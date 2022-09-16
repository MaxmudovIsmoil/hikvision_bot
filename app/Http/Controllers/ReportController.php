<?php

namespace App\Http\Controllers;

use App\Models\TaskDone;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_results(Request $request)
    {
        $start_date = ($request->start_date == NULL) ? date('Y-m-d', strtotime('last week Monday')) : date('Y-m-d', strtotime($request->start_date)); // last monday
        $end_date = ($request->start_date == NULL) ? date('Y-m-d', strtotime('this week Monday')) : date('Y-m-d', strtotime($request->end_date));   // this monday


        $users = User::where('rule', '!=', 'ADMIN')->get();
        $users->load('user_task_done');

        $users_tasks = DB::table('users as u')
            ->select('td.user_id',
                DB::raw('SUM(if(td.status = 1, 1, 0)) AS success'),
                DB::raw('SUM(if(td.status = 0, 1, 0)) AS wait'),
                DB::raw('SUM(if(td.status = -1, 1, 0)) AS cancel')
            )
            ->rightJoin('task_dones as td', 'td.user_id', '=', 'u.id')
            ->whereBetween('td.created_at', [$start_date, $end_date])
            ->groupBy('td.user_id')
            ->get();


        $result = [];
        foreach($users as $user) :

            foreach($users_tasks as $key => $user_task) :

                if ($user_task->user_id == $user->id) :
                    $result[$key]['user_id'] = $user_task->user_id;
                    $result[$key]['full_name'] = $user->full_name;
                    $result[$key]['job_title'] = $user->job_title;
                    $result[$key]['success'] = $user_task->success;
                    $result[$key]['wait'] = $user_task->wait;
                    $result[$key]['cancel'] = $user_task->cancel;
                endif;

            endforeach;

        endforeach;


        return view('report.index', compact('users', 'result', 'start_date', 'end_date'));
    }



    // $u = DB::select('SELECT u.*, sum(if(td.status = 0, 1, 0)) AS wait, sum(if(td.status = 1, 1, 0)) AS success, sum(if(td.status = -1, 1, 0)) AS cancel FROM users u
    //      RIGHT JOIN task_dones AS td ON td.user_id = u.id GROUP BY u.id');

}

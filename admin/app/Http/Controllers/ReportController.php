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
    public function index()
    {
        $users = User::where('rule', '!=', 'ADMIN')->get();
        $users->load('user_task_done');


        $users_tasks = DB::table('users as u')
            ->select('td.user_id', DB::raw('SUM(if(td.status = 1, 1, 0)) AS success'), DB::raw('SUM(if(td.status = 0, 1, 0)) AS wait'), DB::raw('SUM(if(td.status = -1, 1, 0)) AS cancel'))
            ->rightJoin('task_dones as td', 'td.user_id', '=', 'u.id')
            ->groupBy('td.user_id')
            ->get();

        return view('report.index', compact('users', 'users_tasks'));
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $type 1 - day, 2 - week, 3 - month, 4 - year
     * @return \Illuminate\Http\Response
     */
    public function get_report(Request $request)
    {
        try {
//            $u = DB::select('SELECT u.*,
//                            sum(if(td.status = 0, 1, 0)) AS wait, sum(if(td.status = 1, 1, 0)) AS success, sum(if(td.status = -1, 1, 0)) AS cancel
//                            FROM users u
//                            RIGHT JOIN task_dones AS td ON td.user_id = u.id
//                            GROUP BY u.id');


            $users_tasks = DB::table('users as u')
                ->select('td.user_id', DB::raw('SUM(if(td.status = 1, 1, 0)) AS success'), DB::raw('SUM(if(td.status = 0, 1, 0)) AS wait'), DB::raw('SUM(if(td.status = -1, 1, 0)) AS cancel'))
                ->rightJoin('task_dones as td', 'td.user_id', '=', 'u.id')
                ->groupBy('td.user_id')
                ->get();



            $users = User::where('rule', '!=', 'ADMIN')->get();

            $a = 0; $b = 0;

            $html = '';
            $i = 1;
            foreach($users as $user) {
                $done = 0; $failed = 0; $no_click = 0;
                if ($user->user_task_done->count()) {
                    $html .= '<tr class="js_this_tr">
                                <td>' . $i++ . '</td>
                                <td>' . $user->full_name . '</td>
                                <td class="text-center">';
                    foreach ($user->user_task_done as $task_done) {

                        if ($request->interval == 1) { // previous day

                            if (date('Y-m-d', strtotime($task_done->created_at)) == date('Y-m-d', strtotime('-1 day'))) {
                                if ($task_done->status == 1)
                                    $done++;
                                if ($task_done->status == -1)
                                    $failed++;
                                if ($task_done->status == 0)
                                    $no_click++;
                                $a = 111;
                            }
                        }
                        elseif ($request->interval == 2) { // previous week

                            $start_week = date("Y-m-d", strtotime("last week monday"));
                            $end_week = date("Y-m-d", strtotime("last week sunday midnight"));

                            if ($start_week <= date('Y-m-d', strtotime($task_done->created_at))  && date('Y-m-d', strtotime($task_done->created_at)) <= $end_week) {
                                if ($task_done->status == 1)
                                    $done++;
                                if ($task_done->status == -1)
                                    $failed++;
                                if ($task_done->status == 0)
                                    $no_click++;
                                $b = 222;
                            }
                        }
                        elseif ($request->interval == 3) { // this month

                            if (date('Y-m', strtotime($task_done->created_at)) == date('Y-m')) {
                                if ($task_done->status == 1)
                                    $done++;
                                if ($task_done->status == -1)
                                    $failed++;
                                if ($task_done->status == 0)
                                    $no_click++;
                            }
                        }
                        else { // this year
                            if (date('Y', strtotime($task_done->created_at)) == date('Y')) {
                                if ($task_done->status == 1)
                                    $done++;
                                if ($task_done->status == -1)
                                    $failed++;
                                if ($task_done->status == 0)
                                    $no_click++;
                            }
                        }

                    }


                $html .= '<span class="mr-1">
                            <span class="badge badge-success badge-pill">
                                '.$done.' <i class="fas fa-check mr-1"></i>
                                <i class="fas fa-right-long mr-1"></i>
                                '.number_format($done * 100 / ($done + $failed + $no_click), 1, ".", " ").'
                                 <i class="fa-solid fa-percent mr-1"></i>
                            </span>
                            Bajarilgan,
                         </span>
                        <span class="mr-1">
                            <span class="badge badge-danger badge-pill">
                                '.$failed.' <i class="fas fa-times mr-1"></i>
                                <i class="fas fa-right-long mr-1"></i>
                                '.number_format($failed * 100 / ($done + $failed + $no_click), 1, ".", " ") .'
                                 <i class="fa-solid fa-percent mr-1"></i>
                            </span>
                            Bajarilmagan,
                        </span>
                        <span class="mr-1">
                            <span class="badge badge-pill badge-warning">
                                '.$no_click.' <i class="fa-solid fa-question mr-1"></i>
                                <i class="fas fa-right-long mr-1"></i>
                                '.number_format($no_click * 100 / ($done + $failed + $no_click), 1, ".", " ").'
                                <i class="fa-solid fa-percent mr-1"></i>
                            </span>
                            E\'tiborsiz qoldirilgan
                        </span>
                    </td>
                </tr>';
             }
            }

            return response()->json(['status' => true, 'result' => $html, '$a' => $a, '$b' => $b]);
        }
        catch(\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

}

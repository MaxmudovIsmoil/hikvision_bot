<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Models\UserTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = User::where('rule', '!=', 'ADMIN')->where('status', 1)->get();
        $employees->load('user_task');

        $tasks = Task::whereNull('deleted_at')->get();

        return view('user_task.index', compact('employees','tasks'));
    }


    public function one_user_tasks($id)
    {
        try {
            $user_tasks = UserTask::where('user_id', $id)->get();
            return response()->json(['status' => true, 'user_tasks' => $user_tasks]);
        }
        catch (\Exception $exception) {
            return response()->json(['status' => false, 'errors' => $exception->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $error = Validator::make($request->all(), ['user_id' => 'required']);

        if ($error->fails()) {
            return response()->json(array(
                'success' => false,
                'errors' => $error->getMessageBag()->toArray()
            ));
        }
        else {
            try {
                $data = []; $i = 0;
                foreach($request->tasks as $task) {
                    $data[$i]['user_id'] = $request->user_id;
                    $data[$i]['task_id'] = explode(';', $task)[0];
                    $data[$i]['remind_time'] = explode(';', $task)[1];
                    $data[$i]['month'] = $request->month;
                    $data[$i]['year'] = date('Y');
                    $data[$i]['day_off1'] = $request->day_off1;
                    $data[$i]['day_off2'] = $request->day_off2;
                    $data[$i]['created_at'] = date('Y-m-d H:i:s');
                    $data[$i]['updated_at'] = date('Y-m-d H:i:s');
                    $i++;
                }
                UserTask::insert($data);

                return response()->json(['status' => true, 'msg' => 'ok']);
            }
            catch (\Exception $exception) {
                return response()->json(['status' => false, 'errors' => $exception->getMessage()]);
            }
        }
    }


    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), ['user_id' => 'required']);

        if ($validation->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validation->getMessageBag()->toArray()
            ]);
        }
        else {
            try {
                $data = []; $i = 0;
                foreach($request->tasks as $task) {
                    $data[$i]['user_id'] = $request->user_id;
                    $data[$i]['task_id'] = explode(';', $task)[0];
                    $data[$i]['remind_time'] = explode(';', $task)[1];
                    $data[$i]['month'] = $request->month;
                    $data[$i]['year'] = date('Y');
                    $data[$i]['day_off1'] = $request->day_off1;
                    $data[$i]['day_off2'] = $request->day_off2;
                    $data[$i]['created_at'] = date('Y-m-d H:i:s');
                    $data[$i]['updated_at'] = date('Y-m-d H:i:s');
                    $i++;
                }

                UserTask::where(['user_id' => $request->user_id, 'month' => $request->month, 'year' => date('Y')])->delete();

                UserTask::insert($data);

                return response()->json(['status' => true, 'msg' => 'ok']);
            }
            catch (\Exception $exception) {
                return response()->json(['status' => false, 'errors' => $exception->getMessage()]);
            }
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id)
    {
        try {
            UserTask::where(['user_id' => $user_id, 'month' => date('m'), 'year' => date('Y')])->delete();

            return response()->json(['status' => true, 'id' => $user_id]);
        }
        catch (\Exception $exception) {

            if ($exception->getCode() == 23000) {
                return response()->json(['status' => false, 'errors' => 'Ma\'lumotdan foydalanilyapti o\'chirish mumkin emas']);
            }

            return response()->json([
                'status' => false,
                'errors' => $exception->getMessage()
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Models\UserTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserTaskController extends Controller
{

    private function get_tasks() {
        return Task::whereNull('deleted_at')->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = User::where('rule', '!=', 'ADMIN')->get();

        $tasks = $this->get_tasks();

        $user_tasks = UserTask::all();
        $user_tasks->load('user', 'task');


        return view('user_task.index', compact('employees','tasks', 'user_tasks'));
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
        return response()->json(['test' => $request->all()]);

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
                    $data[$i]['task_id'] = $task;
                    $i++;
                }
//                return response()->json(['res' => $request->all(), 'data' => $data]);
                UserTask::insert($data);

                return response()->json(['status' => true, 'msg' => 'ok']);

            } catch (\Exception $exception) {
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
                $product = UserTask::findOrFail($id);
                $product->fill([
                    'name'  => $request->name,
                    'city_id' => $request->city_id,
                    'updated_at'=> date('Y-m-d H:i:s'),
                ]);
                $product->save();

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
    public function destroy($id)
    {
        try {
            $u = UserTask::findOrFail($id);
            $u->delete();

            return response()->json(['status' => true, 'id' => $id]);
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

<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



class UsersController extends Controller
{

    public function index()
    {
        $users = User::where('rule', '!=', 'ADMIN')->get();

        return view('users.index', compact('users'));
    }



    public function oneUser($id)
    {
        try {
            $user = User::findOrFail($id);

            return response()->json(['status' => true, 'user' => $user]);
        }
        catch (\Exception $exception) {
            return response()->json(['status' => false, 'errors' => $exception->getMessage()]);
        }
    }


    public function store(Request $request)
    {

        $error = Validator::make($request->all(), $this->validateData());

        if ($error->fails()) {
            return response()->json(array(
                'success' => false,
                'errors' => $error->getMessageBag()->toArray()
            ));
        }
        else {
            try {
                $phone = str_replace(' ', '', $request->phone);
                User::create([
                    'full_name' => $request->full_name,
                    'job_title' => $request->job_title,
                    'phone'     => $phone,
                    'status'    => (int)$request->status,
                    'chat_id'   => 1,
                    'rule'      => 'USER',
                    'username'  => "test".time(),
                    'password'  => Hash::make(123),
                    'email'     => "test".time()."@gmail.uz",
                    'created_at'=> date('Y-m-d H:i:s'),
                    'updated_at'=> date('Y-m-d H:i:s'),
                ]);

                return response()->json(['status' => true, 'msg' => 'ok']);

            } catch (\Exception $exception) {
                return response()->json(['status' => false, 'errors' => $exception->getMessage()]);
            }
        }
    }


    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), $this->validateData());
        if ($validation->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validation->getMessageBag()->toArray()
            ]);
        }
        else {
            try {
                $phone = str_replace(' ', '', $request->phone);
                $update_data = [
                    'full_name' => $request->full_name,
                    'job_title' => $request->job_title,
                    'phone'     => $phone,
                    'status'    => $request->status,
                    'updated_at'=> date('Y-m-d H:i:s'),
                ];

                $product = User::findOrFail($id);
                $product->fill($update_data);
                $product->save();

                return response()->json(['status' => true, 'msg' => 'ok']);
            }
            catch (\Exception $exception) {
                return response()->json(['status' => false, 'errors' => $exception->getMessage()]);
            }
        }
    }

    public function validateData()
    {
        return [
            'full_name' => 'required',
            'phone'     => 'required',
            'job_title' => 'required'
        ];
    }

    public function destroy($id)
    {
        try {
            $u = User::findOrFail($id);
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


    // user profile

    public function user_profile_show()
    {
        $user_id = Auth::user()->id;
        $user = User::findOrFail($user_id);

        return view('user_profile.index', compact('user'));
    }

    public function user_profile_update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'full_name' => 'required',
            //'phone'     => 'required',
            //'username'  => 'required',
            'password'  => 'required|min:3',
        ]);
        if ($validation->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validation->getMessageBag()->toArray()
            ]);
        }
        else {
            try {
                $phone = str_replace(' ', '', $request->phone);
                $update_data = [
                    'full_name' => $request->full_name,
                    //'phone'     => $phone,
                    'password'  => Hash::make($request->password),
                    'updated_at'=>  date('Y-m-d H:i:s'),
                ];

                $product = User::findOrFail($id);
                $product->fill($update_data);
                $product->save();

                return response()->json(['status' => true, 'msg' => 'ok']);
            }
            catch (\Exception $exception) {
                return response()->json(['status' => false, 'errors' => $exception->getMessage()]);
            }
        }
    }
}

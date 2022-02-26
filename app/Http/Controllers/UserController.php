<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Property;
use Validator;
class UserController extends Controller
{
    //USER LOGIN RFUNCTION
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
                $user = Auth::user();
                $success['token'] =  $user->createToken('auth-token')->accessToken;
                $data['token'] =$success['token'];
                $data['user'] = $user;
                if($user->email_verified_at)
                {
                    return response()->json(['success' => true,'message'=>'Login Successful','data'=>$data]);
                }
                else{
                    return response()->json(['success' => false,'message'=>'User Not Verified']);

                }
        } else {
            return response()->json(['success'=>false,'message' => 'Username or Password doesn`t match'], 401);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'role'=>'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            // 'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['success'=>false,'error' => $validator->errors()], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('auth-token')->accessToken;
        $success['name'] =  $user->name;
        $data['token'] = $success['token'];
        $data['user'] = $user;
        return response()->json(['success' => true,'message'=>'Registration Successful','data'=>$data]);
    }


        //CHANGE PASSWORD FUNCTION
        public function changePassword(Request $request)
        {
            $user = Auth::user();
            if($user)
            {
                $validator = Validator::make($request->all(), [
                    'old_password'=>'required',
                    'password' => 'required',
                    'c_password' => 'required|same:password',
                ]);
                if ($validator->fails()) {
                    return response()->json(['success'=>false,'error' => $validator->errors()], 401);
                }
                else{
                    $email = $user->email;
                    if ( Auth::guard('web')->attempt([
                        'email' => $email,
                        'password' => request('old_password')
                    ])) {
                        $password =bcrypt($request->password);
    
                        $update = User::where('email',$email)
                                ->update([
                                    'password'=>$password
                                ]);
                        if($update)
                        {
                            return response()->json(['success'=>true,'message'=>'password changed successful']);
                        }
                        else{
                            return response()->json(['success'=>false,'message'=>'Error Changing Password']);
                        }
                    }
                    else{
                        return response()->json(['success'=>false,'message'=>'incorrect old password']);
                    }
               
                }
    
            }

        }
        public function allusers(Request $request)
        {
            $users = User::where('email_verified_at',null)->get();
            if(count($users)>0)
            {
                return response()->json(['success'=>true,'data'=>$users]);
            }
            else{
                return response()->json(['success'=>false,'message'=>'No unverified Users']);
            }
        }
        public function verifyUsers(Request $request,$id)
        {
            $verify = User::where('id',$id)->update([
                'email_verified_at'=>date('y-m-d')
            ]);
            if($verify)
            {
                return response()->json(['success'=>true,'message'=>'verified successfully']);
            }
            else{
                return response()->json(['success'=>false,'message'=>'Error verifying']);
            }
        }
        public function deleteUsers(Request $request,$id)
        {
            $delete = User::where('id',$id)->delete();
            if($delete)
            {
                return response()->json(['success'=>true,'message'=>'Rejected']);
            }
            else{
                return response()->json(['success'=>false,'message'=>'Error Deleting user']);
            }
        }

        public function updateUser(Request $request,$id)
        {
            $user = Property::where('id',$id)->select('user_id')->get();
            // return response()->json(['data'=>$user]);
            $update = User::where('id',$user[0]->user_id)->update([
                'email'=>$request->email
            ]);
            if($update)
            {
                return response()->json(['success'=>true]);
            }
            else{
                return response()->json(['success'=>false]);
            }
        }
}

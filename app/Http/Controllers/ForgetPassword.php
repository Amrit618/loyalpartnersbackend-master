<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Mail\SendMail;
use Validator;
use App\User;

class ForgetPassword extends Controller
{
    //
    public function sendMail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'=>'required'
        ]);
        $length = 8;
        $new_pw = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)))), 1, $length);
       
        $update = User::where('email', $request->email)->update([
           'password'=>bcrypt($new_pw)
       ]);

        $to_name = 'Loyal Partner';
        $to_email = $request->email;
        $data = array("password"=>$new_pw);
        \Mail::send('forgetpw', $data, function ($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
        ->subject('Loyal Partners : Change Password');
            $message->from('loyalpartners91@gmail.com', 'Change Password');
        });
        return response()->json(['message'=>'email send']);
    }
}

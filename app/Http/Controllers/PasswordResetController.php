<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PasswordReset;

use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Hash;

use App\Models\User;

use Illuminate\Mail\Message;

use Illuminate\Support\Str;

use Carbon\Carbon;

class PasswordResetController extends Controller
{
    
public function sent_reset_password_mail(Request
$request){

$request->validate([

'email'=>'required|email',

]);

$email = $request->email;

//check user's email exists or not

$user = User::where('email',$email)->first();

if(!$user){

return response([

'message'=>'email does not exist',

'ststus'=>'failed'

],404);

}

//genrate the tokens

$token = Str::random(60);

//save data to pass reset table

PasswordReset::create([

'email'=>$email,

'token'=>$token,

'created_at'=>Carbon::now()

]);

("http://127.0.0.1:3000/api/user/reset".$token);

Mail::send('reset',['token'=>$token],function(Message
$message)use($email){

$message->subject('Reset your password');

$message->to($email);

});

return response([

'message'=>'password reset email sent check your email',

'ststus'=>'success'

],);

}
}

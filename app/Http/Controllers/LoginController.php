<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{


    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $users_email = DB::table('users')->pluck('email');
        $is_logged = false;
 
        foreach ($users_email as $email){
            if($email == $credentials['email']){
                $users_password = DB::table('users')->pluck('password');
                foreach($users_password as $password){
                    if($password == $credentials['password']){
                        $is_logged = true;
                    }
                }
            }
        }

        return  $is_logged;
    }
    
}

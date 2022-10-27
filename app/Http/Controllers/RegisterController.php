<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\user;

class RegisterController extends Controller
{
    //

    function addUser(Request $req){
        $user = new User;
        $user->email = $req->email;
        $user->password=$req->password;
        $user->save();
    }
}
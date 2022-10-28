<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GiornateController extends Controller
{
    public function ottieniGiornate(Request $request){

        $data = $request->all();
        $email = $data['email'];

        $users_email = DB::table('users')->pluck('email');

        foreach ($users_email as $user){
            if($user == $email){
                $giornate = DB::table('giornate')->select('*')->where([
                    'email' => $email
                ])->get();

                return $giornate;

            }
        }
    }
}

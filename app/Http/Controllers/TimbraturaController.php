<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimbraturaController extends Controller
{
    public function checkEntrata(Request $request){
        $data = $request->all();
        $date = $data['date'];
        $orarioEntrata = $data['orarioEntrata'];
        $email = $data['email'];
        
        $users_email = DB::table('users')->pluck('email');

        foreach ($users_email as $user){
            if($user == $email){
                DB::table("giornate")->insert([
                    'email' => $email,
                    'date' => $date,
                    'orarioEntrata' => $orarioEntrata,
                    'orarioUscita' => "0",
                ]);
            }
        }
    }

    public function checkUscita(Request $request){
        $data = $request->all();
        $date = $data['date'];
        $orarioUscita = $data['orarioUscita'];
        $email = $data['email'];
        
        
        $users_email = DB::table('users')->pluck('email');
        

        foreach ($users_email as $user){
            if($user == $email){
                $orarioEntrata = DB::table('giornate')->select('orarioEntrata')->where([
                    'email'=> $email,
                    'date' => $date,
                ])->get();
               
                $array1 = explode(":",$orarioEntrata[0]->orarioEntrata);
                $oreEntrata = (int)$array1[0];
                $array2 = explode(":",$orarioUscita);
                $oreUscita = (int)$array2[0];

                $oreSuperate = $oreUscita - $oreEntrata;

                if($oreSuperate>=8){
                    $oreRaggiunte = true;
                }else{
                    $oreRaggiunte = false;
                }
                
                DB::table('giornate')->updateOrInsert(
                        ['email' => $email, 'date' => $date, 'orarioUScita' => "0"],
                        ['orarioUscita' => $orarioUscita,'oreLavorativeRaggiunte'=>$oreRaggiunte]
                    );

                return $oreRaggiunte;
            }
        }

        
    }
}

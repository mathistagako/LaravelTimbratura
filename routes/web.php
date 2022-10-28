<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TimbraturaController;
use App\Http\Controllers\GiornateController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return view('welcome');
});


//REGISTRAZIONE

Route::post('/register',[RegisterController::class,'addUser']);

//LOGIN

Route::post('/login',[LoginController::class,'authenticate']);

//TIMBRATURA

Route::post('/timbroEntrata',[TimbraturaController::class,'checkEntrata']);
Route::post('/timbroUscita',[TimbraturaController::class,'checkUscita']);

//GET GIORNATE

Route::post('/getGiornate',[GiornateController::class,'ottieniGiornate']);

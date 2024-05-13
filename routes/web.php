<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Middleware\Admin;

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

//Route::get('/', function () {
//    return view('layouts.master');
//});

Route::get('users', [\App\Http\Controllers\UserController::class, 'index'])->name('user.index');


/////////inloggen///////
//Route::get('login', [SessionController::class, 'create'])->middleware('guest');
//Route::post('login', [SessionController::class, 'store'])->middleware('guest');
/////uitloggen/////
//Route::post('logout', [SessionController::class, 'destroy'])->middleware('auth');

////controleren als het admin of gebruiker is////
/// controleren middleware - kernel - homecontroller - redirect voor admin(B-medewerker)(account type 1)
Route::get('admin', [HomeController::class, 'admin'])->middleware('admin');

/// controleren middleware - kernel - homecontroller - redirect voor user(ZZPer)(account type 0)
Route::get('home', [HomeController::class, 'home'])->middleware('user');

Route::get('start/{node?}', [GameController::class, 'index'])->name('Start')->where('page', '[0-9]+');
Route::get('start/{node?}/no/{relation?}', [GameController::class, 'no'])->name('No')->where('node', '[0-9]+');
Route::get('start/{node?}/yes/{relation?}', [GameController::class, 'yes'])->name('Yes')->where('node', '[0-9]+');

// score opslaan
Route::post('start/{node?}/no/{relation?}', [GameController::class, 'score_opslaan'])->name('score_opslaan')->where('page', '[0-9]+');
Route::post('start/{node?}/yes/{relation?}', [GameController::class, 'score_opslaan'])->name('score_opslaan')->where('page', '[0-9]+');


// cHARACTER OPSLAAN
Route::post('add/', [GameController::class, 'store'])->name('Add');

Route::get('start/leaderboard', [GameController::class, 'leaderboard'])->name('leaderboard');;

Route::get('start/scoreinvoer', [GameController::class, 'score_invoer'])->name('score_invoer');;
// Route::post('start/scoreopslaan', [GameController::class, 'score_opslaan'])->name('score_opslaan');;



//Route::get('start/{node?}/{relation?}/{node?}', [GameController::class, 'next'])->name('next')->where('page', '[0-9]+');



///////////////////////////test////////////////////////////
//Route::post('/loop-up', [GameController::class, 'handleLoopUpRequest'])->name('handle_loop_up');
Route::post('/handle-loop-up', [GameController::class, 'handleLoopUpRequest'])->name('handle_loop_up');

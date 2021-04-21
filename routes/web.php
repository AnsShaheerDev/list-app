<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ListController;
use App\Http\Controllers\CardController;

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

Auth::routes([
	'register' => false,
	'reset' => false,
	'verify' => false,
]);

Route::middleware(['auth'])->group(function () {

Route::resource('lists', ListController::class)->except([
    'update', 'destroy'
]);

Route::resource('cards', CardController::class);

});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

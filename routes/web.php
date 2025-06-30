<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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
    return view('home');
});
Route::get('/users', [UserController::class, 'showusers'])->name('users.showusers');
Route::post('/user/create', [UserController::class, 'create'])->name('user.create');
Route::get('/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
Route::delete('/user/delete/{id}', [UserController::class, 'delete'])->name('user.delete');

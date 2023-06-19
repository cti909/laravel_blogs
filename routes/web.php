<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

/**
 * route account
 */
Route::prefix('')->group(function () {
    Route::get('', [UserController::class, 'home'])->name('homepage.home');
    Route::get('/login', [UserController::class, 'loginForm'])->name('accounts.loginForm');
    Route::post('/login-check', [UserController::class, 'loginCheck'])->name('accounts.loginCheck');
    Route::get('/logout', [UserController::class, 'logout'])->name('accounts.logout');
    Route::get('/register', [UserController::class, 'registerForm'])->name('accounts.registerForm');
    Route::post('/user-create', [UserController::class, 'accountCreate'])->name('accounts.accountCreate');
});
/**
 * route post
 */
Route::prefix('posts')->group(function () {
    Route::get('', [PostController::class, 'index'])->name('posts.index');
    Route::get('/detail/{post_id}', [PostController::class, 'detail'])->name('posts.detail');
    Route::post('/like/{post_id}', [PostController::class, 'likePost'])->name('posts.like');
    Route::post('/create', [PostController::class, 'create'])->name('posts.create');
    Route::put('/update/{post_id}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/delete/{post_id}', [PostController::class, 'delete'])->name('posts.delete');
});
// Route::prefix('')->group(function () {
//     // neu dua detail len tren create thi create bi sai
//     Route::get('', [StudentController::class, 'index'])->name('students.index');
//     Route::get('/redirect_create', [StudentController::class, 'redirect_create'])->name('students.redirect_create');
//     Route::get('/redirect_edit/{student_id}', [StudentController::class, 'redirect_edit'])->name('students.redirect_edit');
//     Route::post('/create', [StudentController::class, 'create'])->name('students.create');
//     Route::put('/edit/{student_id}', [StudentController::class, 'edit'])->name('students.edit');
//     Route::delete('/delete/{student_id}', [StudentController::class, 'delete'])->name('students.delete');
//     Route::get('/{student_id}', [StudentController::class, 'detail'])->name('students.detail');
//     // Route::match(['get', 'post'], '/create', [StudentController::class, 'create'])->name('students.create');
//     // Route::match(['get', 'put'], '/edit/{students}', [StudentController::class, 'edit'])->name('students.edit');
//     // Route::resource('students', StudentController::class);
// });

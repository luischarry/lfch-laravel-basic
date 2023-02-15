<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\BookController as Book;
use App\Http\Controllers\AuthorController as Author;
use App\Http\Controllers\AuthController as Auth;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/sing_up',[Auth::class, 'sing_up']);
Route::post('/login',[Auth::class, 'login']);

Route::middleware(['auth:sanctum','solo_usuario_administrador'])->get('books',[Book::class, 'index']);

//Route::get('books',[Book::class, 'index']);
Route::get('books/title={value}',[Book::class, 'getByTitle']);
Route::get('books/{id}',[Book::class,'getById']);
Route::post('books/{author_id}',[Book::class, 'store']);
// Route::post('books',[Book::class, 'store']);
Route::middleware(['auth:sanctum','solo_usuario_administrador'])->delete('books/{id}', [Book::class, 'destroy']);
Route::put('books', [Book::class, 'update']);

Route::get('authors', [ Author::class, 'index']);

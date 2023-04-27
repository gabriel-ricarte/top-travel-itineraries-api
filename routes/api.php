<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/post/titles', [PostController::class, 'createTitles']);
Route::get('/post/body/{title}', [PostController::class, 'createPost']);
Route::get('/posts', [PostController::class, 'frontPage']);
Route::get('/countries', [CategoryController::class, 'countries']);

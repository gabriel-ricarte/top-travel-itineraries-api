<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LanguageController;
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

Route::get('/country', [LocationController::class, 'countriesFromLatinAmerica']);
Route::get('/country/{country}/cities', [LocationController::class, 'citiesFromCountry']);

Route::get('/country/{country}/cities/{city}/touristic-point', [LocationController::class, 'touristicPointsFromCity']);
Route::get('/country/{country}/cities/{city}/touristic-point/{location}', [LocationController::class, 'articleFromTouristicPoint']);
Route::post('/images', [LocationController::class, 'imagesFromArticle']);
Route::get('/country/{country}/cities/{city}/touristic-point-articles', [LocationController::class, 'articlesForTouristicPoints']);

Route::get('/country/{country}/cities/{city}/touristic-point-articles', [LocationController::class, 'articlesForTouristicPoints']);


Route::prefix('config')->group(function () {
    Route::get('/language/setup', [LanguageController::class, 'setupLanguages']);
    Route::get('/country/setup', [LocationController::class, 'setupCountries']);
});

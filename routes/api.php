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

Route::get('/country/{country}/cities/{city}/touristic-point/setup', [LocationController::class, 'touristicPointsFromCity']);
Route::post('/images', [LocationController::class, 'imagesFromArticle']);
Route::get('/country/{country}/cities/{city}/touristic-point', [LocationController::class, 'articlesForTouristicPoints']);

    Route::get('/language', [LanguageController::class, 'index']);
    Route::get('/language/setup', [LanguageController::class, 'setupLanguages']);
    Route::post('/country/setup', [LocationController::class, 'setupCountries']);
    Route::get('/country/{language}', [LocationController::class, 'countriess']);
    Route::post('/city/setup', [LocationController::class, 'setupCities']);
    Route::get('/city/{country}/{language}', [LocationController::class, 'Cities']);

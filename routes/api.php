<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\SetupController;
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
Route::get('{touristc_point}/article', [LocationController::class, 'articleFromTouristicPoint']);

    Route::get('/language', [LanguageController::class, 'index']);
    Route::get('/language/setup', [LanguageController::class, 'setupLanguages']);
    Route::post('/country/setup', [LocationController::class, 'setupCountries']);
    Route::get('/country/language/{language}', [LocationController::class, 'countriess']);
    route::get('/index/{language}',[SetupController::class, 'Setuptexts']);
    Route::post('/city/setup', [LocationController::class, 'setupCities']);
    Route::post('/allcity/setup/{language}', [SetupController::class, 'setupAllCities']);
    Route::get('/city/{country}/{language}', [LocationController::class, 'Cities']);
    Route::get('/country/{country}/cities/{city}/touristic-point/{location}', [LocationController::class, 'articleFromTouristicPointSetup']);
    
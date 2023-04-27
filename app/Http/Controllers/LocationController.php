<?php

namespace App\Http\Controllers;

use App\Services\ChatGptService;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    private ChatGptService $chatGptService;

    public function __construct(ChatGptService $chatGptService) {
        $this->chatGptService = $chatGptService;
    }

    public function countriesFromLatinAmerica()
    {
       return $this->chatGptService->getAllCountries();
    }

    public function citiesFromCountry(string $country)
    {
       return $this->chatGptService->getAllCitiesByCountry($country);
    }

    public function touristicPointsFromCity(string $city, string $country)
    {
       return $this->chatGptService->getTouristicPointsByCountryAndCity($city, $country);
    }

    public function articleFromTouristicPoint(array $location, string $city, string $country)
    {
       return $this->chatGptService->createArticle($location, $city, $country);
    }

    public function articlesForTouristicPoints(string $city, string $country)
    {
       $touristicPoints = $this->touristicPointsFromCity($city, $country);

       $touristicPointsArticles = $this->articleFromTouristicPoint($touristicPoints->{'touristic-points'}, $city, $country);

       return $touristicPointsArticles;
    }
}

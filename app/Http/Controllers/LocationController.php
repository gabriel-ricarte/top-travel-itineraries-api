<?php

namespace App\Http\Controllers;

use App\Services\ChatGptService;
use App\Services\ImageGeneratorService;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    private ChatGptService $chatGptService;
    private ImageGeneratorService $imageGeneratorService;

    public function __construct(ChatGptService $chatGptService, ImageGeneratorService $imageGeneratorService) {
        $this->chatGptService = $chatGptService;
        $this->imageGeneratorService = $imageGeneratorService;
    }

    public function countries()
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

    public function imagesFromArticle(Request $request)
    {
       
       return $this->imageGeneratorService->getPostImages($request['description']);
    }

    public function articlesForTouristicPoints(string $city, string $country)
    {
       $touristicPoints = $this->touristicPointsFromCity($city, $country);

       $touristicPointsArticles = $this->articleFromTouristicPoint($touristicPoints->{'touristic-points'}, $city, $country);

       return $touristicPointsArticles;
    }
}

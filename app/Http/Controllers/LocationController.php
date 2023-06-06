<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Services\ChatGptService;
use App\Services\ImageGeneratorService;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Country;

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

   /**
     * Populates the database with the countries from ChatGPT
     * 
     * @return void
     */
    public function setupCountries(Request $u) {
      $u = $u->validate([
         "language" => "required",
     ]);
      
     $language = Language::where('name', $u['language'])->first();
     $languageId= $language->id;
      try {
         $countries = $this->chatGptService->getAllCountriesByLanguage($u['language']);
      } catch (\Throwable $th) {
         throw new \Exception("MALDITO CHATGPT");
      }    
      
      foreach ($countries->countries as $key => $value) {
          Country::create([
            'name' => strtolower($value->name),
            'iso3' => strtolower($value->iso3Code),
            'languageId'=> $languageId,
            'isActive' => true
          ]);
      }

      return response('Countries are created!', 201)
                ->header('Content-Type', 'application/json');
  }

  public function countriess(string $language) {
   $language = Language::where('name', $language)->first();
   $languageID = $language->id;
   $countries = Country::where('languageId', $languageID)->get();
   $countries = collect($countries)->pluck('name')->toArray();
   return $countries;
}

  public function setupCities(Request $u) {
      $u = $u->validate([
         "language" => "required",
         "country" => "required",
     ]);
      
      $language = Language::where('name', $u['language'])->first();
      $languageId= $language->id;
      try {
         $cities = $this->chatGptService->getAllCitiesByLanguage($u['country'],$u['language']);
      } catch (\Throwable $th) {
         throw new \Exception("MALDITO CHATGPT");
      }    
      foreach ($cities->cities as $key => $value) {
         $country = Country::where('iso3', $value->iso3Code)->first();
         $countryId = $country->id;
        
          City::create([
            'name' => strtolower($value->name),
            'countryId' => $countryId,
            'languageId'=> $languageId,
            'isActive' => true
          ]);
      }

      return response('Countries are created!', 201)
                ->header('Content-Type', 'application/json');
  }
  public function Cities(string $country,string $language) {
   $language = Language::where('name', $language)->first();
   $languageId= $language->id;
   $country = Country::where('name', $country)->first();
   $countryId = $country->id;
   $cities = City::where('languageId', $languageId)
   ->where('countryId', $countryId)
   ->get();
   $cities = collect($cities)->pluck('name')->toArray();
  
   return $cities;
}
}

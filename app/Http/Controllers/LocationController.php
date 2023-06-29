<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\City;
use App\Services\ChatGptService;
use App\Services\ImageGeneratorService;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Country;
use App\Models\PostImage;
use Illuminate\Support\Facades\Storage;
use App\Models\TouristicPoint;

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

    public function touristicPointsFromCity(string $country,string $city)
    {
      $response = $this->chatGptService->getTouristicPointsByCountryAndCity($city, $country); 
      $city = City::where('name',$city)->first();
      $cityId = $city->id;
      
      foreach ($response->touristic_points as $touristicPoint) {
         $url = $this->imageGeneratorService->getPostImages($touristicPoint -> description);
         $url = $url[0]->url;
         $newTouristicPoint = TouristicPoint::create([
            'name' =>$touristicPoint->name,
            'snake_case_name' =>$touristicPoint->snake_case_name,
            'cityId' => $cityId,
            'description'=> $touristicPoint->description,
            'espacial_description'=> $touristicPoint->espacial_description,
            'latitude'=> $touristicPoint->location->lat,
            'longitude'=> $touristicPoint->location->long,
         
         ]);
         Storage::disk('public')->put($touristicPoint->snake_case_name.'.png', file_get_contents($url));
         $imageUrl = asset('storage/'.$touristicPoint->snake_case_name.'.png');
         PostImage::create([
            'imageUrl'=> $imageUrl,
            'touristic_point_id'=> $newTouristicPoint->id
         ]);
      }

      return $response;
    }

    public function articleFromTouristicPointSetup(string $country, string $city,string $location)
    {
     
      $response =$this->chatGptService->createArticle($location, $city, $country);
      $city = City::where('name',$city)->first();
      $cityId = $city->id;
      $country = Country::where('name',$country)->first();
      $countryId = $country->id;
      $description = implode('\n',$response->description);
      $activities = implode('\n',$response->activities);
      
      Article::create([
        'name'=>$response->name,
        'snake_case_name'=>$response->snake_case_name,
        'description'=>$description,
        'activities'=>$activities,
        'hours'=>$response->hours,
        'admission'=>$response->admission,
        'countryId'=>$countryId,
        'cityId'=>$cityId,
      ]);
       return $response ;
    }
    public function articleFromTouristicPoint(string $touristc_point)
    {
      $response = Article::where("snake_case_name",$touristc_point)->first();
      if(empty($response)){
         $response = TouristicPoint::where("snake_case_name",$touristc_point)->first();
         $city = City::where('id',$response->cityId)->first();
         $country = Country::where('id',$city->countryId)->first()->name;
         $response = $this->articleFromTouristicPointSetup($country,$city->name,$touristc_point);
      }
       return $response ;
    }

    public function imagesFromArticle(Request $request)
    {
       
       return $this->imageGeneratorService->getPostImages($request['description']);
    }

    public function articlesForTouristicPointsSetup(string $city, string $country)
    {
       $touristicPoints = $this->touristicPointsFromCity($city, $country);

       $touristicPointsArticles = $this->articleFromTouristicPoint($touristicPoints->{'touristic-points'}, $city, $country);

       return $touristicPointsArticles;
    }

    public function articlesForTouristicPoints(string $country, string $city)
    {
      $city = City::where('name',strtolower($city))->first();
      $cityId = $city->id;
      $touristicPointsArticles = TouristicPoint::where('cityId',$cityId)->with('postImages')->get();
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
      
     $language = Language::where('code', $u['language'])->first();
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
   $language = Language::where("code",$language)->first();
   if(empty($language)){
            $language = Language::first();
   }
   $languageId = $language->id;
   $countries = Country::where('languageId', $languageId)->get();
   
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
      $languageName=$language->name;
      try {
         $cities = $this->chatGptService->getAllCitiesByLanguage($u['country'],$languageName);
      } catch (\Throwable $th) {
         throw new \Exception("MALDITO CHATGPT");
      }    
      foreach ($cities->cities as $key => $value) {
         $country = Country::where('iso3', $u['country'])->first();
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
  public function setupAllCities(string $language) {
  
   
   $language = Language::where('name', $language)->first();
   $languageId= $language->id;
   $countries = Country::all();

      $cities = $this->chatGptService->getAllCitiesFromAllCountriesByLanguage($language);
      dd($cities);
 
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
   $language = Language::where('code', $language)->first();
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

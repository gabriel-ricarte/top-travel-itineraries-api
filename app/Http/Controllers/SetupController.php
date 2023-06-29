<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Language;
use App\Services\ChatGptService;
use App\Models\Article;
use App\Models\City;
use App\Services\ImageGeneratorService;
use App\Models\Country;
use App\Models\PostImage;
use Illuminate\Support\Facades\Storage;
use App\Models\TouristicPoint;
use App\Models\Texts;

class SetupController extends Controller
{
    private ChatGptService $chatGptService;

    public function __construct(ChatGptService $chatGptService) {
        $this->chatGptService = $chatGptService;
    }
    public function setupLanguages() {
        $languages = $this->chatGptService->getAllSupportedLanguages();
        // $isLanguageSettedUp = Language::find(1);

        // if (!empty($isLanguageSettedUp)) {
        //     return response('Languages are already created!', 200)
        //           ->header('Content-Type', 'application/json');
        // }

        foreach ($languages->languages as $key => $value) {
            Language::create([
                'name' => strtolower($value->language),
                'iso3' => $value->iso3Code,
                'isActive' => true
            ]);
        }

        return response('Languages are created!', 201)
                  ->header('Content-Type', 'application/json');
    }
    public function setupAllCities(string $language) {
  
   
        $language = Language::where('name', $language)->first();
        $languageId= $language->id;
        $countries = Country::where('languageId',$languageId)->get();
        foreach($countries as $value){
            $response = $this->setupCities($value->name,$language->name);
            sleep(30);
        }
        
        
     
        return response('Countries are created!', 201)
                  ->header('Content-Type', 'application/json');
     }

     public function setupCities(string $country,string $language) {
        $language = Language::where('name', $language)->first();
        $languageId= $language->id;
        
           $cities = $this->chatGptService->getAllCitiesByLanguage($country,$language);
           $country = Country::where('name', $country)->first();
           $countryId = $country->id;

        foreach ($cities->cities as $key => $value) {
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
    public function Setuptexts(string $language){
        $language = Language::where("code",$language)->first();
        if(empty($language)){
            $language = Language::first();
        }
        $languageId = $language->id;
        $response = Texts::where('languageId',$languageId)->first();
        if(empty($response)){
            $response =$this->chatGptService->getText($language->name);
            Texts::create([
                'banner'=> $response->banner,
                'home'=> $response->home,
                'about'=> $response->about,
                'destination'=> $response->destination,
                'tour'=> $response->tour,
                'city'=> $response->city,
                'country'=> $response->country,
                'travel'=> $response->travel,
                'languageId'=>$languageId,
            ]);
        }
        return $response;
    }
}

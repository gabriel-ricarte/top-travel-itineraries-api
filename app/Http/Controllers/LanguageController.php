<?php

namespace App\Http\Controllers;

use App\Services\ChatGptService;
use Illuminate\Http\Request;
use App\Models\Language;


class LanguageController extends Controller
{
    private ChatGptService $chatGptService;

    public function __construct(ChatGptService $chatGptService) {
        $this->chatGptService = $chatGptService;
    }

    /**
     * Returns all supported languages by ChatGPT
     * 
     * @return array
     */
    public function supportedLanguages() {

    }

    /**
     * Populates the database with the supported languages from ChatGPT
     * 
     * @return void
     */
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
}

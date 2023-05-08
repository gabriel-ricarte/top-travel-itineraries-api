<?php

namespace App\Http\Controllers;

use App\Services\ChatGptService;
use Illuminate\Http\Request;

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
        
    }
}

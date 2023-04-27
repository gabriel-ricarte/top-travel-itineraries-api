<?php

namespace App\Http\Controllers;

use App\Services\ChatGptService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private ChatGptService $chatGptService;

    public function __construct(ChatGptService $chatGptService) {
        $this->chatGptService = $chatGptService;
    }

    public function countries()
    {
       return $this->chatGptService->getAllCountries();
    }
}

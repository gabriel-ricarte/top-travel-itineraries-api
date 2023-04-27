<?php

namespace App\Http\Controllers;

use App\Services\ChatGptService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    private ChatGptService $chatGptService;

    public function __construct(ChatGptService $chatGptService) {
        $this->chatGptService = $chatGptService;
    }

    public function createTitles()
    {
       return $this->chatGptService->createPostTitles();
    }

    public function createPost($title)
    {
       return $this->chatGptService->createPostByTitle($title);
    }

    public function frontPage() 
    {
        $titles = $this->createTitles();

        $posts = array_map(function($item) {
            return [
                'author' => 'Gabriel Ricarte',
                'title' => $item,
                'body' => $this->createPost($item)
            ];
        }, $titles->titles);

        return $posts;
    }
}

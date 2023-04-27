<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class ChatGptService
{
    private string $chatGptKey;
    private string $chatGptUrl;
    private string $chatGptModel;

    public function __construct() {
        $this->chatGptKey = env('CHAT_GPT_KEY');
        $this->chatGptUrl = env('CHAT_GPT_URL');
        $this->chatGptModel = env('GPT_ID_MODEL');
    }

    /**
     * Create title for the posts
     * 
     * @return array
     */
    public function createPostTitles()
    {
        $client = new Client([
            'base_uri' => $this->chatGptUrl,
            'headers' => [
                'Authorization' => "Bearer {$this->chatGptKey}",
                'Content-Type' => 'application/json',
            ],
        ]);
    
        $response = $client->request('POST', '', [
            'json' => [
                'model' => $this->chatGptModel,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => 'Create 5 post titles and return them to me as a json response always inside titles'
                    ]
                ],
            ],
        ]);
    
        $responseBody = $response->getBody()->getContents();
        $responseObj = json_decode( $responseBody);
        $responseFromAssistant = $this->retrieveCompletion( $responseObj);
        return $responseFromAssistant;
    }

    /**
     * Create post for the title
     * @var string $title
     * 
     * @return object
     */
    public function createPostByTitle(string $title): object
    {
        $client = new Client([
            'base_uri' => $this->chatGptUrl,
            'headers' => [
                'Authorization' => "Bearer {$this->chatGptKey}",
                'Content-Type' => 'application/json',
            ],
        ]);
    
        $response = $client->request('POST', '', [
            'json' => [
                'model' => $this->chatGptModel,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => "Create a blog post with 80 words for this post title: {$title}"
                    ]
                ],
            ],
        ]);
    
        $responseBody = $response->getBody()->getContents();
        $responseObj = json_decode( $responseBody);
        $responseFromAssistant = $this->retrieveCompletion($responseObj);

        return $responseFromAssistant;
    }

    /**
     * Retrieve all countries from Latin America
     * 
     * @return object
     */
    public function getAllCountries(): object
    {
        $client = new Client([
            'base_uri' => $this->chatGptUrl,
            'headers' => [
                'Authorization' => "Bearer {$this->chatGptKey}",
                'Content-Type' => 'application/json',
            ],
        ]);
    
        $response = $client->request('POST', '', [
            'json' => [
                'model' => $this->chatGptModel,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => 'give me the list of all countries from latin america as a json format sorted ascendent alphabetically always inside countries'
                    ]
                ],
            ],
        ]);
    
        $responseBody = $response->getBody()->getContents();
        $responseObj = json_decode( $responseBody);
        $responseFromAssistant = $this->retrieveCompletion($responseObj);
        return $responseFromAssistant;
    }

    /**
     * Retrieve reponse from chat completion
     * @var Object $responseObj
     * 
     * @return object
     */
    public function retrieveCompletion(Object $responseObj)
    {
        $responseFromAssistant = json_decode($responseObj->choices[0]->message->content);
        if (!empty($responseFromAssistant )) {
            return  $responseFromAssistant;
        }

        return $responseObj;
    }
}

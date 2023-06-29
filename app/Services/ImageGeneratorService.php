<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class ImageGeneratorService
{
    private string $chatGptKey;
    private string $imageGeneratorUrl;
    private string $chatGptModel;
    private Client $client;

    public function __construct() {
        $this->chatGptKey = env('CHAT_GPT_KEY');
        $this->imageGeneratorUrl = env('IMAGE_GENERATOR_URL');
        $this->chatGptModel = env('GPT_ID_MODEL');
        $this->client = new Client([
            'base_uri' => $this->imageGeneratorUrl,
            'headers' => [
                'Authorization' => "Bearer {$this->chatGptKey}",
                'Content-Type' => 'application/json',
            ],
        ]);
    }


    /**
     * Retrieve all countries from Latin America
     * 
     * @return object
     */
    public function getPostImages($imageDescription)
    {
        $response = $this->client->request('POST', '', [
            'json' => [
                'prompt' => $imageDescription,
                'n' => 1,
                'size' => '1024x1024'
            ],
        ]);
    
        $responseBody = $response->getBody()->getContents();
        $responseObj = json_decode( $responseBody);

        return $responseObj->data;
    }

    /**
     * Retrieve reponse from chat completion
     * @var Object $responseObj
     * 
     * @return object|string
     */
    public function retrieveCompletion(Object $responseObj)
    {
        $responseFromAssistant = json_decode($responseObj->choices[0]->message->content);
        if (!empty($responseFromAssistant )) {
            return  $responseFromAssistant;
        }

        return $responseObj->choices[0]->message->content;
    }
}

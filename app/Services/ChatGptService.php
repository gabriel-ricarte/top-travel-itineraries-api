<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class ChatGptService
{
    private string $chatGptKey;
    private string $chatGptUrl;
    private string $chatGptModel;
    private Client $client;

    public function __construct() {
        $this->chatGptKey = env('CHAT_GPT_KEY');
        $this->chatGptUrl = env('CHAT_GPT_URL');
        $this->chatGptModel = env('GPT_ID_MODEL');
        $this->client = new Client([
            'base_uri' => $this->chatGptUrl,
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
    public function getAllCountries(): object
    {
        $response = $this->client->request('POST', '', [
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
     * Retrieve all countries from Latin America
     * 
     * @return object
     */
    public function getAllCitiesByCountry(string $country): object
    {
        $response = $this->client->request('POST', '', [
            'json' => [
                'model' => $this->chatGptModel,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => "give me the top 20 touristic cities from {$country} as a json format sorted ascendent alphabetically always inside cities"
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
    public function getTouristicPointsByCountryAndCity(string $city, string $country): object
    {
        $response = $this->client->request('POST', '', [
            'json' => [
                'model' => $this->chatGptModel,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => "give me the top 5 touristic points from {$city} in {$country} as a json format sorted ascendent alphabetically always inside touristic-points"
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
     * @return object|string
     */
    public function createArticle(array $locations, string $city, string $country)
    {
        $locations = array_column($locations, 'name');
        $locations = implode(', ',$locations);
        $response = $this->client->request('POST', '', [
            'json' => [
                'model' => $this->chatGptModel,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => "give me the top 3 activities for the location inside topActivities, one url to an image from the location inside imageUrl and the description for each of this locations:{$locations} from {$city}, {$country} on a json like always inside articles, with each one of them having between 120 and 400 words description, imageUrl, topActivities, touristicPointName"
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

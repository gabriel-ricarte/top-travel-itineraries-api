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
     * Retrieve all languages supported by ChatGPT
     * 
     * @return object
     */
    public function getAllSupportedLanguages(): object
    {
        $response = $this->client->request('POST', '', [
            'json' => [
                'model' => $this->chatGptModel,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => "Me dê a lista dos 16 idiomas mais comuns suportados pelo ChatGPT em um objeto JSON com os seguintes atributos: 'language' e 'iso3Code', sempre dentro da propriedade 'languages',e no atributo language escreva o nome do idioma na lingua do idioma"
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
     * Retrieve all countries by language from ChatGPT
     * @var string $language
     * 
     * @return object
     */
    public function getAllCountriesByLanguage(string $language): object
    {
        $response = $this->client->request('POST', '', [
            'json' => [
                'model' => $this->chatGptModel,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => "dê-me a lista de todos os países da américa latina como um formato json com o objeto dos seguintes atributos name e iso3Code sempre dentro dos countries e no atributo name escreva o nome do pais na lingua {$language}
                        tome isso como exemplo:
                        {
                          countries: [
                            {
                              name: Argentina,
                              iso3Code: ARG
                            },
                            {
                              name: Brasil,
                              iso3Code: BRA
                            },
                            {
                              name: Chile,
                              iso3Code: CHL
                            }
                          ]
                        }"
                    ]
                ],
            ],
        ]);
    
        $responseBody = $response->getBody()->getContents();
        $responseObj = json_decode( $responseBody);
        $responseFromAssistant = $this->retrieveCompletion($responseObj);
     
   

        return $responseFromAssistant;
    }

    public function getAllCitiesByLanguage(string $country,string $language): object
    {
        $response = $this->client->request('POST', '', [
            'json' => [
                'model' => $this->chatGptModel,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => "dê-me a lista das top 10 cidades de {$country} como um formato json com o objeto dos seguintes atributos name e iso3Code sempre dentro dos cities e no atributo name escreva o nome da cidade na lingua {$language}
                        "
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

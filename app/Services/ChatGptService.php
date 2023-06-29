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
    public function getAllCitiesByCountry(string $country): array
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
        $responseFromAssistant = collect($responseFromAssistant)->pluck('content')->toArray();

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
                        'content' => "give me the top 5 touristic points from {$city} in {$country} as a json format sorted ascendent alphabetically always inside touristic_points with name, snake_case_name, description, espacial_description, location object with long and lat, categories"
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
    public function createArticle(string $locations, string $city, string $country)
    {
       $response = $this->client->request('POST', '', [
            'json' => [
                'model' => $this->chatGptModel,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' =>"talk about the touristc poitn {$locations},{$city} in {$country} as a json format with name,snake_case_name(must be based in {$locations}),location{city,country},description(5 paragrahps),activities,hours,admission"
                    ]
                ],
            ],
        ]);
    
        $responseBody = $response->getBody()->getContents();
        $responseObj = json_decode( $responseBody);
        $responseFromAssistant = $this->retrieveCompletion($responseObj);
        if(gettype($responseFromAssistant)=='string'){
            $responseFromAssistant = $this->createArticle($locations,$city,$country);
        }

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
                        'content' => "qual as linguas suportadas pelo chat gpt e seus respectivos codigos e de suas variantes como pt-br e en-us em um objeto JSON com os seguintes atributos: 'language' e 'iso3Code' e 'code', sempre dentro da propriedade 'languages'.(no minimo 20 linguas e pelo menos 10 variantes )"                
                    ]
                ],
            ],
        ]);
    
        $responseBody = $response->getBody()->getContents();
        $responseObj = json_decode( $responseBody);
        $responseFromAssistant = $this->retrieveCompletion($responseObj);

        return $responseFromAssistant;
    }

    public function getText($language): object
    {
        $response = $this->client->request('POST', '', [
            'json' => [
                'model' => $this->chatGptModel,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => "Nosso site de roteiros de viagem é a ferramenta perfeita para ajudá-<br/>lo a planejar
                        sua próxima aventura. Com diversas funcionalidades<br/> disponíveis, como criar uma
                        conta, salvar roteiros personalizados e<br/> visualizá-los em um calendário, você terá 
                        tudo o que precisa para <br/>organizar sua viagem de maneira eficiente e prática. Além 
                        disso,<br/> nosso site oferece recomendações personalizadas para tornar sua<br/> viagem 
                        ainda mais especial.<br/>
                        <br/>
                        Não perca mais tempo navegando por diferentes sites e aplicativos. <br/>Com nosso 
                        site, você terá todas as informações que precisa em um <br/>só lugar. Comece criando 
                        sua conta e aproveite todos os recursos <br/>disponíveis para criar o roteiro perfeito 
                        para a sua viagem dos <br/>sonhos. Não espere mais, comece a planejar sua próxima 
                        aventura<br/> agora mesmo!<br/>
,Countries
,Cities
,Crie um roteiro de viagem
Home,
About Us,
Destination,
Tour,
pegue os textos acima e faça a trazução para {$language} sem altera a tag <br/>
coloque em um objeto JSON nos seguintes atributos respctivamente
banner,country,city,travel,home,about,destination,tour"
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
                        'content' => "dê-me a lista das top 10 cidades de {$country} (nao repita nomes de cidades)como um formato json com o objeto dos seguintes atributos name dentro de cities e no atributo escrava o name na lingua {$language}
                        "
                    ]
                ],
            ],
        ]);
    
        $responseBody = $response->getBody()->getContents();
        $responseObj = json_decode( $responseBody);
        $responseFromAssistant = $this->retrieveCompletion($responseObj);
        if(gettype($responseFromAssistant)=='string'){
            $responseFromAssistant = $this->getAllCitiesByLanguage( $country, $language);
        }
     
   

        return $responseFromAssistant;
    }
    public function getAllCitiesFromAllCountriesByLanguage(string $language): object
    {
        $response = $this->client->request('POST', '', [
            'json' => [
                'model' => $this->chatGptModel,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => "dê-me a lista das top 10 cidades de cada um dos seguintes paisesvenezuela,uruguay,trinidad and tobago,suriname,puerto rico,peru,paraguay,panama,nicaragua,mexico,jamaica,honduras,haiti,argentina,bolivia,brazil,chile,colombia,guatemalael salvador,ecuador,dominican republic,cuba,costa rica como um formato json com o objeto dos seguintes atributos name e iso3Code sempre dentro dos cities e no atributo name escreva o nome da cidade na lingua english"
                    ]
                ],
            ],
        ]);
    
        $responseBody = $response->getBody()->getContents();
        $responseObj = json_decode( $responseBody);
        $responseFromAssistant = $this->retrieveCompletion($responseObj);
        dd($responseFromAssistant);
        if(gettype($responseFromAssistant)=='string'){
            $responseFromAssistant = $this->getAllCitiesFromAllCountriesByLanguage($language);
        }
        
     
   

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

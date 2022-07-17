<?php

namespace Provider;

abstract class Provider
{
    private $client_id;
    private $client_secret;
    private $provider_uri;
    private $redirect_uri;
    private $token_uri;
    private $user_uri;
    private $scope;
    private $state;
    private $token;
    private $userInfos;
    private $http_method;

    public function __construct($client_id,$client_secret,$provider_uri,$redirect_uri,$token_uri,$user_uri,array $scope,$http_method='GET') 
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->provider_uri = $provider_uri;
        $this->redirect_uri = $redirect_uri;
        $this->token_uri = $token_uri;
        $this->user_uri = $user_uri;
        $this->scope = $scope;
        $this->state = bin2hex(random_bytes(16));
        $this->http_method = 'POST';
    }

    public function GetAuthorizationUrl()
    {
        $queryParams= http_build_query([
            'client_id' => $this->client_id ,
            'redirect_uri' => $this->redirect_uri,
            'response_type' => 'code',
            'scope' => implode(",",$this->scope),
            'state' => $this->state,
        ]);

        return $this->provider_uri . '?' . $queryParams;
    }

    public function GetToken()
    {
        ["code" => $code, "state" => $state] = $_GET;

        $specifParams = [
            'code' => $code,
            'grant_type' => 'authorization_code',
        ];

        $queryParams = http_build_query(array_merge([
            'client_id' =>$this->client_id,
            'client_secret' =>$this->client_secret,
            'redirect_uri' => $this->redirect_uri,
        ], $specifParams));
        $context = $this->AccessToken($queryParams);

        $response = file_get_contents($this->token_uri, false, $context);
        $token = json_decode($response, true);

        return $token;
    }

    public function GetUser($token)
    {
        $response = file_get_contents($this->user_uri, false, $this->AccessUser($token));
        
        $user = json_decode($response, true);

        return $user;
    }

    public function AccessToken($queryParams)
    {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => [
                    'Content-Type: application/x-www-form-urlencoded'
                ],
                'content' => $queryParams
            ],
        ]);

        return $context;
    }

    Public function AccessUser($token)  
    {
        return stream_context_create([
            'http' => [
                'header' => [
                    "Authorization: Bearer {$token['access_token']}"
                ]
            ]
        ]);
    }
}
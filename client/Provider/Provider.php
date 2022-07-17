<?php

namespace Provider;

abstract class Provider
{
    private $client_id;
    private $client_secret;
    private $provider_uri;
    private $redirect_uri; //= 'http://localhost:8081/callback';
    private $token_uri;
    private $user_uri;
    private $scope;
    private $state;
    // protected $responseType = 'code';
    // protected $oAuthUri;
    // protected $accessTokenUri;
    // protected $token;
    // protected $userInfoUri;
    // protected $userInfos;
    // protected $retriveTokenMethod = 'GET';

    public function __construct($client_id,$client_secret,$provider_uri,$redirect_uri,$token_uri,$user_uri,array $scope=[]) 
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->provider_uri = $provider_uri;
        $this->redirect_uri = $redirect_uri;
        $this->token_uri = $token_uri;
        $this->user_uri = $user_uri;
        $this->scope = $scope;
        $this->state = bin2hex(random_bytes(16)); // "gh_" . 
        // $this->retriveTokenMethod = $retriveTokenMethod;
    }

    Public function GetAuthorizationUrl()
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

    Public function GetToken()
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

        $response = file_get_contents($this->token_uri . '?' . $queryParams);
        $token = json_decode($response, true);

        return $token;
    }

    Public function GetUser($token)  
    {
        $context = stream_context_create([
            'http' => [
                'header' => "Authorization: Bearer {$token['access_token']}"
                ]
            ]);

        $response = file_get_contents($this->user_uri, false, $context);
        $user = json_decode($response, true);

        return($user);
    }

  // protected function generateAccessTokenQueryParams(): string
  // {
  //   $queryParams = http_build_query([
  //     'client_id' => $this->clientId,
  //     'client_secret' => $this->clientSecret,
  //     'code' => $this->code,
  //     'redirect_uri' => $this->redirectUri,
  //     'grant_type' => 'authorization_code',
  //   ]);
  //   return $queryParams;
  // }

  // public function getAccessTokenUri(): string
  // {
  //   return $this->accessTokenUri . '?' . $this->generateAccessTokenQueryParams();
  // }

  // public function setCode(string $code)
  // {
  //   $this->code = $code;
  // }

  // protected function retriveTokenContext()
  // {
  //   $context = stream_context_create([
  //     'http' => [
  //       'method' => $this->retriveTokenMethod,
  //       'header' => [
  //         'Content-Type: application/x-www-form-urlencoded',
  //         'Content-Length: ' . strlen($this->generateAccessTokenQueryParams()),
  //         'Accept: application/json'
  //       ],
  //       'content' => $this->generateAccessTokenQueryParams()
  //     ],
  //   ]);
  //   return $context;
  // }

  // public function retriveToken()
  // {
  //   $response = file_get_contents($this->getAccessTokenUri(), false, $this->retriveTokenContext());
  //   $this->setToken(json_decode($response, true)['access_token']);
  // }

  // protected function setToken(string $token)
  // {
  //   $this->token = $token;
  // }

  // protected function getToken()
  // {
  //   return $this->token;
  // }

  // public function setUserInfos($userInfos)
  // {
  //   $this->userInfos = $userInfos;
  // }

  // public function getUserInfosContext()
  // {
  //   return stream_context_create([
  //     'http' => [
  //       'header' => [
  //         "Authorization: Bearer " . $this->getToken() . "",
  //         "Client-ID: " . $this->clientId . "",
  //         'User-Agent: PHP'
  //       ],
  //     ]
  //   ]);
  // }

  // public function getUserInfosUri(): string
  // {
  //   return $this->userInfoUri;
  // }

  // public function __toString()
  // {
  //   return json_encode($this->userInfos);
  // }
}
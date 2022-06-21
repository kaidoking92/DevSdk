<?php

namespace Provider;


class ProviderFb {
    
    private $client_id;
    private $redirect_uri;
    private $scope;
    private $client_secret;


    Public function __construct($client_id, $redirect_uri, $client_secret,  array $scope=[])
    {
        $this->client_id = $client_id;
        $this->redirect_uri = $redirect_uri;
        $this->scope = $scope;
        $this->client_secret = $client_secret;
    }

    Public function GetUser($token)  
    {
        $context = stream_context_create([
            'http' => [
                'header' => "Authorization: Bearer {$token['access_token']}"
                ]
            ]);
        $response = file_get_contents("https://graph.facebook.com/v2.10/me", false, $context);
        $user = json_decode($response, true);
        return($user);
    }


    Public function GetAutorisationUrl()
    {
        $queryParams= http_build_query([
            'client_id' => $this->client_id ,
            'redirect_uri' => $this->redirect_uri  ,
            'response_type' => 'code',
            'scope' => implode(",",["public_profile","email"]+$this->scope),
            "state" => bin2hex(random_bytes(16))
        ]);
        return "https://www.facebook.com/v2.10/dialog/oauth?{$queryParams}";
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
    $response = file_get_contents("https://graph.facebook.com/v2.10/oauth/access_token?{$queryParams}");
    $token = json_decode($response, true);
    return $token;
    }
    
    
    }

?>
<?php

namespace Provider;

/**
 * 
 */
class ProviderLocal
{
	private $client_id;
	private $client_secret;
	private $redirect_uri;
	private $scope;

	
	public function __construct($client_id, $client_secret, $redirect_uri, array $scope=[])
	{
		$this->client_id = $client_id;
		$this->client_secret = $client_secret;
		$this->redirect_uri = $redirect_uri;
		$this->scope = $scope;
	}

	public function getAuthorizationUrl()
	{
		$queryParams= http_build_query([
	        'client_id' => $this->client_id,
	        'redirect_uri' => $this->redirect_uri,
	        'response_type' => 'code',
	        'scope' => implode(",", ['basic']+$this->scope),
	        "state" => bin2hex(random_bytes(16))
	    ]);

		return "http://localhost:8080/auth?{$queryParams}";
	}

	public function getToken()
	{
		["code" => $code, "state" => $state] = $_GET;

	    $specifParams = [
	        'code' => $code,
	        'grant_type' => 'authorization_code',
	    ];

	    $queryParams = http_build_query(array_merge([
	        'client_id' => $this->client_id,
	        'client_secret' => $this->client_secret,
	        'redirect_uri' => $this->redirect_uri,
	    ], $specifParams));
	    $response = file_get_contents("http://server:8080/token?{$queryParams}");
	    $token = json_decode($response, true);

	    return $token;
	}

	public function getUser($token)
	{
		$context = stream_context_create([
	        'http' => [
	            'header' => "Authorization: Bearer {$token['access_token']}"
	            ]
	        ]);
	    $response = file_get_contents("http://server:8080/me", false, $context);
	    $user = json_decode($response, true); 

	    return $user;
	}
}
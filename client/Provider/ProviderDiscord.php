<?php

namespace Provider;

use Provider\Provider;

class ProviderDiscord extends Provider
{
	private $client_id;
    private $client_secret;
    private $provider_uri;
    private $redirect_uri; //= 'http://localhost:8081/callback';
    private $token_uri;
    private $user_uri;
    private $scope;
    private $state;

	
	public function __construct($client_id,$client_secret,$provider_uri,$redirect_uri,$token_uri,$user_uri,array $scope,$http_method='GET') 
    {
		parent::__construct($client_id,$client_secret,$provider_uri,$redirect_uri,$token_uri,$user_uri,$scope,$http_method);
	}
}
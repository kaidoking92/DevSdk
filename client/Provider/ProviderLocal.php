<?php

namespace Provider;

require "./Provider/Provider.php";
use Provider\Provider;

/**
 * 
 */
class ProviderLocal extends Provider
{
	private $client_id;
    private $client_secret;
    private $provider_uri;
    private $redirect_uri; //= 'http://localhost:8081/callback';
    private $token_uri;
    private $user_uri;
    private $scope;
    private $state;

	
	public function __construct($client_id,$client_secret,$provider_uri,$redirect_uri,$token_uri,$user_uri,array $scope=[]) 
    {
		parent::__construct($client_id,$client_secret,$provider_uri,$redirect_uri,$token_uri,$user_uri,$scope);
	}
}
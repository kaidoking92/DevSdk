<?php

namespace Provider;

use Provider\Provider;

class ProviderDiscord extends Provider
{
  public function __construct(
    string $clientId,
    string $oAuthUri,
    string $scope,
    string $clientSecret,
    string $accessTokenUri,
    string $userInfoUri,
    string $retriveTokenMethod = 'GET'
  ) {
    $this->clientId = $clientId;
    $this->scope = $scope;
    $this->state = bin2hex(random_bytes(16));
    $this->oAuthUri = $oAuthUri;
    $this->clientSecret = $clientSecret;
    $this->accessTokenUri = $accessTokenUri;
    $this->userInfoUri = $userInfoUri;
    $this->retriveTokenMethod = $retriveTokenMethod;
  }
};
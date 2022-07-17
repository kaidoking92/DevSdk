<?php

namespace client;


require "./Provider/ProviderLocal.php";
use Provider\ProviderLocal;
require "./Provider/ProviderFb.php";
use Provider\ProviderFb;

define('OAUTH_CLIENT_ID', '');
define('OAUTH_CLIENT_SECRET', '');
define('FACEBOOK_CLIENT_ID', '');
define('FACEBOOK_CLIENT_SECRET', '');

function login()
{
    $provider = new ProviderLocal(
        $client_id=OAUTH_CLIENT_ID,
        $client_secret=OAUTH_CLIENT_SECRET,
        $provider_uri='http://localhost:8080/auth',
        $redirect_uri='http://localhost:8081/callback',
        $token_uri='http://server:8080/token',
        $user_uri='http://server:8080/me',
        $scope=['basic']
    );
    
    echo '<a href="' . $provider->getAuthorizationUrl() . '">click</a>';

    $providerFB = new ProviderFb(
        $client_id=FACEBOOK_CLIENT_ID,
        $client_secret=FACEBOOK_CLIENT_SECRET,
        $provider_uri='https://www.facebook.com/v2.10/dialog/oauth',
        $redirect_uri='http://localhost:8081/fbcallback',
        $token_uri='https://graph.facebook.com/v2.10/oauth/access_token',
        $user_uri='https://graph.facebook.com/v2.10/me',
        $scope=["public_profile","email"]
    );
    
    echo '<a href="' . $providerFB->getAuthorizationUrl() . '">click</a>';

}

function callback()
{
    $provider = new ProviderLocal(
        $client_id=OAUTH_CLIENT_ID,
        $client_secret=OAUTH_CLIENT_SECRET,
        $provider_uri='http://localhost:8080/auth',
        $redirect_uri='http://localhost:8081/callback',
        $token_uri='http://server:8080/token',
        $user_uri='http://server:8080/me',
        $scope=['basic']
    );
    var_dump($provider->getUser($provider->getToken()));
}

function fbcallback()
{
    $provider = new ProviderFb(
        $client_id=FACEBOOK_CLIENT_ID,
        $client_secret=FACEBOOK_CLIENT_SECRET,
        $provider_uri='https://www.facebook.com/v2.10/dialog/oauth',
        $redirect_uri='http://localhost:8081/fbcallback',
        $token_uri='https://graph.facebook.com/v2.10/oauth/access_token',
        $user_uri='https://graph.facebook.com/v2.10/me',
        $scope=["public_profile","email"]
    );
    var_dump($provider->getUser($provider->getToken()));
}

$route = $_SERVER["REQUEST_URI"];
switch (strtok($route, "?")) {
    case '/login':
        login();
        break;
    case '/callback':
        callback();
        break;
    case '/fb_callback':
        fbcallback();
        break;
    default:
        http_response_code(404);
        break;
}

<?php

namespace client;


require "./Provider/ProviderLocal.php";
use Provider\ProviderLocal;
require "./Provider/ProviderFb.php";
use Provider\ProviderFb;
require "./Provider/ProviderDiscord.php";
use Provider\ProviderDiscord;

define('OAUTH_CLIENT_ID', '');
define('OAUTH_CLIENT_SECRET', '');
define('FACEBOOK_CLIENT_ID', '');
define('FACEBOOK_CLIENT_SECRET', '');
define('DISCORD_CLIENT_ID', '');
define('DISCORD_CLIENT_SECRET', '');

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
    
    echo '<a href="' . $provider->getAuthorizationUrl() . '">click</a><br>';

    $providerFB = new ProviderFb(
        $client_id=FACEBOOK_CLIENT_ID,
        $client_secret=FACEBOOK_CLIENT_SECRET,
        $provider_uri='https://www.facebook.com/v2.10/dialog/oauth',
        $redirect_uri='http://localhost:8081/fb_callback',
        $token_uri='https://graph.facebook.com/v2.10/oauth/access_token',
        $user_uri='https://graph.facebook.com/v2.10/me',
        $scope=["public_profile","email"]
    );
    
    echo '<a href="' . $providerFB->getAuthorizationUrl() . '">click</a><br>';

    $providerDis = new ProviderDiscord(
        $client_id=DISCORD_CLIENT_ID,
        $client_secret=DISCORD_CLIENT_SECRET,
        $provider_uri='http://discord.com/api/oauth2/authorize',
        $redirect_uri='http://localhost:8081/dis_callback',
        $token_uri='https://discord.com/api/oauth2/token',
        $user_uri='https://discord.com/api/users/@me',
        $scope=["identify"],
        $http_method='POST'
    );
    
    echo '<a href="' . $providerDis->getAuthorizationUrl() . '">click</a><br>';
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
    $user = $provider->GetUser($provider->GetToken());
    echo "Hello, " . $user['firstname'] . " " . $user['lastname'];
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
    $user = $provider->GetUser($provider->GetToken());
    echo "Hello, " . $user['firstname'] . " " . $user['lastname'];
}

function discallback()
{ 
    $provider = new ProviderDiscord(
        $client_id=DISCORD_CLIENT_ID,
        $client_secret=DISCORD_CLIENT_SECRET,
        $provider_uri='http://discord.com/api/oauth2/authorize',
        $redirect_uri='http://localhost:8081/dis_callback',
        $token_uri='https://discord.com/api/oauth2/token',
        $user_uri='https://discord.com/api/users/@me',
        $scope=["identify"],
        $http_method='POST'
    );
    $user = $provider->GetUser($provider->GetToken());
    echo "Hello, " . $user['username'];
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
    case '/dis_callback':
        discallback();
        break;
    default:
        http_response_code(404);
        break;
}

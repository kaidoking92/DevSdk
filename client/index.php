<?php

namespace client;

require "./Provider/ProviderLocal.php";
require "./Provider/ProviderTwitter.php";

use Provider\ProviderLocal;
use Provider\ProviderTwitter;

define('OAUTH_CLIENT_ID', '621f59c71bc35');
define('OAUTH_CLIENT_SECRET', '621f59c71bc36');
define('FACEBOOK_CLIENT_ID', '');
define('FACEBOOK_CLIENT_SECRET', '');
define('TWITTER_CLIENT_ID', '');
define('TWITTER_CLIENT_SECRET', '');


function login()
{
    $provider = new ProviderTwitter('TWITTER_CLIENT_ID', 'TWITTER_CLIENT_SECRET', 'http://localhost:8081/tw_callback');

    echo '<a href="' . $provider->getAuthorizationUrl() . '">click</a>';
}

// Exchange code for token then get user info
function callback()
{
    $provider = new ProviderLocal('621f59c71bc35', '621f59c71bc36', 'http://localhost:8081/callback');
    var_dump($provider->getUser($provider->getToken()));
}

function twcallback()
{
    $provider = new ProviderTwitter('TWITTER_CLIENT_ID', 'TWITTER_CLIENT_SECRET', 'http://localhost:8081/tw_callback');
    var_dump($provider->getUser($provider->getToken()));
}

function fbcallback()
{
    ["code" => $code, "state" => $state] = $_GET;

    $specifParams = [
            'code' => $code,
            'grant_type' => 'authorization_code',
        ];

    $queryParams = http_build_query(array_merge([
        'client_id' => FACEBOOK_CLIENT_ID,
        'client_secret' => FACEBOOK_CLIENT_SECRET,
        'redirect_uri' => 'http://localhost:8081/fb_callback',
    ], $specifParams));
    $response = file_get_contents("https://graph.facebook.com/v2.10/oauth/access_token?{$queryParams}");
    $token = json_decode($response, true);
    
    $context = stream_context_create([
        'http' => [
            'header' => "Authorization: Bearer {$token['access_token']}"
            ]
        ]);
    $response = file_get_contents("https://graph.facebook.com/v2.10/me", false, $context);
    $user = json_decode($response, true);
    echo "Hello {$user['name']}";
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
    case 'tw_callback':
        twcallback();
        break;
    default:
        http_response_code(404);
        break;
}

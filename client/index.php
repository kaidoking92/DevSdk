<?php

define('OAUTH_CLIENT_ID', '621f59c71bc35');
define('OAUTH_CLIENT_SECRET', '621f59c71bc36');
define('FACEBOOK_CLIENT_ID', '');
define('FACEBOOK_CLIENT_SECRET', '');
<<<<<<< HEAD


require ('./Provider/ProviderFb.php');
use Provider\ProviderFb;

=======
>>>>>>> master

function login()
{
    $provider = new ProviderFb('', 'http://localhost:8081/fb_callback','');
    echo '<a href="' . $provider->GetAutorisationUrl() . '">click</a>';
}

// Exchange code for token then get user info
function callback()
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        ["username" => $username, "password" => $password] = $_POST;
        $specifParams = [
            'username' => $username,
            'password' => $password,
            'grant_type' => 'password',
        ];
    } else {
        ["code" => $code, "state" => $state] = $_GET;

        $specifParams = [
            'code' => $code,
            'grant_type' => 'authorization_code',
        ];
    }

    $queryParams = http_build_query(array_merge([
        'client_id' => OAUTH_CLIENT_ID,
        'client_secret' => OAUTH_CLIENT_SECRET,
        'redirect_uri' => 'http://localhost:8081/callback',
    ], $specifParams));
    $response = file_get_contents("http://server:8080/token?{$queryParams}");
    $token = json_decode($response, true);
    
    $context = stream_context_create([
        'http' => [
            'header' => "Authorization: Bearer {$token['access_token']}"
            ]
        ]);
    $response = file_get_contents("http://server:8080/me", false, $context);
    $user = json_decode($response, true);
    echo "Hello {$user['lastname']} {$user['firstname']}";
}

function fbcallback()
{
    $provider = new ProviderFb('', 'http://localhost:8081/fb_callback','');
    $user = $provider->GetUser($provider->GetToken());
    var_dump($user);
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

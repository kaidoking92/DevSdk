<?php

namespace client;

require "./Provider/ProviderLocal.php";

use Provider\ProviderLocal;

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
<<<<<<< HEAD
    $provider = new ProviderFb('', 'http://localhost:8081/fb_callback','');
    echo '<a href="' . $provider->GetAutorisationUrl() . '">click</a>';
=======
    $provider = new ProviderLocal('621f59c71bc35', '621f59c71bc36', 'http://localhost:8081/callback');

    echo '<a href="' . $provider->getAuthorizationUrl() . '">click</a>';
>>>>>>> feature/ProviderLocal
}

// Exchange code for token then get user info
function callback()
{
    $provider = new ProviderLocal('621f59c71bc35', '621f59c71bc36', 'http://localhost:8081/callback');
    var_dump($provider->getUser($provider->getToken()));
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

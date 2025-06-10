<?php


session_start();

define('BASE_PATH', __DIR__);
define('CURRENT_DOMAIN', currentDomain());
define('DISPLAY_ERROR', true);

define('DB_HOST', 'localhost');
define('DB_USERNAME', 'ROOT');
define('DB_PASSWORD', '');
define('DB_NAME', 'stackoverflow');



//mail


function asset($src){
    $domain = trim(CURRENT_DOMAIN, '/ ');
    $src = $domain . '/' . trim($src, '/ ');
    return $src;
}


function url($url){
    $domain = trim(CURRENT_DOMAIN, '/ ');
    $url = $domain . '/' . trim(url, '/ ');
    return $url;
}

function protocol()
{
    return stripos($_SERVER['SERVER_PROTOCOL'], 'https') === 0 ? 'https://' : 'http://';
}

function currentDomain()
{
    return protocol() . $_SERVER['HTTP_HOST'];
}

function currentUrl()
{
    return currentDomain() . $_SERVER['REQUEST_URI'];
}

function methodField()
{
    return $_SERVER['REQUEST_METHOD'];
}

function displayError($status)
{
    if ($status){
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
    else{
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);
        error_reporting(0);
    }
}
displayError(DISPLAY_ERROR);


function dd($vars)
{
    echo '<pre/>';
    var_dump($vars);
    exit;
}
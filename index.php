<?php


use Database\CreateDB;
use Miladr\Jalali\jDate;
use Database\Database;

session_start();

define('BASE_PATH', __DIR__);
define('CURRENT_DOMAIN', currentDomain());
define('DISPLAY_ERROR', true);

define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '1234');
define('DB_NAME', 'stackoverflow');


require_once __DIR__ . '/activities/Admin/Category.php';
require_once __DIR__ . '/database/Database.php';
require_once __DIR__ . '/database/CreateDB.php';
//mail


$db = new CreateDB();
$db->run();

function uri($reservedUrl, $class, $method, $requestMethod = 'GET')
{
    // current URL
    $currentUrl = explode('?', currentUrl())[0];
    $currentUrl = str_replace(CURRENT_DOMAIN, "", $currentUrl);
    $currentUrl = trim($currentUrl, '/ ');
    $currentUrlArray = explode('/', $currentUrl);
    $currentUrlArray = array_filter($currentUrlArray);

    //reserved Url
    $reservedUrl = trim($reservedUrl, '/ ');
    $reservedUrlArray = explode('/', $reservedUrl);
    $reservedUrlArray = array_filter($reservedUrlArray);



    if (sizeof($currentUrlArray) != sizeof($reservedUrlArray) || methodField() != $requestMethod) {
        return false;
    }

    $parameters = [];
    for ($key = 0; $key < sizeof($currentUrlArray); $key++) {
        if ($reservedUrlArray[$key][0] == "{" && $reservedUrlArray[$key][strlen($reservedUrlArray[$key]) - 1] == "}") {
            array_push($parameters, $currentUrlArray[$key]);
        } elseif ($currentUrlArray[$key] !== $reservedUrlArray[$key]) {
            return false;
        }
    }



    if (methodField() == 'POST') {
        $request = isset($_FILES) ? array_merge($_POST, $_FILES) : $_POST;
        $parameters = array_merge([$request], $parameters);
    }


    $object = new $class;
    call_user_func_array([$object, $method], $parameters);
    exit();
}



spl_autoload_register(function ($className) {
    $path = BASE_PATH . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR;
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    include $path . $className . '.php';
});

function asset($src)
{
    $domain = trim(CURRENT_DOMAIN, '/ ');
    $src = $domain . '/' . trim($src, '/ ');
    return $src;
}


function url($url)
{
    $domain = trim(CURRENT_DOMAIN, '/ ');
    $url = $domain . '/' . trim($url, '/ ');
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
    if ($status) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    } else {
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


global $flashmessage;
if (isset($_SESSION['flashmessage'])) {
    $flashmessage = $_SESSION['flashmessage'];
    unset($_SESSION['flashmessage']);
}
function flash($name, $value = null)
{
    if ($value == null) {
        global $flashmessage;
        $message = isset($flashmessage[$name]) ? $flashmessage[$name] : "";
        return $message;
    } else {
        $_SESSION['flashmessage'][$name] = $value;
    }
}


//flash('cart_success', 'added to cart');
//echo flash('cart_success');


function jalali($date)
{
    $date = jDate::forge($date)->format('%B %d، %Y');
    return $date;
}




uri('admin/category', 'Activities\Admin\category', 'index');
uri('admin/category/create', 'Activities\Admin\category', 'create');
uri('admin/category/edit/{id}', 'Activities\Admin\category', 'edit');

echo '404';
exit();


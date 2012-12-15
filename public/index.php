<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));

$url = null; // set URL to null by default.

if(isset($_GET['url'])) // if we have a URL in our $_GET array
{
    $url = $_GET['url']; // get our URL (rewritten by .htaccess (or equivalent) re-writes)
    unset($_GET['url']); // unset the URL from $_GET array 
}


// load bootstrap
require_once(ROOT . DS . 'system' . DS . 'bootstrap.php');
<?php

/** Check if environment is development and display errors **/

function set_error_reporting()
{
    if (DEVELOPMENT_ENVIRONMENT == true)
    {
        error_reporting(E_ALL);
        ini_set('display_errors','On');
        ini_set('log_errors', 'On');
    }
    else
    {
        error_reporting(E_ALL);
        ini_set('display_errors','Off');
        ini_set('log_errors', 'On');
    }
}

/** Check for Magic Quotes and remove them **/

function strip_slashes_deep($value)
{
    $value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
    return $value;
}

function remove_magic_quotes()
{
    if (get_magic_quotes_gpc())
    {
        $_GET    = strip_slashes_deep($_GET);
        $_POST   = strip_slashes_deep($_POST);
        $_COOKIE = strip_slashes_deep($_COOKIE);
    }
}

/** Check register globals and remove them **/

function unregister_globals()
{
    if (ini_get('register_globals'))
    {
        $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
        foreach ($array as $value)
        {
            foreach ($GLOBALS[$value] as $key => $var)
            {
                if ($var === $GLOBALS[$key])
                {
                    unset($GLOBALS[$key]);
                }
            }
        }
    }
}

/** Secondary Call Function **/

function perform_action($controller,$action,$queryString = null,$render = 0)
{
    $controllerName = ucfirst($controller).'Controller';
    $dispatch = new $controllerName($controller,$action);
    $dispatch->render = $render;
    return call_user_func_array(array($dispatch,$action),$queryString);
}

/** Routing **/
// will probably change this to routing class eventually
function route_url($url, $routing)
{
    foreach ($routing as $pattern => $result)
    {
            if (preg_match($pattern, $url))
            {
                return preg_replace($pattern, $result, $url);
            }
    }
    return ($url);
}

/** Main Call Function **/

function run_calls(&$url, $default, $autoload, &$extra_query_string, $routing)
{
    $query_string = array();
    $extra_query_string = array();
    $original_url = array();
    
    if(isset($_GET) && count($_GET) > 0)
    {
        foreach($_GET as $key => $value)
        {
            $extra_query_string[$key] = $value;
        }
    }
    
    if (!isset($url)) // homepage / default
    {
        $controller = $default['controller'];
        $action = $default['action'];
        
        $url_class_name = SYSTEM_PREPEND . 'Url';
        $url_class = new $url_class_name(array(), $original_url, $extra_query_string);
        
    }
    else
    {
        $original_url = $url;
        $url = route_url($url, $routing);
        $url_array = array();
        $url_array = explode("/",$url);
        
        $url_class_name = SYSTEM_PREPEND . 'Url';
        $url_class = new $url_class_name($url_array, $original_url, $extra_query_string);
        
        $controller = $url_array[0];
        
        array_shift($url_array);
        if (isset($url_array[0]) && trim($url_array[0]) != '')
        {
            $action = $url_array[0];
            array_shift($url_array);
        }
        else
        {
            $action = 'index'; // Default Action
        }
        $query_string = $url_array; // whatever's left in here
    }
    
    // load our helpers
    foreach($autoload['helpers'] as $key => $helper)
    {
        load_helper($helper);
    }
    
    $controller_name = ucfirst($controller).'Controller';
    
    // if class or method in that class doesn't exist - 404!
    if(!class_exists($controller_name) || !(int)method_exists($controller_name, $action))
    {
        $controller = 'fourohfour';
        $controller_name = ucfirst($controller).'Controller';
        $action = 'index';
    }
    
    $dispatch = new $controller_name($controller, $action, $autoload, $url_class);
    
    if ((int)method_exists($controller_name, $action))
    {
        call_user_func_array(array($dispatch, "before_action"), $query_string);
        call_user_func_array(array($dispatch, $action), $query_string);
        call_user_func_array(array($dispatch, "after_action"), $query_string);
    }
    else
    {
        // error
        exit("ohnoes");
    }
}


/** Autoload any classes that are required **/

function __autoload($className)
{
    if (file_exists(ROOT . DS . 'library' . DS . 'system' . DS . strtolower($className) . '.class.php'))
    {
        require_once(ROOT . DS . 'library' . DS . 'system' . DS . strtolower($className) . '.class.php');
    }
    elseif (file_exists(ROOT . DS . 'system' . DS . strtolower($className) . '.class.php'))
    {
        require_once(ROOT . DS . 'system' . DS . strtolower($className) . '.class.php');
    }
    elseif (file_exists(ROOT . DS . 'application' . DS . 'controllers' . DS . strtolower($className) . '.php'))
    {
        require_once(ROOT . DS . 'application' . DS . 'controllers' . DS . strtolower($className) . '.php');
    }
    elseif (file_exists(ROOT . DS . 'application' . DS . 'models' . DS . strtolower($className) . '.php'))
    {
        require_once(ROOT . DS . 'application' . DS . 'models' . DS . strtolower($className) . '.php');
    }
    else
    {
        // error stuff in here?
    }
}


function load_helper($helper)
{
    if (file_exists(ROOT . DS . 'helpers' . DS . strtolower($helper) . '.helper.php')) {
        require_once(ROOT . DS . 'helpers' . DS . strtolower($helper) . '.helper.php');
    }
}

function load_library($library)
{
    if (file_exists(ROOT . DS . 'library' . DS . strtolower($library) . '.library.php')) {
        require_once(ROOT . DS . 'library' . DS . strtolower($library) . '.library.php');
    }
}

/** GZip Output **/

function gzip_output()
{
    $ua = $_SERVER['HTTP_USER_AGENT'];

    if (0 !== strpos($ua, 'Mozilla/4.0 (compatible; MSIE ')
        || false !== strpos($ua, 'Opera')) {
        return false;
    }

    $version = (float)substr($ua, 30); 
    return (
        $version < 6
        || ($version == 6  && false === strpos($ua, 'SV1'))
    );
}

function redirect($url)
{
    header('Location: ' . $url);
    exit();
}

function site_url()
{
    if($_SERVER['SERVER_PORT'] != SERVER_SSL_PORT) {
        $url = SITE_URL;
    }
    else {
        $url = SECURE_SITE_URL;
    }
    return $url;
}

// yeah, I use it, so sue me...
function print_array($array, $return = false)
{
    print "<pre>";
    print_r($array, $return);
    print "</pre>";
}
<?php

/** Check if environment is development and display errors **/

function setReporting()
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

function stripSlashesDeep($value)
{
	$value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
	return $value;
}

function removeMagicQuotes()
{
	if ( get_magic_quotes_gpc() ) {
		$_GET    = stripSlashesDeep($_GET   );
		$_POST   = stripSlashesDeep($_POST  );
		$_COOKIE = stripSlashesDeep($_COOKIE);
	}
}

/** Check register globals and remove them **/

function unregisterGlobals()
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

function performAction($controller,$action,$queryString = null,$render = 0)
{
	$controllerName = ucfirst($controller).'Controller';
	$dispatch = new $controllerName($controller,$action);
	$dispatch->render = $render;
	return call_user_func_array(array($dispatch,$action),$queryString);
}

/** Routing **/
// will probably change this to routing class eventually
function routeURL($url, $routing)
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

function callHook(&$url, $default, $autoload, &$extra_query_string, $routing)
{
	$queryString = array();
	$extra_query_string = array();
	
	if(isset($_GET) && count($_GET) > 0)
	{
		foreach($_GET as $key => $value)
		{
			$extra_query_string[$key] = $value;
		}
	}
	
	if (!isset($url))
	{
		$controller = $default['controller'];
		$action = $default['action'];
		
		$url_class_name = SYSTEM_PREPEND . 'Url';
		$url_class = new $url_class_name(array(), $extra_query_string);
		
	}
	else
	{
		$url = routeURL($url, $routing);
		$urlArray = array();
		$urlArray = explode("/",$url);
		
		$url_class_name = SYSTEM_PREPEND . 'Url';
		$url_class = new $url_class_name($urlArray, $extra_query_string);
		
		$controller = $urlArray[0];
		
		array_shift($urlArray);
		if (isset($urlArray[0]) && trim($urlArray[0]) != '') {
			$action = $urlArray[0];
			array_shift($urlArray);
		} else {
			$action = 'index'; // Default Action
		}
		$queryString = $urlArray;
	}
	
	// load our helpers
	foreach($autoload['helpers'] as $key => $helper)
	{
		load_helper($helper);
	}
	
	$controllerName = ucfirst($controller).'Controller';
	$abstract_controller = false;
	
	// if class or method in that class doesn't exist - 404!
	if(!class_exists($controllerName) || !(int)method_exists($controllerName, $action))
	{
		$controller = 'fourohfour';
		$controllerName = ucfirst($controller).'Controller';
		$action = 'index';
	}
	
	$dispatch = new $controllerName($controller, $action, $autoload, $url_class);
	
	if ((int)method_exists($controllerName, $action))
	{
		call_user_func_array(array($dispatch,"before_action"),$queryString);
		call_user_func_array(array($dispatch,$action),$queryString);
		call_user_func_array(array($dispatch,"after_action"),$queryString);
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

function gzipOutput()
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
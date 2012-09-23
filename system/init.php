<?php
require_once (ROOT . DS . 'system' . DS . 'shared.php');

/** Get Required Files **/

gzipOutput() || ob_start("ob_gzhandler");

setReporting(); // set error reporting levels here

$cache = Cache::get_instance();
$inflect = Inflection::get_instance();

$extra_query_string = '';

removeMagicQuotes();
unregisterGlobals();

// let's go!
callHook($url, $default, $autoload, $extra_query_string, $routing);
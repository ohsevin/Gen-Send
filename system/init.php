<?php
require_once (ROOT . DS . 'system' . DS . 'shared_functions.php');

/** Get Required Files **/

gzip_output() || ob_start("ob_gzhandler");

set_error_reporting(); // set error reporting levels here

$cache = Cache::get_instance();
$inflect = Inflection::get_instance();

$extra_query_string = '';

remove_magic_quotes();
unregister_globals();

// let's go!
run_calls($url, $default, $autoload, $extra_query_string, $routing);
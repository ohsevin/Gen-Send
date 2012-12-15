<?php

/** Configuration Variables **/

define('DEVELOPMENT_ENVIRONMENT', false); // for error logging etc
define('DB_DRIVER', 'mysql');
define('DB_NAME', '');
define('DB_USER', '');
define('DB_PASSWORD', '');
define('DB_HOST', 'localhost');

define('BASE_PATH', 'http://your_domain.co.uk'); // no trailing slash!
define('SITE_URL', BASE_PATH . '/');

define('SYSADMIN_EMAIL', 'sysadmin@your_domain.co.uk');

define('SECURE_BASE_PATH', 'https://your_domain.co.uk'); // no trailing slash, typically the same as BASE_PATH but with https:// instead of http://
define('SECURE_SITE_URL', SECURE_BASE_PATH . '/');

define('ALLOW_ROBOTS', true); // set to false to add nofollow meta on the site globally

define('LOG_ERRORS', true);

define('GLOBAL_SSL', false); // do we force global SSL for the whole thing

define('DEFAULT_TIMEZONE', 'Europe/London'); // or something like America/Los_Angeles

define('GEN_SEND_URL', 'https://gensend.com');
define('GEN_SEND_GITHUB_URL', 'https://github.com/Brunty/Gen-Send');

define('CONFIG_HASH', ''); // put long (32char) random keys in here for security stuff.
define('SITE_ENCRYPTION_KEY', ''); // put long (32char) random keys in here for security stuff.

define('SERVER_SSL_PORT', 443); // change if different on your server

define('DEFAULT_EXPIRE_DAYS', 7);
define('DEFAULT_EXPIRE_VIEWS', 3);

/* prepends and schtuff */
define('SYSTEM_PREPEND', 'GNSND_'); // generally don't change this as default classnames have this appended to the start

/* SITE INFO */
define('SITE_NAME', '');

/* nofollow stuff */
define('NOFOLLOW_GEN', true); // generate page
define('NOFOLLOW_SEND', true); // send page
define('NOFOLLOW_ABOUT', false); // about page
define('NOFOLLOW_INFO', true); // info pages for each section 

/* Meta stuff */
define('META_TITLE_SEPARATOR', ' | ');
define('META_TITLE_APPEND', 'at your_domain.co.uk');
define('META_TITLE_DEFAULT', 'at your_domain.co.uk');

define('META_KEYWORDS_APPEND', '');
define('META_KEYWORDS_DEFAULT', '');

define('META_DESCRIPTION_APPEND', '');
define('META_DESCRIPTION_DEFAULT', '');
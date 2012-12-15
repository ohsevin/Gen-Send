<?php

// order is important in this array MOST IMPORTANT GO FIRST!
$routing = array(
    '/v\/(.*)/'                         =>      'securesend/v/\1', // go from /v/$url to /securesend/v/$url
    '/securesend\/v\/(.*)/'             =>      'securesend/v/\1', // go from securesend/v/$url to securesend/v/$url
    '/send\/(.*)/'                		=>      'securesend/\1', // go from send/*wildcard* to securesend/*wildcard*
    '/securesend\/cron_update\/(.*)/'   =>      'securesend/cron_update/\1', // securesend cron
    '/send\/v\/(.*)/'                   =>      'securesend/v/\1', // go from send/v/$url to securesend/v/$url
    '/securesend\/(.*)/'                =>      'securesend/\1', // go from securesend/*wildcard* to securesend/*wildcard*
    '/securesend/'                      =>      'securesend/',
    '/send/'                            =>      'securesend/', // go from send/v/$url to securesend/v/$url
    '/gen\/(.*)/'                       =>     'password/\1', // go from gen/*wildcard* to password/*wildcard*
    '/gen/'                             =>      'password/', // go from securesend/v/$url to securesend/v/$url
);

$default['controller'] = 'siteindex';
$default['action'] = 'index';
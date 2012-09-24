<?php

// order is important in this array
$routing = array(
    '/securesend/'                      =>      'securesend/', // go from securesend/v/$url to securesend/v/$url
    '/send/'                            =>      'securesend/', // go from securesend/v/$url to securesend/v/$url
    '/gen/'                             =>      'password/', // go from securesend/v/$url to securesend/v/$url
    '/securesend\/v\/(.*)/'             =>      'securesend/v/\1', // go from securesend/v/$url to securesend/v/$url
    '/send\/v\/(.*)/'                   =>      'securesend/v/\1', // go from securesend/v/$url to securesend/v/$url
    '/v\/(.*)/'                         =>      'securesend/v/\1' // go from /v/$url to /securesend/v/$url
);

$default['controller'] = 'siteindex';
$default['action'] = 'index';
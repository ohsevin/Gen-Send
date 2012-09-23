<?php

// order is important in this array
$routing = array(
    '/securesend\/v\/(.*)/'            =>      'securesend/v/\1', // go from securesend/v/$url to securesend/v/$url
    '/v\/(.*)/'                        =>      'securesend/v/\1' // go from /v/$url to /securesend/v/$url
);

$default['controller'] = 'siteindex';
$default['action'] = 'index';
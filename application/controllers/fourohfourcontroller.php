<?php

class FourohfourController extends MFYU_VanillaController {
    
    function before_action ()
    {
        $is_ssl = (GLOBAL_SSL) ? GLOBAL_SSL : false;
        $this->set_ssl($is_ssl);
        $this->set('is_ssl', $is_ssl);
        // set 404 header
        header('HTTP/1.0 404 Not Found');
    }
    
    function index() {
        $this->meta['title'] = '404! uh-oh...';
        $this->meta['description'] = '404 error';
        $this->meta['keywords'] = '404';
    }

    function after_action()
    {

    }
}
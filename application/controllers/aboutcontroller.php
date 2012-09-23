<?php

class AboutController extends MFYU_VanillaController {
    
    public function before_action ()
	{
        $is_ssl = (GLOBAL_SSL) ? GLOBAL_SSL : false;
        $this->set_ssl($is_ssl);
		$this->set('is_ssl', $is_ssl);
    }
    
    public function index()
    {
        $this->meta['title'] = 'About ' . SITE_NAME;
        $this->meta['description'] = '';
        $this->meta['keywords'] = '';
    }
    
    public function after_action()
	{

    }
}
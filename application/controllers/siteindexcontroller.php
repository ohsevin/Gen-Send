<?php

class SiteindexController extends MFYU_VanillaController {
	
	function before_action ()
	{
        $is_ssl = (GLOBAL_SSL) ? GLOBAL_SSL : false;
        $this->set_ssl($is_ssl);
        $this->set('is_ssl', $is_ssl);
	}
	
	function index()
	{
		$this->meta['title'] = 'Home';
		$this->meta['description'] = 'Tools created by Matthew Brunt of maffyoo.co.uk - choose from the list below';
		$this->meta['keywords'] = '';
		/*
		$query = 'SELECT * FROM todo';
		
		$results = $this->db->query($query);
		
		foreach($results as $result)
		{
			echo $result['label'] . "<br />";
		}
		*/
	}
	
	function after_action()
	{
        
	}


}
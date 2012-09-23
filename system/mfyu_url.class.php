<?php

class MFYU_Url {
	
	protected $_url_parts;
	protected $_query_string;

	function __construct($url_array, $extra_query_string)
	{
		$this->_url_parts = $url_array;
		$this->_query_string = $extra_query_string;
	}
	
	public function segment($segment_part)
	{
		 return isset($this->_url_parts[$segment_part-1]) ? $this->_url_parts[$segment_part-1] : false;
	}
	
	public function construct_url()
	{
		$url = '';
		$url .= implode('/',$this->_url_parts);
		
		return $url;
	}
	
	public function construct_query_string()
	{
		$query_string = '';
		
		if(count($this->_query_string) > 0)
		{
			$query_string .= http_build_query($this->_query_string);
			$query_string = urldecode($query_string); // just so things like [] don't get turned into weird encoded values
			$query_string = '?=' . $query_string;
		}
		return $query_string;
	}
}
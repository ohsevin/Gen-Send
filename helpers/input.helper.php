<?php
/**
* Input helper / utility class.
* 
* @package    Utils
* @author     Matt Brunt (http://maffyoo.co.uk)
* @version    0.1
* ...
*/

class GNSND_Input {
    
    public $post;
    public $get;
    
    function __construct()
    {
        $this->post = $_POST;
        $this->get = $_GET;
        
        $this->sanitize($this->post);
        $this->sanitize($this->get);
    }
    
    function sanitize(&$input_array)
    {
        filter_var_array($input_array, FILTER_SANITIZE_STRING);
    }
    
    /**
     *    Generates a random string of a length specified
     *
     *    @param    int        The length of the string to return
     *    @param    array    The options to determine our character set to use
     *    @return    string    The random string generated
     *
     *    @access    public
     */
    public function post($var = '')
    {
        if(trim($var) == '')
        {
            return $this->post; // return whole post array
        }
        elseif(isset($_POST[$var]))
        {
            return $_POST[$var];    
        }
        else
        {
            return false;
        }
    }
    
    
    public function get($var = '')
    {
        if(trim($var) == '')
        {
            return $this->get; // return whole post array
        }
        elseif(isset($_GET[$var]))
        {
            return $_GET[$var];    
        }
        else
        {
            return false;
        }
    }
}
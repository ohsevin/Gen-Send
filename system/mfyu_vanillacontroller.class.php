<?php

class MFYU_VanillaController {
    
    protected $_controller;
    protected $_action;
    protected $_template;
    protected $_ssl = false;
    
    public $meta = array('title' => '', 'keyword' => '', 'description' => '');
    public $no_render_chrome;
    public $render;
    public $url;

    function __construct($controller, $action, $autoload = array(), $url_class)
    {
        $this->_controller = $controller;
        $this->_action = $action;
        
        $this->url = $url_class;
        
        foreach($autoload['helpers'] as $key => $helper)
        {
            $this->load_helper($helper, $key);
        }
        
        foreach($autoload['libraries'] as $key => $library)
        {
            if($library == 'database') // always set $db for 'database' library for $this->db
            {
                $key = 'db';
            }
            
            $this->load_library($library, $key);
        }
        
        $this->_controller = ucfirst($controller);
        $this->_action = $action;
        
        $this->no_render_chrome = 0;
        $this->render = 1;
        $this->_template = new Template($controller, $action);
    }
    
    function set_template($template)
    {
        $this->_template->set_template($template);
    }
    
    function set($name,$value)
    {
        $this->_template->set($name,$value);
    }
    
    public function redirect($uri = '', $method = 'location', $http_response_code = 302)
    {
        if ( ! preg_match('#^https?://#i', $uri))
        {
            $uri = site_url() . $uri;
        }
        
        switch($method)
        {
            case 'refresh'    : header("Refresh:0;url=".$uri);
                break;
            default            : header("Location: ".$uri, TRUE, $http_response_code);
                break;
        }
        exit;
    }
    
    function load_model()
    {
        $inflect = Inflection::get_instance();
        
        $model = ucfirst($inflect->singularize($this->_controller));
        
        $model_file = ROOT . DS . 'application' . DS . 'models' . DS . strtolower($model) . '.php';
        
        // if the model doesn't exist - create the file with it
        if(!file_exists($model_file))
        {
            $model_file_content = '<?php if (!defined(\'BASE_PATH\')) exit(\'No direct script access allowed\');' . PHP_EOL;
            $model_file_content .= '' . PHP_EOL;
            $model_file_content .= 'class ' . $model . ' extends ' . strtoupper(SYSTEM_PREPEND) . '_VanillaModel {' . PHP_EOL;
            $model_file_content .= '        ' . PHP_EOL;
            $model_file_content .= '        protected $_abstract = true; /* used so table description isn\'t done */' . PHP_EOL;
            $model_file_content .= '        ' . PHP_EOL;
            $model_file_content .= '}' . PHP_EOL;
            
            $model_file_handle = fopen($model_file, 'w') or die("Can't auto-generate model.");
            
            fwrite($model_file_handle, $model_file_content);
            fclose($model_file_handle);
        }
        $model_var = strtolower($model);
        $this->$model_var = new $model;
    }
    
    function set_ssl($ssl = false)
    {
        $this->_ssl = $ssl;
    }
    
    function is_ssl()
    {
        return $this->_ssl;        
    }
    
    function load_helper($helper, $key = 0)
    {
        load_helper($helper); // global function to include the right file
        
        $helper_name = SYSTEM_PREPEND . $helper;
        
        $var_name = $key;
        
        if(is_int($key))
        {
            $var_name = strtolower($helper_name);
        }
        if(!isset($this->$var_name)) // only set if it doesn't already exist as $var_name so we don't overwrite new ones
        {
            $this->$var_name = new $helper_name;
        }
        
    }
    
    function load_library($library, $key = 0)
    {
        load_library($library); // global function to include the right file
        
        $library_name = SYSTEM_PREPEND . $library;
        
        $var_name = $key;
        
        if(is_int($key))
        {
            $var_name = strtolower($library_name);
        }
        
        if(!isset($this->$var_name)) // only set if it doesn't already exist as $var_name so we don't overwrite new ones
        {
            $this->$var_name = new $library_name;
        }
    }

    function __destruct()
    {
        // get our URL and query strings here!
        $url = $this->url->construct_url();
        $query_string = $this->url->construct_query_string();
        
        // force our SSL checking
        if($this->_ssl && $_SERVER['SERVER_PORT'] != SERVER_SSL_PORT)
        {
            redirect(SECURE_SITE_URL . $url . $query_string);
        }
        elseif(!$this->_ssl && $_SERVER['SERVER_PORT'] == SERVER_SSL_PORT) // if we don't want SSL and the server port IS SSL
        {
            redirect(SITE_URL . $url . $query_string);
        }
        
        
        if ($this->render) {
            
            $this->set('meta', $this->meta); // always set our meta
            
            $this->_template->render($this->no_render_chrome);
        }
    }
}
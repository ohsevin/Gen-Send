<?php

class PasswordController extends GNSND_VanillaController {
    
    public function before_action ()
    {
    	$this->set_header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
    	$this->set_header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    	
        $is_ssl = (GLOBAL_SSL) ? GLOBAL_SSL : false;
        $this->set_ssl($is_ssl);
        $this->set('is_ssl', $is_ssl);
        $this->load_helper('input', 'input'); // load our input helper
    }
    
    public function index()
    {
        $this->set('default_password_length', 8);
        
        $this->meta['title'] = 'Generate a random password';
        $this->meta['description'] = 'Use this tool to generate a random password with configuration options.';
        $this->meta['keywords'] = '';
            
        if($this->input->post('submit')) {
            
            $this->load_helper('validation', 'validation'); // load our validation helper
            
            $rules = array(
                'input|length'    =>    array(
                    'required'                =>    true,
                    'filters'                =>    'int',
                    'min_value'                =>    4,
                    'max_value'                =>    99
                )
            );
            
            // setup our form
            $this->validation->setup($this->input->post, $rules);
            
            if($this->validation->validate()) // successful form submission
            {
                $this->set('success', true);
                $this->load_helper('string'); // load our string helper
				$this->load_helper('crypt', 'crypt'); // load our crypt helper to generate secure random numbers
                
                $password = $this->gnsnd_string->generate_random_string($this->input->post['input']['length'], $this->input->post('options'), $this->crypt);
                $phonetic = $this->gnsnd_string->to_phonetic($password);
                
                $this->meta['title'] = 'Your password has been generated!';
                $this->meta['description'] = '';
                $this->meta['keywords'] = '';
                
                $this->set('password', $password);
                $this->set('phonetic', $phonetic);
            }
            else // uh-oh... errors
            {
                $this->set('errors', $this->validation->errors);
            }
            
            $this->set('post', $this->input->post);
        }
    }
    
	
	public function help()
	{
        $this->meta['title'] = 'About the Gen&Send Password Generator';
        $this->meta['description'] = '';
        $this->meta['keywords'] = '';
	}
	
    public function after_action()
    {

    }
}
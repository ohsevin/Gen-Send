<?php

class SecuresendController extends MFYU_VanillaController {
    
    public function before_action()
    {
        $is_ssl = (GLOBAL_SSL) ? GLOBAL_SSL : false;
        $this->set_ssl($is_ssl);
        $this->set('is_ssl', $is_ssl);
        
        $this->load_helper('input', 'input'); // load our input helper
        $this->load_helper('validation', 'validation'); // load our validation helper
        
        $this->load_model(); // load our securesend model
    }
    
    public function index()
    {
        $password = '';
        if($this->url->segment(3) != '')
        {
            $password = $this->url->segment(3);
        }
        
        $this->set('default_password', $password);
        
        $default_expire_time = 7;
        $this->set('default_expire_time', $default_expire_time);
        
        if(isset($this->input->post['password_transfer'])) // if we've transferred the password from our other form
        {
            $this->input->post['expire']['length'] = $default_expire_time;
        }
        
        $this->meta['title'] = 'Send your password securely';
        $this->meta['description'] = 'Use this tool to send your password securely to another person.';
        $this->meta['keywords'] = '';
            
        if($this->input->post('submit')) {
            
            $rules = array(
                'password'              =>    array(
                    'required'              => true,
                    'max_length'            => 255
                ),
                
                'expire|length'         =>    array(
                    'required'              =>    true,
                    'filters'               =>    'int',
                    'min_value'             =>    1,
                    
                    'error_messages'        =>    array(
                           'required'           =>    '\'Expire after\' is required'
                    )
                ),
                
                'expire|time'           =>    array(
                    'required'              =>    true,
                    'filters'               =>    'chars',
                    
                    'error_messages'        =>    array(
                           'required'           =>    'Please choose what type of expiration you want.',
                    )
                )
            );
            
            $this->validation->setup($this->input->post, $rules);
            
            if($this->validation->validate()) // successful form submission
            {
                // setup defaults before we change them based on what type of expiration it is
                $expiration_date = '0000-00-00';
                $this->securesend->expiration_views = 0; 
                
                if($this->input->post['expire']['time'] == 'views') // if it's after $length views
                {
                    $this->securesend->expiration_views = $this->input->post['expire']['length']; 
                }
                else // it's date-based expiration
                {
                    $today['timestamp'] = time(); // current timestamp
                    
                    $today['hour'] = date('H', $today['timestamp']);
                    $today['minute'] = date('i', $today['timestamp']);
                    $today['second'] = date('s', $today['timestamp']);
                    
                    $today['day'] = date('d', $today['timestamp']);
                    $today['month'] = date('m', $today['timestamp']);
                    $today['year'] = date('Y', $today['timestamp']);
					
                    switch($this->input->post['expire']['time'])
                    {
                        case 'days':
						      $expiration_timestamp = mktime(
							             $today['hour'],
										 $today['minute'],
										 $today['second'],
                                         $today['month'],
										 $today['day'] + $this->input->post['expire']['length'],
										 $today['year']
										 );
						      break;
                        case 'weeks':
                              $expiration_timestamp = mktime(
                                         $today['hour'],
                                         $today['minute'],
                                         $today['second'],
                                         $today['month'],
                                         $today['day'] + ($this->input->post['expire']['length'] * 7),
                                         $today['year']
                                         );
                              break;
                        case 'months':
                              $expiration_timestamp = mktime(
                                         $today['hour'],
                                         $today['minute'],
                                         $today['second'],
                                         $today['month'] + $this->input->post['expire']['length'],
                                         $today['day'],
                                         $today['year']
                                         );
                              break;
                    }
                }
                
                $expiration_date = ($this->input->post['expire']['time'] == 'views') ? $expiration_date : date('Y-m-d', $expiration_timestamp);;
				
                $this->securesend->expiration_date = $expiration_date;
                
                $this->load_helper('string'); // load our string helper
                    
                $url_unique = false;
                
                // while the URL isn't unique
                while(!$url_unique)
                {
                    // to generate the URL we'll basically use my string helper class to create a new URL, and check it doesn't exist...
                    // it's not tied to a DB row ID then, so it's not generated from that number, meaning harder to crack.
                    $this->securesend->url = $this->mfyu_string->generate_random_string(); // 8 chars default length, should be fine.
                    
                    if(!$this->securesend->url_exists()) // if the url doesn't already exist in the DB
                    {
                        $url_unique = true; // the URL is unique so we can continue onwards!
                    }
                }
                
				//finally, encrypt our password to save in the database!
                $this->load_helper('encryption'); // load our encryption helper
                
                $password = $this->mfyu_encryption->encrypt($this->input->post['password']);
                $this->securesend->pass = $password;
				
				
                if($this->securesend->save()) // if it saves
                {
                    $this->set('success', true); // success is true
                    
                    $this->set('url', $this->securesend->url); // set our URL for display on the following page
                    
                    // meta stuff
                    $this->meta['title'] = 'Securely stored!';
                    $this->meta['description'] = '';
                    $this->meta['keywords'] = '';
                }
                else // there was a problem...
                {
                    $this->meta['title'] = 'Problem!';
                    $this->meta['description'] = '';
                    $this->meta['keywords'] = '';
                }
            }
            else // uh-oh... form validation errors!
            {
                $this->set('errors', $this->validation->errors);
                $this->set('post', $this->input->post);
            }
        }
    }
    
    public function v() // our view function, named v for short-ness of URLs
    {
    	// set the template to be "view" instead of the default of "v" for the action
        $this->set_template('view');
		
		
		// get our URL part
        $url = $this->url->segment(3);
		
        if(!$url || trim($url) == '') // if no URL, redirect them back to securesend index page
        {
            $this->redirect('securesend/');
        }
        
		// get our password by URL
        $this->securesend->get_by_url($url);
        
        if($this->securesend->exists()) // the item exists!
        {
	        $this->load_helper('encryption'); // load our encryption helper
	        // decrypt password from the DB
	        $password = $this->mfyu_encryption->decrypt($this->securesend->pass);
			
            // set our password for the view page
            $this->set('password', $password);
            
            // update stuff here if it's a view-count-based-password-thingy
            if($this->securesend->expiration_date == '0000-00-00' && $this->securesend->expiration_views > 0) // if it's blank expiration date and has views then it's a view-based password
            {
                $this->securesend->viewed(); // this password has been viewed! Do stuff with it! (if it reaches 0 it'll be deleted)
            }
            
			// meta stuff
            $this->meta['title'] = 'Your password!';
            $this->meta['description'] = '';
            $this->meta['keywords'] = '';
            
        }
        else
        {
            $errors[] = 'Invalid URL'; // add invalid URL message onto errors (DB row doesn't exist with that URL)
            $this->set('errors', $errors);
        }
    }
    
    public function cron_update() // cron update function
    {
        // check the 3rd URL segment against our random hash in the config.
        
        $hash = $this->url->segment(3);
        
        if($hash == CONFIG_HASH) // success, it matches
        {
            // crawl throuh DB and remove time-expired passwords!
			// could use model to db interaction, but we'll just delete all records where expiry date is less than the current date
			if($this->securesend->delete_expired()) // if we successfully deleted out of date entries
			{
				$status = "SUCCESSFUL DELETION";
			}
			else
			{
				$status = "FAILED DELETION";
			}
			
            $rows = 0; // used to count number of rows we're deleting
            $subject = 'Cron has run';
            $message = <<<HTML
            Cron has run for the securesend password system.
            
            Status is: {$status}
            
            Sleep well.
HTML;
        }
        else // naughty, someone's tried to access this
        {
            $rows = 0; // used to count number of rows we're deleting
            $subject = 'Cron has been badly accessed';
            $message = <<<HTML
            Cron has been accessed incorrectly on the securesend password system.
            
            The hash is incorrect!
            
            Investigate!
HTML;
            
        }
        
		$headers = 'From: ' . SYSADMIN_EMAIL . PHP_EOL .
            'Reply-To: ' . SYSADMIN_EMAIL . PHP_EOL .
            'X-Mailer: PHP/' . phpversion();
		
        if(!DEVELOPMENT_ENVIRONMENT)
		{
			mail(SYSADMIN_EMAIL, $subject, $message, $headers);
		}
        
        $this->render = 0; // do not render
        return true;
    }
    
    public function after_action()
    {

    }
}
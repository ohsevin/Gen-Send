<?php
/**
* Validation helper class.
*
* @package    GNSND_Framework
* @author     Matt Brunt (http://maffyoo.co.uk)
* @version    0.3.3
* ...
*/

/**
 * 
 * EXAMPLE RULES SETUP (ALL RULE TYPES & OPTIONS)
     $rules = array(
     //    multi dimensional arrays are specified as such:
     //    'input|length' is the variable $_POST['input']['length'] 
     //    this can go on as much as needed, the validator constructs parents from the input array and matches it to the rules key :)
         
        'input|length'                =>    array(
            'required'                    =>    true,
            'human'                       =>    'Password length',
            'min_length'                  =>    5,
            'max_length'                  =>    100,
            'min_value'                   =>    5,
            'max_value'                   =>    100,
            
            //    filters run checks on the input to check if it's valid for that filter.
            //    you can specify custom filters that'll run a custom function as well
            //    this function must return true or false
            //    function name will be filter_$custom    where $custom is the name of the filter you passed in
            'filters'                    =>    'chars|email|url|float|int|custom',
            
            // error messages for each of our things to check
            'error_messages'            =>    array(
                'required'                  =>    '',
                'min_length'                =>    '',
                'max_length'                =>    '',
                'min_value'                 =>    '',
                'max_value'                 =>    ''
                
                // specify error messages for each of the filters as well
                'filters'            => array(
                    'chars'              =>    '',
                    'email'              =>    '',
                    'float'              =>    '',
                    'int'                =>    '',
                    'custom'             =>    ''
                )
            )
        )
    );
 * 
 * 
 */

class GNSND_Validation {
    
    /**
     * @var array    stores the data passed into the validation class (typically $_POST)  
     */
    protected $_data = array();
    
    /**
     * @var array    the rules passed into the validation class as an array
     */
    protected $_rules = array();
    
    /**
     * @var array    Stores parent keys for traversing through an array
     */
    protected $_parents = array();
    
    /**
     * @var array    used to store an array of our data as rule-keys instead of multi-dimensional arrays
     */
    protected $_rule_keys_array = array();
    
    /**
     * @var array    Used to store errors in the form for output.
     */
    public $errors = array();
    
    /**
    * Error message definitions - not all are used but hey.
    */
    const ERROR_GENERIC_INVALID         = 'The field \'%s\' is invalid.';
    const ERROR_REQUIRED                = 'The field \'%s\' is required. Please enter a value.';
    const ERROR_ALPHA                   = 'The field \'%s\' should contain only alpha-numeric characters';
    const ERROR_EMAIL                   = 'Please input a valid email address.';
    const ERROR_URL                     = 'The field \'%s\' should be a valid URL.';
    const ERROR_MIN_LENGTH              = 'The field \'%s\' does not meet the minimum length.';
    const ERROR_MAX_LENGTH              = 'The field \'%s\' exceeds the maximum length.';
    const ERROR_MIN_VALUE               = 'The field \'%s\' does not meet the minimum value.';
    const ERROR_MAX_VALUE               = 'The field \'%s\' exceeds the maximum value.';
    const ERROR_NUMERIC                 = 'The field \'%s\' should hold a numeric value.';
    const ERROR_INT                     = 'The field \'%s\' should hold a numeric value.';
    const ERROR_NO_FUNCTION             = 'Validation function %s() doesn\'t exist for field \'%s\'';
    
    /**
    * Used to separate rule stuff, for both keys for multi-dimensional rules and filters
    */
    const RULE_SEPARATOR                = '|';
    
    
    /**
     * Runs the setup for our validation class, sets the data array to loop through and the rules to be applied
     * 
     * @param   array   $data[optional]     data array we'll be validating ($_POST for example)
     * @param   array   $rules [optional]   rules to validate against
     * @return 
     */
    function __construct($data = array(), $rules = array())
    {
        $this->setup($data, $rules);
    }
    
    /**
     * Runs the setup for our validation class, sets the data array to loop through and the rules to be applied
     * 
     * @param   array   $data [optional]    data array we'll be validating ($_POST for example)
     * @param   array   $rules [optional]   rules to validate against
     */
    
    public function setup($data = array(), $rules = array())
    {
        $this->_data = $data;
        $this->_rules = $rules;
    }
    
    /**
     * Clears all our variables for the class.
     * 
     * Maybe used it validating multiple things within the same script?
     * 
     * $validation->setup($data_array_one, $rules_array_two);
     * $validation->validate(); // validate form 1 with setup
     * 
     * // checking etc
     * 
     * $validation->clear();
     * 
     * $validation->setup($data_array_two, $rules_array_two);
     * $validation->validate(); // validate form 2 with setup
     * 
     */
    public function clear()
    {
        
        $this->_data = array();
        $this->_rules = array();
        $this->_parents = array();
        $this->_rule_keys_array = array();
        $this->errors = array();
        
    }
    
    /**
     * Run the validation, is called recursively if a multi-dimensional array is found.
     * 
     * @param   object  $data [optional]    passed in when the function is called recursively, so it only validates that array instead of $this->_merged_data_rules
     * @return  boolean true|false
     */
    public function validate($data = array())
    {
        if(!empty($data))
        {
            $this->_data = $data; // set data to validate if passed to this function - overwrites existing data.
        }
        
        if(count($this->_rules) == 0) // no rules set
        {
            $this->errors['validation_rules'] = 'No rules have been set for validation.';
        }
        
        // convert $data to rule keys to match against our rules
        $data_as_rule_keys = $this->_array_to_rule_keys($this->_data);
        
        // check through our rules!
        foreach($this->_rules as $rule_key => $rule_info)
        {
            // get human readable
            $key = explode(self::RULE_SEPARATOR, $rule_key);
            $key = array_pop($key);
            $human = isset($this->_rules[$rule_key]['human']) ? $this->_rules[$rule_key]['human'] : str_replace('_', ' ' , ucfirst($key));
            
            // get value
            $value = isset($data_as_rule_keys[$rule_key]) ? $data_as_rule_keys[$rule_key] : '';
            $value = trim($value);
            
            // is it required?
            if($this->_rules[$rule_key]['required'] && $value == '')
            {
                if(isset($this->_rules[$rule_key]['error_messages']['required']))
                {
                    $this->errors[$rule_key] = $this->_rules[$rule_key]['error_messages']['required'];
                }
                else
                {
                    $this->errors[$rule_key] = 'Field \'' . $human . '\' is required.';
                }
                continue;
            }
            
            // do we have filters set for this rule?
            if(isset($this->_rules[$rule_key]['filters']))
            {
                // get our filters
                $filters = explode(self::RULE_SEPARATOR, $this->_rules[$rule_key]['filters']);
                
                // loop through them
                foreach($filters as $filter)
                {
                    // create filter function name
                    $method_name = 'filter_' . $filter;
                    
                    // if the method doesn't exist - output an error for no method
                    if(!method_exists(__CLASS__, $method_name))
                    {
                        $this->errors[] = sprintf(ERROR_NO_FUNCTION, $method_name, $human);
                    }
                    else // function exists
                    {
                        // call the function, if it returns false, continue.
                        if(!$this->$method_name($value, $rule_key, $human))
                        {
                            continue;
                        }
                    }
                }
                
                // if we have errors at this point, just continue out of the loop
                if(count($this->errors) > 0)
                {
                    continue;
                }
            }
            
            // check min length
            if(isset($this->_rules[$rule_key]['min_length']) && strlen($value) < $this->_rules[$rule_key]['min_length'])
            {
                if(isset($this->_rules[$rule_key]['error_messages']['min_length']))
                {
                    $this->errors[$rule_key] = $this->_rules[$rule_key]['error_messages']['min_length'];
                }
                else
                {
                    $this->errors[$rule_key] = 'Field \'' . $human . '\' is too short, minimum length: ' . $this->_rules[$rule_key]['min_length'];
                }
                continue;
            }
            
            // check max length
            if(isset($this->_rules[$rule_key]['max_length']) && strlen($value) > $this->_rules[$rule_key]['max_length'])
            {
                if(isset($this->_rules[$rule_key]['error_messages']['max_length']))
                {
                    $this->errors[$rule_key] = $this->_rules[$rule_key]['error_messages']['max_length'];
                }
                else
                {
                    $this->errors[$rule_key] = 'Field \'' . $human . '\' is too long, maximum length: ' . $this->_rules[$rule_key]['max_length'];
                }
                continue;
            }
            
            // check min value
            if(isset($this->_rules[$rule_key]['min_value']) && (int)$value < $this->_rules[$rule_key]['min_value'])
            {
                if(isset($this->_rules[$rule_key]['error_messages']['min_value']))
                {
                    $this->errors[$rule_key] = $this->_rules[$rule_key]['error_messages']['min_value'];
                }
                else
                {
                    $this->errors[$rule_key] = 'Field \'' . $human . '\' is too small, minimum value: ' . $this->_rules[$rule_key]['min_value'];
                }
                continue;
            }
            
            // check max value
            if(isset($this->_rules[$rule_key]['max_value']) && (int)$value > $this->_rules[$rule_key]['max_value'])
            {
                if(isset($this->_rules[$rule_key]['error_messages']['max_value']))
                {
                    $this->errors[$rule_key] = $this->_rules[$rule_key]['error_messages']['max_value'];
                }
                else
                {
                    $this->errors[$rule_key] = 'Field \'' . $human . '\' is too large, maximum value: ' . $this->_rules[$rule_key]['max_value'];
                }
                continue;
            }
        }
        
        if(count($this->errors) > 0) // we have errors, return false
        {
            return false;
        }
        else // success! Return true
        {
            return true;
        }
    }
    
    /* FILTER FUNCTIONS
     * 
     */
    
    /**
     * Filter the value against our character regex
     * 
     * @param   object  $value                  the value to rulter against our rule
     * @param   object  $rule_key [optional]    the rule key we're validating against (for error message setting)
     * @param   object  $human                  human readable field name
     * @return  boolean true|false
     */
    public function filter_chars($value, $rule_key = '', $human)
    {
        // standard character regex, allow uppercase, lowercase, numbers, hyphens and underscores
        $char_regex = '[^A-Za-z0-9\-\_]';
            
        if(preg_match($char_regex, $value))
        {
            if(isset($this->_rules[$rule_key]['error_messages']['filters']['chars']))
            {
                $this->errors[$rule_key] = $this->_rules[$rule_key]['error_messages']['filters']['chars'];
            }
            else
            {
                $this->errors[$rule_key] = sprintf(self::ERROR_ALPHA, $human);
            }
            return false;
        }
        else
        {
            return true;
        }
    }
    
    /**
     * Filter the value against PHP's own email filter.
     * 
     * @param   object  $value                  the value to rulter against our rule
     * @param   object  $rule_key [optional]    the rule key we're validating against (for error message setting)
     * @param   object  $human                  human readable field name
     * @return  boolean true|false
     */
    public function filter_email($value, $rule_key = '', $human)
    {
        if(!filter_var($value, FILTER_VALIDATE_EMAIL))
        {
            if(isset($this->_rules[$rule_key]['error_messages']['filters']['email']))
            {
                $this->errors[$rule_key] = $this->_rules[$rule_key]['error_messages']['filters']['email'];
            }
            else
            {
                $this->errors[$rule_key] = sprintf(self::ERROR_EMAIL, $human);
            }
            return false;
        }
        else
        {
            return true;
        }
    }
    
    /**
     * Filter the value against PHP's own URL filter.
     * 
     * @param   object  $value                  the value to rulter against our rule
     * @param   object  $rule_key [optional]    the rule key we're validating against (for error message setting)
     * @param   object  $human                  human readable field name
     * @return  boolean true|false
     */
    public function filter_url($value, $rule_key = '', $human)
    {
        if(!filter_var($value, FILTER_VALIDATE_URL))
        {
            if(isset($this->_rules[$rule_key]['error_messages']['filters']['url']))
            {
                $this->errors[$rule_key] = $this->_rules[$rule_key]['error_messages']['filters']['url'];
            }
            else
            {
                $this->errors[$rule_key] = sprintf(self::ERROR_URL, $human);
            }
            return false;
        }
        else
        {
            return true;
        }
    }
    
    
    /**
     * Filter the value against PHP's own integer filter.
     * 
     * @param   object  $value                  the value to rulter against our rule
     * @param   object  $rule_key [optional]    the rule key we're validating against (for error message setting)
     * @param   object  $human                  human readable field name
     * @return  boolean true|false
     */
    public function filter_int($value, $rule_key = '', $human)
    {
        if(!filter_var($value, FILTER_VALIDATE_INT))
        {
            if(isset($this->_rules[$rule_key]['error_messages']['filters']['int']))
            {
                $this->errors[$rule_key] = $this->_rules[$rule_key]['error_messages']['filters']['int'];
            }
            else
            {
                $this->errors[$rule_key] = sprintf(self::ERROR_INT, $human);
            }
            return false;
        }
        else
        {
            return true;
        }
    }
    
    
    /**
     * Filter the value against PHP's own float filter.
     * 
     * @param   object  $value                  the value to rulter against our rule
     * @param   object  $rule_key [optional]    the rule key we're validating against (for error message setting)
     * @param   object  $human                  human readable field name
     * @return  boolean true|false
     */
    public function filter_float($value, $rule_key = '', $human)
    {
        if(!filter_var($value, FILTER_VALIDATE_FLOAT))
        {
            if(isset($this->_rules[$rule_key]['error_messages']['filters']['float']))
            {
                $this->errors[$rule_key] = $this->_rules[$rule_key]['error_messages']['filters']['float'];
            }
            else
            {
                $this->errors[$rule_key] = sprintf(self::ERROR_NUMERIC, $human);
            }
            return false;
        }
        else
        {
            return true;
        }
    }
    
    /**
     * Returns our rule key for an input value.
     * 
     * For example:
     * 
     * If we passed in $_POST['input']['personal']['name']
     * 
     * in $this->_parents we'd have:
     * 
     * [0]        =>        'input',
     * [1]        =>        'personal'
     * 
     * and our $key would be $name.
     * 
     * TO get the validation rule, we use our separator (constant defined as |) to get our validation rule of:
     * 
     * input|personal|name
     * 
     * @param   object  $key [optional]     Key of the final item to go on there...
     * @return  string  the rule key of the rule based on parents
     */
    protected function _generate_rule_key($key = '')
    {
        // set the rule key to return
        $rule_key = '';
        
        // if we have parents - implode using the separator
        if(count($this->_parents) > 0)
        {
            $rule_key = implode(self::RULE_SEPARATOR, $this->_parents) . self::RULE_SEPARATOR;
        }
        
        // append the key of the value onto the end
        $rule_key .= $key;
        
        // return it
        return $rule_key;
    }
     
    /** 
     * Turns array into corresponding rule keys.
     * 
     * Example:
     * 
     * Array (
     *         'input'    => array(
     *             'length'    => $value
     *         ),
     *         'input'    => array(
     *             'width'    => $value
     *         )
     * )
     * 
     * in a new array of:
     * 
     * Array (
     *         'input|length'    => $value,
     *         'input|width'    => $value
     * )
     *
     * @param   array  $data_array [optional]     Data array to convert to rule keys
     * @return  array  the converted array
     */
    protected function _array_to_rule_keys($data_array = array())
    {
        foreach($data_array as $key => $value)
        {
            if(is_array($value))
            {
                $this->_parents[] = $key;
                $append = $this->_array_to_rule_keys($value);
            }
            else
            {
                $rule_key = $this->_generate_rule_key($key);
                $this->_rule_keys_array[$rule_key] = $value;
            }
        }
        
        $this->_parents = array(); // reset parents
        
        return $this->_rule_keys_array;
    }
}
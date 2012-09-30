<?php
/**
* String helper / utility class.
* Methods to do nice things with strings.
*
* @package    Utils
* @author     Matt Brunt (http://maffyoo.co.uk)
* @version    0.3.3
*/

class GNSND_String {
        
    public static $characters = array(
                'full_lowercase'            =>        'abcdefghijklmnopqrstuvwxyz',
                'full_uppercase'            =>        'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
                'non_similar_lowercase'     =>        'abcdefghjkmnpqrtuvwxyz',
                'non_similar_uppercase'     =>        'ABCDEFGHJKMNPQRTUVWXYZ',
                'standard_numbers'          =>        '12346789',
                'similar'                   =>        'iIlLoOsS150',
                'punctuation'               =>        '!@#$%^&*?_~()-+:;'
    );
    
    protected static $default_random_options = array(
                'non_similar_lowercase'     =>        true,
                'non_similar_uppercase'     =>        true,
                'standard_numbers'          =>        true,
                'similar'                   =>        false,
                'punctuation'               =>        false
    );
    
    protected $_character_set = '';
    
    public static $phonetic = array(
    
                // Letters
                'a' => 'alfa',
                'b' => 'bravo',
                'c' => 'charlie',
                'd' => 'delta',
                'e' => 'echo',
                'f' => 'foxtrot',
                'g' => 'golf',
                'h' => 'hotel',
                'i' => 'india',
                'j' => 'juliet',
                'k' => 'kilo',
                'l' => 'lima',
                'm' => 'mike',
                'n' => 'november',
                'o' => 'oscar',
                'p' => 'papa',
                'q' => 'quebec',
                'r' => 'romeo',
                's' => 'sierra',
                't' => 'tango',
                'u' => 'uniform',
                'v' => 'victor',
                'w' => 'whisky',
                'x' => 'xray',
                'y' => 'yankee',
                'z' => 'zulu',
                
                // numbers
                '0' => 'zero',
                '1' => 'one',
                '2' => 'two',
                '3' => 'three',
                '4' => 'four',
                '5' => 'five',
                '6' => 'six',
                '7' => 'seven',
                '8' => 'eight',
                '9' => 'niner',
                
                // punctuation
                '!' => 'exclamation mark',
                '?' => 'question mark',
                '$' => 'dollar',
                '%' => 'percent',
                '&' => 'ampersand',
                '*' => 'asterisk',
                '^' => 'caret',
                '~' => 'tilde',
                '(' => 'open braces',
                ')' => 'close braces',
                '-' => 'dash',
                '+' => 'plus',
                '@' => 'at',
                '#' => 'hash',
                '_' => 'underscore',
                '£' => 'pound',
                ':' => 'colon',
                ';' => 'semicolon'
    );
    
    /**
     *    Generates a random string of a length specified
     *
     *    @param    int         The length of the string to return
     *    @param    array      The options to determine our character set to use
     *    @return   string
     *
     *    @access    public
     */
    public function generate_random_string($length = 8, $options = array())
    {
        // If no options set, use default ones.
        if(empty($options))
        {
            $options = self::$default_random_options;
        }
        
        // For each option set as true, append the characters for that option to our character set
        foreach($options as $option => $use)
        {
            if($use && array_key_exists($option, self::$characters))
            {
                $this->_character_set .= self::$characters[$option];
            }
        }
		
		$string = '';
        
        // Let's shuffle the character set to make it randomly ordered
        $this->_character_set = str_shuffle($this->_character_set);
        
        // Generate our string
        while(strlen($string) < $length)
        {
            $string .= $this->_character_set[rand(0, strlen($this->_character_set) - 1)];
        }
		
        // Return our string
        return $string;
    }
    
    /**
     *    Convert a string to the phonetic alphabet.
     *
     *    @param    string      The string to convert
     *    @param    boolean     Match on the case, so A lists as ALPHA and a lists as alpha
     *    @return   string    
     *
     *    @access   public
     */
    public function to_phonetic($string = '', $match_case = true, $separator = ' - ')
    {
        $string = trim($string);
        if(strlen($string) == 0)
        {
            return '';
        }
        
        $phonetic = array();
        
        // split our string so we have individual characters to work with
        $characters = str_split($string);
        
        // loop to add phonetic meaning to each character in the string.
        foreach($characters as $position => $character)
        {
            if(array_key_exists($character, self::$phonetic) && $match_case) // check as they are (lowercase)
            {
                $phonetic[$position] = self::$phonetic[$character];
            }
            elseif(array_key_exists(strtolower($character), self::$phonetic) && $match_case) // check for uppercase
            {
                $phonetic[$position] = strtoupper(self::$phonetic[strtolower($character)]);
            }
            elseif(array_key_exists(strtolower($character), self::$phonetic) && !$match_case) // just convert all to lowercase and match here
            {
                $phonetic[$position] = self::$phonetic[strtolower($character)];
            }
            else // we can't find it, it's an unknown... currently this phonetic converter works only on the array specified in this helper class
            {
                $phonetic[$position] = 'UNKNOWN';
            }
        }
        
        return implode($separator, $phonetic); // return our phonetic string separated by our separator
    }

    /**
     *    Returns a numeric value of the strength score of a string.
     *    Higher score if it matches multiple conditions
     *
     *    @param    string  The string to check
     *    @return   int
     *
     *    @access    public
     */
    public function check_strength($string = '')
    {
        $score = 0;
        
        // trim the string
        $string = trim($string);
        
        if(strlen($string) == 0)
        {
            return $score; // string is blank, return starting score - bad string!
        }
        
        // if it's longer than or equal to 8, give it a point
        if(strlen($string) >= 8)
        {
            $score++;
        }
        
        // if it's longer than or equal to 12, give it another point
        if(strlen($string) >= 12)
        {
            $score++;
        }
        
        // if we find lowercase letters
        if (preg_match("/[a-z]/", $string))
        {
            $score++;
        }
        // if we find uppercase letters
        if(preg_match("/[A-Z]/", $string))
        {
            $score++;
        }
        // if we find a number
        if (preg_match("/[0-9]/", $string))
        {
            $score++;
        }
        
        // split our punctuation into an array
        $punctuation_list = str_split(self::$characters['punctuation']);
        
        // implode that array with commas
        $punctuation_list = implode(',', $punctuation_list);
        
        // form the regex
        $punctuation_regex = "/.[" . $punctuation_list . "]/";
        
        // if we find symbols matched in our list of punctuation
        if (preg_match($punctuation_regex, $string))
        {
            $score++;
        }
        
        return $score;
    }
}
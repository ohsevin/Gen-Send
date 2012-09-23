<?php
/**
* String helper / utility class.
* Methods to do nice things with strings.
*
* @package    Utils
* @author     Matt Brunt (http://maffyoo.co.uk)
* @version    0.3.2
*/

class MFYU_String {
        
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
    
    protected static $character_set = '';
    
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
     *    @param    int        The length of the string to return
     *    @param    array    The options to determine our character set to use
     *    @return    string    The random string generated
     *
     *    @access    public
     */
    public function generate_random_string($length = 8, $options = array())
    {
        $string = '';
        
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
                self::$character_set .= self::$characters[$option];
            }
        }
        
        // Let's shuffle the string, why? Because we can, don't have to, but 'more random' is good right?
        self::$character_set = str_shuffle(self::$character_set);
        
        // Generate our string
        while(strlen($string) < $length)
        {
            $string .= self::$character_set[rand(0, strlen(self::$character_set) - 1)];
        }
        
        return $string;
    }
    
    /**
     *    Convert a string to the phonetic alphabet.
     *
     *    @param    string    The string to convert
     *    @param    boolean    Match on the case, so A lists as ALPHA and a lists as alpha
     *    @return    string    A string in phonetic
     *
     *    @access    public
     */
    public function to_phonetic($string = '', $match_case = true, $separator = ' - ')
    {
        $string = trim($string);
        if(strlen($string) == 0)
        {
            return '';
        }
        
        // split our string so we have individual characters to work with
        $characters = str_split($string);
        
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
     *    Convert a string to binary.
     *
     *    @param    string    The string to convert
     *    @param    string    The separator for our returned string
     *    @param    int        Value to pad the binary to, PHP doesn't output to 8-bit binary by default.
     *    @return    string    A string, but in binary format
     *
     *    @access    public
     */
    public function string_to_binary($string = '', $separator = ' ', $bit_binary = 8)
    {
        // split our string so we have individual characters to work with
        $characters = str_split($string);
        $binary = array();
        
        foreach($characters as $position => $character)
        {
            // pad to X-bit binary - bad? Maybe...
            $binary[$position] = str_pad(decbin(ord($character)), $bit_binary, '0', STR_PAD_LEFT);
        }
        
        return implode($separator, $binary);
    }
    
    /**
     *    Remove all non alpha numeric or space characters.
     *
     *    @param    string    The string to replace
     *    @return    string    The replaced string
     *
     *    @access    public
     */
    public function strip_non_alpha_numeric_spaces($string = '')
    {
        return preg_replace('/[^a-z0-9 ]/i', '', $string);
    }
    
    /**
     *    Searches through a string to convert all links to hyperlinks
     *
     *    @param    string    The string to replace
     *    @return    string    The replaced string
     *
     *    @access    public
     */
    public function urls_to_links($string = '')
    {
        $reg_ex_url = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
        
        if (preg_match_all($reg_ex_url, $string, $urls))
        {
            foreach($urls[0] as $url){

                $position = strpos($string, ' ' . $url) + 1;
                $string = substr_replace($string, '', $position, strlen($url));
                $string = substr_replace($string, '' . $url . '', $position, 0);
            }
            return $string;
        }
        else
        {
            return $string;
        }
    }
    
    /**
     *    Returns a numeric value of the strength score of a string.
     *    Higher score if it matches multiple conditions
     *
     *    @param    string    The string to check
     *    @return    int        The score of the string (0-6)
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
<?php
/**
* Cryptographically secure helper
*
* @package    Utils
* @author     Matt Brunt (http://maffyoo.co.uk)
* @version    0.0.2
*/

class GNSND_Crypt {
	
	/**
     *    Generates a random number between two given values
	 *    More secure than rand() 
     *
     *    @param    int        Minimum number
     *    @param    int        Maximum number
     *    @return   int        The random number
     *
     *    @access    public
     */
    public function random_number($min = 0, $max = 1000000)
    {
        // get our range
        $range = $max - $min;
        
        if ($range == 0) return $min; // return because there's no difference, oops.
        
        // get the length of bytes we should generate
        $length = (int)(log($range, 2) / 8) + 1;
		
        return $min + (hexdec(bin2hex(openssl_random_pseudo_bytes($length, $s))) % $range);
    }
}
<?php


/**
* Cryptographically secure helper
*
* @package    Gensend
* @author     Matt Brunt (http://maffyoo.co.uk)
* @version    0.0.3
*/

class Crypt {
    
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
    public function random_number($min = 0, $max = 1000000) {
        // get our range
        $range = $max - $min;
        
        if ($range == 0) return $min; // uh-oh.
        
        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        
        do
        {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes, $s)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        
        return $min + $rnd;
    }
}
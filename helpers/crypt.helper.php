<?php
/**
* Cryptographically secure helper
*
* @package    Utils
* @author     Matt Brunt (http://maffyoo.co.uk)
* @version    0.0.1
*/

class GNSND_Crypt {

    public function random_number($min = 0, $max = 1000000)
    {
        $range = $max - $min;
        if ($range == 0) return $min; // return because there's no difference, oops.
        $length = (int)(log($range, 2) / 8) + 1;
		
        return $min + (hexdec(bin2hex(openssl_random_pseudo_bytes($length, $s))) % $range);
    }
}
<?php
/**
* Encryption helper / utility class.
* 
* Uses mcrypt to encrypt and decrypt data (nice for mnore secure storage)
* 
* @package    Utils
* @author     Matt Brunt (http://maffyoo.co.uk)
* @version    0.1
* ...
*/

class MFYU_Encryption {
	
	CONST ENCRYPT_CIPHER		= MCRYPT_RIJNDAEL_256;
	CONST ENCRYPT_MODE			= MCRYPT_MODE_ECB;
	CONST ENCRYPT_RAND			= MCRYPT_RAND;
	CONST ENCRYPT_KEY			= SITE_ENCRYPTION_KEY; // set this to be whatever's in your config file for an encryption key, longer = better
	
	function __construct()
	{
		
	}
	
	public function encrypt($value = false)
	{
		if(!$value)
		{
			return false;
		}
		$iv = mcrypt_create_iv(mcrypt_get_iv_size(self::ENCRYPT_CIPHER, self::ENCRYPT_MODE), self::ENCRYPT_RAND);
		$encrypted_text = mcrypt_encrypt(self::ENCRYPT_CIPHER, self::ENCRYPT_KEY, $value, self::ENCRYPT_MODE, 'SECURE_STRING_2');
		
		return trim(base64_encode($encrypted_text));
	}
	
	public function decrypt($value = false)
	{
		if(!$value)
		{
			return false;
		}
		$value = base64_decode($value);
		$iv = mcrypt_create_iv(mcrypt_get_iv_size(self::ENCRYPT_CIPHER, self::ENCRYPT_MODE), self::ENCRYPT_RAND);
		
		$password = mcrypt_decrypt(self::ENCRYPT_CIPHER, self::ENCRYPT_KEY, $value, self::ENCRYPT_MODE, $iv);
		
		return $password;		
	}
}
<?php

    $plaintext = 'Testing OpenSSL Functions';
    $methods = openssl_get_cipher_methods();
    //$clefSecrete = 'flight';
    echo '<pre>';       
   // foreach ($methods as $method) {
   	$method = 'AES-128-CBC';     

        $ivlen = openssl_cipher_iv_length($method);
        $clefSecrete = "jGqo4iuKY57iMn4E";
         $iv = openssl_random_pseudo_bytes($ivlen);
        $encrypted = openssl_encrypt($plaintext, $method, $clefSecrete, OPENSSL_RAW_DATA, $clefSecrete);
        $decrypted = openssl_decrypt($encrypted, $method, $clefSecrete, OPENSSL_RAW_DATA, $clefSecrete);
        echo 'plaintext='.$plaintext. "\n";
        echo 'cipher='.$method. "\n";
        echo 'encrypted to: '.$encrypted. "\n";
        echo 'decrypted to: '.$decrypted. "\n\n";
    //}
    echo '</pre>';
$resenc = encryptAes($plaintext, $clefSecrete);
echo "enc--".$resenc;
$resdec = decryptAes($resenc, $clefSecrete);
echo "dec--".$resdec;
function encryptAes($plaintext, $clefSecrete)
{
	// AES encryption, CBC blocking with PKCS5 padding then HEX encoding.
	// Add PKCS5 padding to the text to be encypted.
	//$string = addPKCS5Padding($string);
	// Perform encryption with PHP's MCRYPT module.
	$cipher = 'AES-128-CBC';     
	//$crypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $string, MCRYPT_MODE_CBC, $key);
$crypt = openssl_encrypt($plaintext,$cipher,$clefSecrete,OPENSSL_RAW_DATA,$clefSecrete);
	// Perform hex encoding and return.
	return "@" . strtoupper(bin2hex($crypt));
}

function decryptAes($strIn, $password)
{
	// HEX decoding then AES decryption, CBC blocking with PKCS5 padding.
	// Use initialization vector (IV) set from $str_encryption_password.
	$strInitVector = $password;

	// Remove the first char which is @ to flag this is AES encrypted and HEX decoding.
	$hex = substr($strIn, 1);

	// Throw exception if string is malformed
	if (!preg_match('/^[0-9a-fA-F]+$/', $hex))
	{
		echo '<br><br>Invalid encryption string1';
		exit;
	}
	$strIn = pack('H*', $hex);
	$cipher = 'AES-128-CBC';     

	// Perform decryption with PHP's MCRYPT module.
	//$string = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $password, $strIn, MCRYPT_MODE_CBC, $strInitVector);
	        $string = openssl_decrypt($strIn, $cipher, $password, OPENSSL_RAW_DATA, $password);

	return $string;
}

?>

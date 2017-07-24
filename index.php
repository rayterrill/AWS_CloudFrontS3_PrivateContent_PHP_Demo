<?php

require __DIR__ . '/vendor/autoload.php';

function getSignedURL($resource, $timeout)
{
	//This comes from key pair you generated for cloudfront
	$keyPairId = getenv('CLOUDFRONT_KEY_PAIR');

	$expires = time() + $timeout; //Time out in seconds
	$json = '{"Statement":[{"Resource":"'.$resource.'","Condition":{"DateLessThan":{"AWS:EpochTime":'.$expires.'}}}]}';		

    //READ PRIVATE KEY FROM ENVIRONMENT VARIABLE
    $priv_key = getenv('CLOUDFRONT_KEY');
    
    //use phpseclib to convert the key to make sure it works with openssl_get_privatekey which is SUPER picky
    $rsa = new phpseclib\Crypt\RSA();
    $rsa->loadKey($priv_key);

	//Create the private key
	$key = openssl_get_privatekey($rsa);
	if(!$key)
	{
		echo "<p>Failed to load private key!</p>";
		return;
	}
	
	//Sign the policy with the private key
	if(!openssl_sign($json, $signed_policy, $key, OPENSSL_ALGO_SHA1))
	{
		echo '<p>Failed to sign policy: '.openssl_error_string().'</p>';
		return;
	}
	
	//Create url safe signed policy
	$base64_signed_policy = base64_encode($signed_policy);
	$signature = str_replace(array('+','=','/'), array('-','_','~'), $base64_signed_policy);

	//Construct the URL
	$url = $resource.'?Expires='.$expires.'&Signature='.$signature.'&Key-Pair-Id='.$keyPairId;
	
	return $url;
}

$imageURL = 'http://' . getenv('CLOUDFRONT_BASE_URL') . '/image.jpg';
$url = getSignedURL($imageURL, 60);

echo "<img src='" . $url . "' />Signed URL Link</a>";

?>
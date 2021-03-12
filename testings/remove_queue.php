<?php
		$curlSession = curl_init();
		curl_setopt($curlSession, CURLOPT_URL, "http://webclinicmailer.co.uk/qmail1.php");
		curl_setopt($curlSession, CURLOPT_HEADER, 0);
		curl_setopt($curlSession, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($curlSession, CURLOPT_TIMEOUT,60); 

		$response = curl_exec ($curlSession);
		
		if (curl_error($curlSession)){
			$output['Status'] = "FAIL";
			$output['StatusDetail'] = curl_error($curlSession);
		}

		curl_close ($curlSession);
		echo $response;
?>

<?php
	$fp = fopen ('finance_test/paypal_hostedresponse.txt','a+');

	 ob_start();
	 echo date("r")."\n================================\n";
	/* PERFORM COMLEX QUERY, ECHO RESULTS, ETC. */
	print_r($_POST);
	
	$content = ob_get_contents();
		ob_end_clean();
		fwrite($fp,$content);
		fclose($fp);
?>
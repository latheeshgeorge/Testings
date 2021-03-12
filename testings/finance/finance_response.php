<?
$fp = fopen ('response.txt','a');

 ob_start();
/* PERFORM COMLEX QUERY, ECHO RESULTS, ETC. */
print_r($_POST);
$content = ob_get_contents();
ob_end_clean();
fwrite($fp,$content);
fclose($fp);


?>

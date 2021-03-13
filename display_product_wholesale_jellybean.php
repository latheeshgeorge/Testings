<?php
	$my_qrystr = str_replace('pass_str=','',$_SERVER['REDIRECT_QUERY_STRING']);
	
	include 'urltarget.php';
	
	$indx = 'wholesale/display_product.php?'.$my_qrystr;
	if($target_array[$indx] and $target_array[$indx]!='/')
		$redirect_url ='http://'.$_SERVER['HTTP_HOST'].'/'.$target_array[$indx];
	else	
		$redirect_url ='http://'.$_SERVER['HTTP_HOST'];
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: $redirect_url");
	exit();
?>

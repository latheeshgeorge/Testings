<?php
$fname = 'vsp_return.txt';
$fp = fopen($fname,'a+');
$order_id = 332222;
$cardtype = 'paypal';
fwrite($fp,"Order Id :$order_id\t Card Type: $cardtype \n");
fclose($fp);
?>
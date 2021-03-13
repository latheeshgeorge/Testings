<?php
$site_where = "site_domain like '".$_SERVER['HTTP_HOST']."' 
						OR site_domain_alias LIKE '".$_SERVER['HTTP_HOST']."'";
?>

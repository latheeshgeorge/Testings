<?php
if($_REQUEST['fpurpose']=='')
{
	include("includes/cpc/list_cpc.php");
}
elseif($_REQUEST['fpurpose']=='list_month') // show state list
{
	include("includes/cpc/list_cpc_month.php");
}		
?>
<?php
if($_REQUEST['fpurpose']=='')
{
	include("includes/costperclick_report/list_summary.php");
}
elseif($_REQUEST['fpurpose']=='list_monthly')
{
	include("includes/costperclick_report/list_monthly.php");
}
elseif($_REQUEST['fpurpose']=='list_clicks')
{
	include("includes/costperclick_report/list_clicks.php");
}
elseif($_REQUEST['fpurpose']=='list_export')
{
	include("includes/costperclick_report/list_export.php");
}

?>
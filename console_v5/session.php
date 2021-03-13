<?php
session_start();
include('sites.php');
if($_SESSION['console_id'] == '' || $_SESSION['site_id'] == '' || $_SESSION['domain'] == '' || ($_SESSION['domain'] != $_SERVER['HTTP_HOST'] && $_SESSION['domain_alias'] != $_SERVER['HTTP_HOST'])) {
	if($ecom_bypass_loggedin_check!=1)
		redirect('index.php','session=true&request='.$_REQUEST['request']);
	else
	{
		
		echo "<form method='post' name='login_redirect_sess_form' id='login_redirect_sess_form' target='_parent'>
				Sorry!! your session expired
			</form>
			<script type='text/javascript'>document.login_redirect_sess_form.action='index.php?session=true';document.login_redirect_sess_form.submit();</script>
			";
		exit;
	}	
}
if($_REQUEST['request'] == 'logout') {
	include('logout.php');
}
?>
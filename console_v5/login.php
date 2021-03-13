<?
session_start();
include_once("functions/functions.php");
if($_POST['login_check'] != '' && $_POST['txtuname'] != '' && $_POST['txtpass'] != '') {
	include('sites.php');//#Getting HTTP_HOST
	include('config.php');
	
	if($ecom_siteid != '') {
		if($ecom_status == 'suspended') {
			echo 'Error! This domain is suspended';
			exit;
		} else if($ecom_status == 'cancelled') {
			echo 'Error! This domain is cancelled';
			exit;
		}
		$username 	= addslashes($_POST['txtuname']);
		$passwd 	= base64_encode(addslashes($_POST['txtpass']));
		// Check whether the length of password is greater than 20. If so then break
		if (strlen($_POST['txtpass'])>20)
		{
			redirect('index.php',$arg = "failure=4");
			exit;
		}
		$sql = "SELECT user_id,user_type,user_active,user_fname,user_lname FROM sites_users_7584 WHERE user_email_9568='$username' AND user_pwd_5124='$passwd' AND sites_site_id=".$ecom_siteid;
		$res = $db->query($sql);
		$row = $db->fetch_array($res);
		if($db->num_rows($res)) {
			//#User is disabled
			if($row['user_active'] == 0) {
				redirect('index.php',$arg = "failure=disabled");
				exit;
			}
			
			$_SESSION['console_id'] 	= $row['user_id'];//#Console user id
			$_SESSION['site_id'] 		= $ecom_siteid;//#Site id
			$_SESSION['domain'] 		= $ecom_hostname;//#Domain name
			$_SESSION['domain_alias'] 		= $ecom_hostname_alias;//#Domain name
			$_SESSION['user_type']		= $row['user_type'];//#User type
			$_SESSION['log_user'] 		= stripslashes($row['user_fname'])." ".stripslashes($row['user_lname']);//#User name
			redirect('home.php','request='.$_REQUEST['request']);
		} else {
			$sql = "SELECT user_id,user_type,user_fname,user_lname FROM sites_users_7584 WHERE user_email_9568='$username' AND user_pwd_5124='$passwd' AND sites_site_id=0";
			$res = $db->query($sql);
			$row = $db->fetch_array($res);
			if($db->num_rows($res)) {
				
				$_SESSION['console_id'] 	= $row['user_id'];//#Console user id
				$_SESSION['site_id'] 		= $ecom_siteid;//#Site id
				$_SESSION['domain'] 		= $ecom_hostname;//#Domain name
				$_SESSION['domain_alias'] 		= $ecom_hostname_alias;//#Domain name
				$_SESSION['user_type'] 		= $row['user_type'];//#User type
				$_SESSION['log_user'] 		= stripslashes($row['user_fname'])." ".stripslashes($row['user_lname']);//#User name
				redirect('home.php','request='.$_REQUEST['request']);
				exit;
			} else {
				redirect('index.php',$arg = "failure=true");
				exit;
			}
		}
	} else {
		echo 'Error! This domain does not exists in our database';
		exit;
	}
} else {
	redirect('index.php',$arg = "failure=true");
	exit;
}
?>
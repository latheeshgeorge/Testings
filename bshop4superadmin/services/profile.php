<?php
if($_REQUEST['fpurpose'] == '') {
	include("includes/admin/profile.php");
} else if($_REQUEST['fpurpose'] == 'update') {
	
	if($_REQUEST['Submit'])
	{
		$txtname	= add_slash($_REQUEST[txtname]);
		$txtuname	= add_slash($_REQUEST[txtuname]);
		$txtconfirm	= add_slash($_REQUEST[txtconfirm]);
		if($txtconfirm)
		{
			$sql = "SELECT user_pwd_5124 FROM sites_users_7584 WHERE user_id=".$_SESSION['user_id'];
			$res = $db->query($sql);
			$row = $db->fetch_array($res);
			$pwd = base64_decode($row['user_pwd_5124']);
			
			$sql_check = "SELECT COUNT(*) as cnt FROM sites_users_7584 WHERE user_email_9568 = '$txtuname' AND user_active=1 AND user_id <> ".$_SESSION['user_id'];
			$res_check = $db->query($sql_check);
			$row_check = $db->fetch_array($res_check);
			if($row_check['cnt'] == 0) {
				if($pwd==$_REQUEST[txtoldpass])
				{
					$txtconfirm_new = trim($txtconfirm);
					$txtconfirm_new = str_replace(' ','_',$txtconfirm_new);
					//$txtconfirm_new = preg_replace("/[^0-9a-zA-Z_]+/", "", $txtconfirm_new);
					if($txtconfirm_new == $txtconfirm) {
						$txtconfirm = base64_encode($txtconfirm);
						$update_array = array();
						$update_array['user_fname'] = $txtname;
						$update_array['user_email_9568'] = $txtuname;
						$update_array['user_pwd_5124'] = $txtconfirm;
						$db->update_from_array($update_array, 'sites_users_7584', 'user_id', $_SESSION['user_id']);
						$erro_msg = 'Successfully Updated';
					} /*else {
						$erro_msg = 'Invalid characters in the Password field. Allowed character are (0-9,a-z,A-Z)';
					}*/
				}
				else
				{
					$erro_msg="Old Password Entered is Wrong";
				}
			} else {
				$erro_msg="Error! Email address already in Use.";
			}
		}
		else
		{
			$update_array = array();
			$update_array['user_fname'] = $txtname;
			$update_array['user_email_9568'] = $txtuname;
			$db->update_from_array($update_array, 'sites_users_7584', 'user_id', $_SESSION['user_id']);
			$erro_msg = 'Successfully Updated';
		}
	}
	
	include("includes/admin/profile.php");
}
?>
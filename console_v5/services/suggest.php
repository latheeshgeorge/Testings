<?php
/*
	#################################################################
	# Script Name 	: view_profile.php
	# Description 	: Action Page for changing the details of the logged in users
	# Coded by 		: ANU
	# Created on	: 13-Jun-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################
	*/	

if($_REQUEST['fpurpose'] == '') {
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";

	include("includes/suggest/add_suggest.php");
} 

elseif($_REQUEST['fpurpose']=='list_feature') // show state list
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/suggest/ajax/suggest_ajax_functions.php');
		show_display_feature_list($_REQUEST['service_id'],$_REQUEST['feature_id']);
}
elseif($_REQUEST['fpurpose']=='insert') // show state list
{
			$ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
		$alert='';
		if($_REQUEST['service']<0) {
			$alert='Enter Service';
		}
			
		
		$fieldRequired = array($_REQUEST['title'],$_REQUEST['email'],$_REQUEST['comments']);
		$fieldDescription = array('Title','E-mail','Comments');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		if(!$alert) 
		{
			$sql = "SELECT user_fname, user_lname 
									FROM sites_users_7584 
											WHERE user_id='".$_SESSION['console_id']."' AND sites_site_id='$ecom_siteid'";
			$res = $db->query($sql);
			$row = $db->fetch_array($res);
			$username = $row['user_fname']." ".$row['user_lname'];								
			
			$insert_array = array();
			$insert_array['sugg_date'] 						=   'now()';
			$insert_array['sites_site_id']     				=	$ecom_siteid;
			$insert_array['sugg_user_id']					= $_SESSION['console_id'];
			$insert_array['sugg_user_name']					= add_slash($_SESSION['log_user']);
			$insert_array['sugg_email']						= add_slash($_REQUEST['email']);
			$insert_array['services_service_id']			= add_slash($_REQUEST['service']);
			$insert_array['features_feature_id']			= add_slash($_REQUEST['feature']);
			$insert_array['sugg_status']					= 'NEW';
			$insert_array['sugg_title']						= add_slash($_REQUEST['title']);;
			$insert_array['sugg_text']						= add_slash($_REQUEST['comments']);
			
			$db->insert_from_array($insert_array, 'console_suggestions');
			$insert_id = $db->insert_id();
			
			 $sql = "SELECT  site_domain FROM sites WHERE site_id='".$ecom_siteid."'";
 		     $res = $db->query($sql);
			 $row = $db->fetch_array($res);
			 $domain = $row['site_domain'];
			
			 $sql = "SELECT service_id, service_name FROM services WHERE service_id='".$_REQUEST['service']."'";
			 $res = $db->query($sql);
			 $row = $db->fetch_array($res);
			 $service = $row['service_name'];

			 $sql = "SELECT feature_id, feature_name FROM features WHERE feature_id='".$_REQUEST['feature']."'";
			 $res = $db->query($sql);
			 $row = $db->fetch_array($res);
			 $feature = $row['feature_name'];

			  
			$to = "randeep.nath@calpinetech.com";
			$subject = "Concole User Suggestion ";
			$message = 
			
			"<TABLE cellSpacing=0 cellPadding=0 width='100%' align=center border=0 valign='center'><TBODY>
  <TR> 
    <TD> 
    <TABLE class=cellbox2 cellSpacing=0 cellPadding=0 width=754 align=center border=0>
              <TR> 
                <TD colSpan=2></TD>
              </TR>
         
              <TR> 
                <TD colspan=2>
                <p align='left'><b>Below is the result of what you've submitted. All this information has been sent to Business 1st Customer Support team to answer your questions and one copy has been sent to you via email for your records.</b>                 </p></TD>
              </TR>
              <TR> 
                <TD><p align='center'>
                &nbsp;&nbsp;
                </p></TD>
                <TD></TD>
              </TR>
              <TR> 
                <TD colspan='2' vAlign=top> &nbsp; SIte Domain : " . $domain . " 
                <BR> <hr></TD>
              </TR>
              ";
              if($_REQUEST['service'] != "") $message .= "
              <TR> 
                <TD colspan='2' vAlign=top><br> &nbsp; 
                 Nature Of the Problem : " . $service . " <hr></TD>
              </TR>
              ";
           if($_REQUEST['feature'] != "") $message .= "
              <TR>
                <TD colspan='2' vAlign=top><br> &nbsp; 
                 Feature : " . $feature . " <hr></TD>
              </TR>
               ";           
              $message .="
              <TR>
                <TD colspan='2' vAlign=top><br> 
               &nbsp;   Email : " . $_REQUEST['email'] . " <hr></TD>
              </TR>
              <TR>
                <TD colspan='2' vAlign=top><br> &nbsp; 
                 Title :  " . $_REQUEST['title'] . " <hr></TD>
              </TR>
              <TR> 
                <TD colspan='2' vAlign=top><p><br> &nbsp; 
                 Please explain in as much detail as you can what the problem is:<br> <br> 
                   " . $_REQUEST['comments'] . " <hr>  </p><hr></TD>
              </TR>       
          </TABLE></TD>
  </TR></TBODY>
</TABLE>";
			
			
			

			/* To send HTML mail, you can set the Content-type header. */
			$headers  = "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
			
			/* additional headers */
			$headers .= "To: ".$to."\r\n";
			$headers .= "From: <".$_REQUEST['email'].">\r\n";
			
			/* and now mail it */
			mail($to, $subject, $message, $headers);
			
			$alert = '<center><font > Suggestion send Sucessfully </font></center>';
		
			unset($_REQUEST);
		}
			include("includes/suggest/add_suggest.php");
}

?>
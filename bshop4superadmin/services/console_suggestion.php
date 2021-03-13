<?php
if($_REQUEST['fpurpose'] == '') {
	include("includes/console_suggestion/list_suggestion.php");
}
elseif($_REQUEST['fpurpose']=='view_suggestion') // show state list
{
	
	$sugg_id = $_REQUEST['sugg_id'];
	include("includes/console_suggestion/view_suggest.php");
}
elseif($_REQUEST['fpurpose']=='sendmail') 
{
	$reply_title = $_REQUEST['reply_title'];
	$reply_message = $_REQUEST['reply_message'];
	$sugg_id = $_REQUEST['sugg_id'];
	
	$sql = "SELECT sugg_email FROM console_suggestions WHERE sugg_id='$sugg_id'";
	$res = $db->query($sql);
	$row = $db->fetch_array($res);
	$email = $row['sugg_email'];
	
			$to = $email;
			$subject = "Suggestion Reply ";
			$message = "Title :- '".$_REQUEST['reply_title']."'";
			$message .= "Message :- '".$_REQUEST['reply_message']."'";
			

			/* To send HTML mail, you can set the Content-type header. */
			$headers  = "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
			
			/* additional headers */
			$headers .= "To: ".$to."\r\n";
			$headers .= "From: <info@bshop4.co.uk>\r\n";
			
			/* and now mail it */
			mail($to, $subject, $message, $headers);
			
			$msg = "<center><font > Mail Send Sucessfully </font></center>";
			include("includes/console_suggestion/view_suggest.php");
			
	
}
elseif($_REQUEST['fpurpose']=='statuschange') // show state list
{
	
	$sugg_id = $_REQUEST['sugg_id'];
	
	$update_array					= array();
	$update_array['sugg_status']	= add_slash($_REQUEST['sugg_status']);
	$db->update_from_array($update_array,'console_suggestions',array('sugg_id'=>$sugg_id));
	
	$msg = "<center><font > Status Changed Sucessfully </font></center>";
	include("includes/console_suggestion/view_suggest.php");
}
 
 elseif($_REQUEST['fpurpose']=='delete') // show state list
{
	
	$sugg_id = $_REQUEST['sugg_id'];
	
	$sql = "DELETE FROM console_suggestions WHERE sugg_id='$sugg_id'";	
	$res = $db->query($sql);
	$error_msg = "<center><font > Suggestion Deleted Sucessfully </font></center>";
	include("includes/console_suggestion/view_suggest.php");
}

?>
<?
	//define('ORG_DOCROOT','/var/www/html/webclinic/bshop4'); // Local
	define('ORG_DOCROOT','/var/www/vhosts/bshop4.co.uk/httpdocs'); // Live
	
	
	$required_domain 	= 'www.discount-mobility.co.uk';
	$copy_file_name		= '911703.txt';
	
	
	$src_folder 		= ORG_DOCROOT."/testings/copy_customers/order_email_changes/tochangefiles";
	$dest_folder 		= ORG_DOCROOT."/images/".$required_domain."/email_messages/order_emails"; 
	
	
	
	$src_file_path		= $src_folder."/".$copy_file_name;
	$des_file_path		= $dest_folder."/".$copy_file_name;
	
	/* Check whether source file exists */
	if(file_exists($src_file_path))
	{
		if(!copy($src_file_path,$des_file_path))
		{
			echo "<br><br>Sorry an error occured";
		}
		else
		{
			echo "<br><br>$src_file_path <br> <br> Successfully Copied To <br><br> $des_file_path ";
		}
	}
	else
	{
		echo "Sorry!! $src_file_path Does not exists";
		exit;
	}			
	
?>

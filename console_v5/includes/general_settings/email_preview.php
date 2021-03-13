<?php
	/*#################################################################
	# Script Name 	: add_newsletter.php
	# Description 	: Page for adding Newsletter
	# Coded by 		: ANU
	# Created on	: 29-Aug-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = '';
$email_id = $_REQUEST['email_id'];
$help_msg = get_help_messages('PREVIEW_EMAIL_MESS1');

	$tabale = "general_settings_sites_mail_inactivecustomers";
	$where  = "sites_site_id=".$ecom_siteid;
	if(!server_check($tabale, $where)) {
		echo " <font color='red'> You Are Not Authorised  </a>";
		exit;
	}

$emailssql = "SELECT id, email_subject, email_content, preview_email_subject, preview_email_content  
				   FROM general_settings_sites_mail_inactivecustomers 
				   		WHERE sites_site_id=".$ecom_siteid." AND id=".$email_id;
$emailres = $db->query($emailssql);
$emailrow = $db->fetch_array($emailres);
if($_REQUEST['fpurptype']=='save')
{
$preview_title   = $emailrow['preview_email_subject'];
$preview_content = $emailrow['preview_email_content'];
$template        = $preview_content;
}
else
{
	 if($_REQUEST['nextdo']=='preview')
	 {
	   $preview_title 	= $emailrow['preview_email_subject'];
	   $preview_content = $emailrow['preview_email_content'];
	   $template        = $preview_content;
	 }
	 else if($_REQUEST['nextdo']=='savec')
	 {	
		$preview_title = $emailrow['email_subject'];
		$preview_content = $emailrow['email_content'];
		$template        = $preview_content;
 
 
/*
$templateid = $newsrow['id'];

if($templateid > 0) { // To get Template Product layout FROM Newsletter Template Table
	$tempsql = "SELECT product_layout 
						FROM newsletter_template 
							 WHERE newstemplate_id ='".$templateid."'";
	$tempres = $db->query($tempsql);
	$temprow = $db->fetch_array($tempres);						 
	$productlayout = $temprow['product_layout'];
} else {
	$tempsql = "SELECT template_product_layout 
						FROM sites 
							 WHERE site_id ='".$ecom_siteid."'";
	$tempres = $db->query($tempsql);
	$temprow = $db->fetch_array($tempres);						 
	$productlayout = $temprow['template_product_layout'];						 
}

*/	
$tempsql = "SELECT template_product_layout 
						FROM sites 
							 WHERE site_id ='".$ecom_siteid."'";
	$tempres = $db->query($tempsql);
	$temprow = $db->fetch_array($tempres);						 
	$productlayout = $temprow['template_product_layout'];	


$prodsql = "SELECT products_product_id
						 FROM general_settings_sites_mail_product_map 
						 		WHERE product_inactive_mail_id='".$email_id."'
						 		AND product_sites_site_id ='".$ecom_siteid."'
						 		ORDER BY product_order ASC";
$prodres = $db->query($prodsql);
$prodnum = $db->num_rows($prodres);
if($prodnum > 0) 
	{ 
 		if($productlayout=='')
        {
		   $alert = "Product default layout not set !!!";
		}	  
			//$prodcontent = "<table width='100%' border='0'>";
			  while($prodrow = $db->fetch_array($prodres)) { 
				$count+=1;
				$imagsql     = "SELECT  image_thumbpath 
				                      FROM images a, images_product b 
									                 WHERE a.image_id=b.images_image_id 
													   AND b.products_product_id = '".$prodrow['products_product_id']."' 
													   AND a.sites_site_id = '".$ecom_siteid."'
													   		ORDER BY b.image_order ASC
													     ";
				$imagres      = $db->query($imagsql);
				$imagrow      = $db->fetch_array($imagres);
				$images       = $imagrow['image_thumbpath'];
				
				
				
				
				$prodnamesql = "SELECT product_name, product_shortdesc, product_webprice, product_discount, product_discount_enteredasval, 
									   product_bulkdiscount_allowed 
									 			FROM products 
													 WHERE product_id='".$prodrow['products_product_id']."'";
				$prodnameres = $db->query($prodnamesql);
				$prodnamerow = $db->fetch_array($prodnameres);
				$rate = 0;
				if($prodnamerow['product_discount']>0)
				{
					switch($prodnamerow['product_discount_enteredasval']) 
					{
						case '0' :
							$rate =  $prodnamerow['product_webprice'] - ($prodnamerow['product_webprice']*$prodnamerow['product_discount']/100);
						break;
						case '1' :
						    $rate =  $prodnamerow['product_webprice'] - $prodnamerow['product_discount'];
						break;
						case '2' :
							$rate =  $prodnamerow['product_discount'];		
						break;
						default :
							$rate = $prodnamerow['product_webprice'];
					}
				}	
				/*$link = "<a href=\"http://".$ecom_hostname."/p".$prodrow['products_product_id']."/".strip_url($prodnamerow['product_name']).".html\" style=\"border: 0pt none ; text-decoration: none;\">
					<img src='http://".$ecom_hostname."/images/".$ecom_hostname."/static/more_newsletter.gif'   border='0'/></a>";*/
				
				$link = "http://".$ecom_hostname."/p".$prodrow['products_product_id']."/".strip_url($prodnamerow['product_name']).".html";				 

				if(trim($images)) {
					$imgname = "<a href=\"http://".$ecom_hostname."/p".$prodrow['products_product_id']."/".strip_url($prodnamerow['product_name']).".html\">
					<img src='http://".$ecom_hostname."/images/".$ecom_hostname."/".$images."' border='0'/></a>";					 
				} else { 
					$imgname = "<a href=\"http://".$ecom_hostname."/p".$prodrow['products_product_id']."/".strip_url($prodnamerow['product_name']).".html\"><img src='http://".$ecom_hostname."/images/".$ecom_hostname."/site_images/no_small_image.gif' border='0'/></a>";
				}
					$productlayouttem =	str_replace('[IMG]',$imgname,$productlayout);
					$prodname 	   =    add_slash($prodnamerow['product_name']);
					//$prodname_link =   "<a href=\"http://".$ecom_hostname."/p".$prodrow['products_product_id']."/".strip_url($prodnamerow['product_name']).".html\" style=\"text-decoration: none; font-size: 12px; color: rgb(102, 102, 102); font-weight: bold;\">".$prodname."</a>";
					$prodname_link =   "http://".$ecom_hostname."/p".$prodrow['products_product_id']."/".strip_url($prodnamerow['product_name']).".html";
					$prodshortdesc =    $prodnamerow['product_shortdesc'];
					$org_rate = $rate;
					$ret_rate      =  display_price($prodnamerow['product_webprice']);
					$rate 	   	   	=    display_price($rate);
					$anyprice = 0;
					//echo "original rate".$org_rate;
					if($org_rate)
						$anyprice = display_price($org_rate);
					else
						$anyprice = $ret_rate;
					
					$productlayouttem =	str_replace('[TITLE]',$prodname,$productlayouttem);
					$productlayouttem =	str_replace('[DESCRIPTION]',$prodshortdesc,$productlayouttem);
					$productlayouttem =	str_replace('[PRICE]',$rate,$productlayouttem);
					$productlayouttem =	str_replace('[RET_PRICE]',$ret_rate,$productlayouttem);
					$productlayouttem =	str_replace('[ANY_PRICE]',$anyprice,$productlayouttem);
					$productlayouttem =	str_replace('[LINK]',$link,$productlayouttem);

					$productlayoutdesign .=  	$productlayouttem;

			}
			
		$template =	str_replace('[Products]',$productlayoutdesign,$template);
	}
}
}
						

?>	
<script language="javascript" type="text/javascript">

function valform(frm,mod)
{	
	fieldRequired = Array('preview_email_subject');
	fieldDescription = Array('Subject','Content');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	var reqcnt = fieldRequired.length;
	
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		
		show_processing();
		frm.fpurpose.value='save_preview';
		if(mod=='save')
			frm.fpurptype.value='save';
		else 
			frm.fpurptype.value='savec';	
		frm.submit();
	} else {
		return false;
	}
}
</script>
<form name='frmPreviewEmail' action='home.php?request=general_settings'  method="post" >
  	  
  <table width="100%" border="0" cellspacing="2" cellpadding="2">
        <tr>
          <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="#">General Settings </a>
		   <a href="home.php?request=general_settings&fpurpose=sent_email_inactive&curtab=email_tab_td"> Email To Inactive Customers </a>  <span>Email Preview</span></div>
		  </td>
        </tr>
      <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="3">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<tr>
			<td><div class="editarea_div">
  <table width="100%" border="0" cellspacing="2" cellpadding="2">	
		<?php 
		if($alert)
		{			
		?>
        <tr>
          <td colspan="3" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
          <tr>
          <td width="17%" align="left" valign="middle" class="tdcolorgray" >Email  Subject <span class="redtext">*</span> </td>
          <td width="47%" align="left" valign="middle" class="tdcolorgray"><input name="preview_email_subject" type="text" id="preview_email_subject" value="<?=$preview_title?>" size="75" /></td>
        </tr>
		 <tr>
		   <td colspan="3" align="left" valign="middle" class="tdcolorgray" >EmailContents <span class="redtext">*</span></td>
    </tr>
		 <tr>
		   <td colspan="3" align="left" valign="top" class="tdcolorgray" style="padding-left:25px;" ><?php 
		     			$editor_elements = "preview_email_content";
						include_once("js/tinymce.php");
						/*
						include_once("classes/fckeditor.php");
						$editor 			= new FCKeditor('newsletter_contents') ;
						$editor->BasePath 	= '/console/js/FCKeditor/';
						$editor->Width 		= '850';
						$editor->Height 	= '500';
						$editor->ToolbarSet = 'BshopWithImages';
						$editor->Value 		= stripslashes($template);
						$editor->Create() ;*/
				       
		?>
		<textarea style="height:500px; width:850px" id="preview_email_content" name="preview_email_content"><?=stripslashes($template)?></textarea>
		</td>
         </tr>
		 <tr>
           <td colspan="2" align="left" valign="middle" class="tdcolorgray" ><!--  <select name="group_position[]" multiple="multiple">
		  </select>-->           </td>
    </tr>
		 
	    
  </table>
    
    </div>
    </td>
    </tr>
    <tr>
	<td>
  	<div class="editarea_div">
  <table width="100%" border="0" cellspacing="2" cellpadding="2">
	  <tr>
          <td colspan="3" align="right" valign="middle" class="tdcolorgray" >
		   <input type="hidden" name="email_id" id="email_id" value="<?=$_REQUEST['email_id']?>" />
		   <input type="hidden" name="fpurpose" id="fpurpose" value=""  />
		    <input type="hidden" name="fpurptype" id="fpurptype"  />
		   <input name="Submit" type="button" class="red" value=" Save " onclick="valform(frmPreviewEmail,'save')" />
		   <a href="#" onmouseover="ddrivetip('<?=get_help_messages('EDIT_EMAIL_PREV_SAVE')?>')" ;="" onmouseout="hideddrivetip()"><img src="images/helpicon.png" border="0" height="13" width="17" /></a>		   
		   <input name="Submit" type="button" class="red" value=" Save & Continue " onclick="valform(frmPreviewEmail,'savec')" />
		   <a href="#" onmouseover="ddrivetip('<?=get_help_messages('EDIT_EMAIL_PREV_SAVECNT')?>')" ;="" onmouseout="hideddrivetip()"><img src="images/helpicon.png" border="0" height="13" width="17" /></a></td>
        </tr>
	  </table>
	  </td>
	  </tr>	  
    </table>
</form>	

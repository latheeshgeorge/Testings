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
$page_type = 'News Letter';
$newsletter_id = $_REQUEST['newsletter_id'];
$help_msg = get_help_messages('PREVIEW_NEWSLETTER_MESS1');

	$tabale = "newsletters";
	$where  = "newsletter_id=".$newsletter_id;
	if(!server_check($tabale, $where)) {
		echo " <font color='red'> You Are Not Authorised  </a>";
		exit;
	}

$newssql = "SELECT newsletter_template_id, newsletter_title, newsletter_contents, preview_title, preview_contents 
				   FROM newsletters 
				   		WHERE newsletter_id=".$newsletter_id;
$newsres = $db->query($newssql);
$newsrow = $db->fetch_array($newsres);
$templateid = $newsrow['newsletter_template_id'];

//(trim($newsrow['newsletter_contents']) && $newsrow['newsletter_contents'] != '&nbsp;' )?$template = $newsrow['newsletter_contents']:$template = $newsrow['preview_contents'];
if($_REQUEST['newspreview']=='preview')	{
	$template = $newsrow['newsletter_contents'];
	$preview_title = $newsrow['newsletter_title'];
} else {
	$template = $newsrow['preview_contents'];
	$preview_title = $newsrow['preview_title'];
}
//$template = $newsrow['preview_contents'];


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
	


$prodsql = "SELECT products_product_id
						 FROM newsletter_products 
						 		WHERE newsletters_newsletter_id='".$newsletter_id."'";
$prodres = $db->query($prodsql);
$prodnum = $db->num_rows($prodres);
if($prodnum > 0) 
	{
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
						
					$yousaveprice = 0;		
					if($org_rate>0)
						$yousaveprice = ($prodnamerow['product_webprice']-$org_rate);
					if($yousaveprice<0)
						$yousaveprice = 0;	
					$yousaveprice = display_price($yousaveprice);			
					
					$productlayouttem =	str_replace('[TITLE]',$prodname,$productlayouttem);
					$productlayouttem =	str_replace('[DESCRIPTION]',$prodshortdesc,$productlayouttem);
					$productlayouttem =	str_replace('[PRICE]',$rate,$productlayouttem);
					$productlayouttem =	str_replace('[RET_PRICE]',$ret_rate,$productlayouttem);
					$productlayouttem =	str_replace('[ANY_PRICE]',$anyprice,$productlayouttem);
					$productlayouttem =	str_replace('[YOUSAVE]',$yousaveprice,$productlayouttem);
					$productlayouttem =	str_replace('[LINK]',$link,$productlayouttem);

					$productlayoutdesign .=  	$productlayouttem;

			}
			
		
		$template =	str_replace('[Products]',$productlayoutdesign,$template);
	}

						

?>	
<script language="javascript" type="text/javascript">

function valform(frm,mod)
{
	/*fieldRequired = Array('newsletter_title','newsletter_contents' );
	fieldDescription = Array('News letter Title','News letter contents');*/
	
	fieldRequired = Array('newsletter_title');
	fieldDescription = Array('News letter Title');
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
			frm.fpurptype.value='save_cont';	
		frm.submit();
	} else {
		return false;
	}
}
function tempale_change() 
{
	document.frmPreviewNewsletter.submit();
}
</script>
<form name='frmPreviewNewsletter' action='home.php?request=newsletter'  method="post" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=newsletter&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Newsletter </a> <a href="home.php?request=newsletter&fpurpose=edit&newsletter_id=<?=$newsletter_id?>"> Edit Newsletter </a> <a href="home.php?request=newsletter&fpurpose=prodnewsletter&newsletter_id=<?=$newsletter_id?>"> Assigned Products </a><span> Preview Newsletter</span></div></td>
        </tr>
      <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="3">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<tr>
          <td colspan="5" align="left" valign="middle" > <?PHP echo newsletter_tabs('preview_tab_td',$newsletter_id) ?></td>
      </tr>		
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
          <td colspan="3" align="center" valign="middle" class="tdcolorgray" >
		  <div class="editarea_div">
      	<table  border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >News Letter  Subject <span class="redtext">*</span> </td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray"><input name="newsletter_title" type="text" id="newsletter_title" value="<?=$preview_title?>" size="75" /></td>
        </tr>
		 <tr  >
		   <td colspan="3" align="left" valign="middle" class="tdcolorgray" >News LetterContents <span class="redtext">*</span></td>
    </tr>
		 <tr>
		   <td colspan="3" align="left" valign="top" class="tdcolorgray"><?php 
		     			$editor_elements = "newsletter_contents";
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
		<textarea style="height:500px; width:850px" id="newsletter_contents" name="newsletter_contents"><?=stripslashes($template)?></textarea>
		</td>
         </tr>
		 <tr>
           <td colspan="2" align="left" valign="middle" class="tdcolorgray" ><!--  <select name="group_position[]" multiple="multiple">
		  </select>-->           </td>
    </tr>
		 <tr class="listingarea" >
		 <td colspan="3" align="left" valign="middle" class="tdcolorgray" ><table width="70%" border="0" class="listingtable">
             <tr >
               <td colspan="3" class="helpmsgtd" align="left"><div class="helpmsg_divcls"><?=get_help_messages('NEWSLETTER_CODE_REPLACE')?></div></td>
             </tr>
             <tr class="listingtableheader">
               <td width="17%"><div align="left"><strong>&nbsp; Code</strong></div></td>
               <td width="5%">&nbsp;</td>
               <td width="78%"><div align="left"><strong>&nbsp; Description</strong></div></td>
             </tr>
             <?PHP 
			 	foreach($templatecode AS $key=>$val) {
			 ?>
             <tr class="listingtablestyleB">
               <td align="left" > &nbsp; <?PHP echo $val; ?></td>
               <td>=&gt;</td>
               <td align="left">&nbsp; <?PHP echo $key; ?></td>
             </tr>
             <?PHP } ?>
           </table></td>
    </tr>
	</table>
	</div>
	</td>
	</tr>
	<tr>
		<td colspan="3" align="right" valign="middle" class="tdcolorgray" >
			<div class="editarea_div">
				<table  border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td align="right" valign="middle">
						<input type="hidden" name="newsletter_id" id="newsletter_id" value="<?=$_REQUEST['newsletter_id']?>" />
						<input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
						<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
						<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
						<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
						<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
						<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
						<input type="hidden" name="fpurpose" id="fpurpose" value=""  />
						<input type="hidden" name="fpurptype" id="fpurptype"  />
						<input name="Submit" type="button" class="red" value=" Save " onclick="valform(frmPreviewNewsletter,'save')" />		   
						<input name="Submit" type="button" class="red" value=" Save & Continue " onclick="valform(frmPreviewNewsletter,'savec')" />
					</td>
				</tr>
				</table>
			</div>
		</td>
	</tr>
  </table>
</form>	  
<script type="text/javascript">
	handletype_change('');
</script>

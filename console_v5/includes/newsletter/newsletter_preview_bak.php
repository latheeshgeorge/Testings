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
$help_msg = get_help_messages('ADD_NEWSLETTER_MESS1');

$newssql = "SELECT newsletter_title, newsletter_contents, preview_title, preview_contents 
				   FROM newsletters 
				   		WHERE newsletter_id=".$newsletter_id;
$newsres = $db->query($newssql);
$newsrow = $db->fetch_array($newsres);
$template = $newsrow['preview_contents'];

$prodsql = "SELECT products_product_id
						 FROM newsletter_products 
						 		WHERE newsletters_newsletter_id='".$newsletter_id."'";
$prodres = $db->query($prodsql);
$prodnum = $db->num_rows($prodres);
if($prodnum > 0) 
	{
			$prodcontent = "<table width='100%' border='0'>";
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
				if(trim($images)) {
					$imgname = "<img src='http://".$ecom_hostname."/images/".$ecom_hostname."/".$images."' border='0'/>";					 
				} else { 
					$imgname = "<img src='http://".$ecom_hostname."/images/".$ecom_hostname."/site_images/no_small_image.gif' border='0'/>";
				}
				
				$prodnamesql = "SELECT product_name, product_shortdesc, product_webprice, product_discount, product_discount_enteredasval, 
									   product_bulkdiscount_allowed 
									 			FROM products 
													 WHERE product_id='".$prodrow['products_product_id']."'";
				$prodnameres = $db->query($prodnamesql);
				$prodnamerow = $db->fetch_array($prodnameres);
				
				if($prodnamerow['product_bulkdiscount_allowed']=='Y') {
				    
					switch($prodnamerow['product_discount_enteredasval'])  {
						case '0' :
							$rate =  $prodnamerow['product_webprice'] - ($prodnamerow['product_webprice']*$prodnamerow['product_discount']/100);
						case '1' :
						    $rate =  $prodnamerow['product_webprice'] - $prodnamerow['product_discount'];
						case '2' :
							$rate =  $prodnamerow['product_discount'];		
					}
				}	
					 
					$prodcontent .= "<tr><td width='42%' >".$prodnamerow['product_name']."<br/>".
					$prodnamerow['product_shortdesc'].
					"</td>
						<td width='33%' >".$imgname."</td>
						<td width='25%' >".$rate."</td></tr>";
			}
			$prodcontent .= "</table>";
		
		$template =	str_replace('[Products]',$prodcontent,$template);
	}

						

?>	
<script language="javascript" type="text/javascript">

function valform(frm,mod)
{
	fieldRequired = Array('newsletter_title','newsletter_contents' );
	fieldDescription = Array('News letter Title','News letter contents');
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
          <td colspan="3" align="left" valign="middle" class="treemenutd"><a href="home.php?request=newsletter&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Newsletter </a> >>
		   <a href="home.php?request=newsletter&fpurpose=edit&newsletter_id=<?=$newsletter_id?>"> Edit Newsletter </a> &gt;&gt;  <a href="home.php?request=newsletter&fpurpose=prodnewsletter&newsletter_id=<?=$newsletter_id?>"> Assigned Products </a>
		  &gt;&gt; Preview Newsletter </td>
        </tr>
        <tr>
          <td colspan="3" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=$help_msg ?></div></td>
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
          <td width="17%" align="left" valign="middle" class="tdcolorgray" >News Letter  Title <span class="redtext">*</span> </td>
          <td width="47%" align="left" valign="middle" class="tdcolorgray"><input name="newsletter_title" type="text" id="newsletter_title" value="<?=$newsrow['preview_title']?>" size="75" /></td>
        </tr>
		 <tr  >
		   <td colspan="3" align="left" valign="middle" class="tdcolorgray" >News LetterContents <span class="redtext">*</span></td>
    </tr>
		 <tr>
		   <td colspan="2" align="left" valign="top" class="tdcolorgray" style="padding-left:25px;" ><?php 
		     	
						include_once("classes/fckeditor.php");
						$editor 			= new FCKeditor('newsletter_contents') ;
						$editor->BasePath 	= '/console/js/FCKeditor/';
						$editor->Width 		= '650';
						$editor->Height 	= '400';
						$editor->ToolbarSet = 'BshopWithImages';
						$editor->Value 		= stripslashes($template);
						$editor->Create() ;
				       
		?></td>
           <td width="36%" align="left" valign="top" class="tdcolorgray" ><table width="100%" border="0">
             <tr>
               <td>&nbsp;</td>
               <td>&nbsp;</td>
             </tr>
             <tr>
               <td><div align="left"><strong>Code</strong></div></td>
               <td><div align="left"><strong>Description</strong></div></td>
             </tr>
             <?PHP 
			 	foreach($templatecode AS $key=>$val) {
				if($key != 'Products') {
			 ?>
             <tr>
               <td><?PHP echo $key; ?></td>
               <td><?PHP echo $val; ?></td>
             </tr>
             <?PHP }
			  } ?>
           </table></td>
    </tr>
		 <tr>
           <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
		   <td align="left" valign="middle" class="tdcolorgray"><!--  <select name="group_position[]" multiple="multiple">
		 
		  <?
		
	   	  ?>

		  </select>-->           </td>
    </tr>
		 
		 
		 
		<tr>
          <td colspan="3" align="center" valign="middle" class="tdcolorgray" >
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
		   <input name="Submit" type="button" class="red" value=" Save & Continue " onclick="valform(frmPreviewNewsletter,'savec')" /></td>
        </tr>
  </table>
</form>	  
<script type="text/javascript">
	handletype_change('');
</script>

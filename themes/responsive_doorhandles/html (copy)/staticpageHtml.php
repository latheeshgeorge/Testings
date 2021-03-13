<?php
	/*############################################################################
	# Script Name 	: staticpageHtml.php
	# Description 	: Page which holds the display logic for Static pages
	# Coded by 		: Anu
	# Created on	: 22-Feb-2008
	# Modified by	: ANU
	# Modified On	: 22-Feb-2008
	##########################################################################*/
	class static_Html
	{
		// Defining function to show the selected static pages
		function Show_StaticPage($row_statpage)
		{
			global 	$Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,
					$ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr;
			// ** Fetch any captions for product details page
			$Captions_arr['STATIC_PAGES'] 	= getCaptions('STATIC_PAGES');
			
			// ** Fetch the product details
			//$row_statpage	= $db->fetch_array($ret_statpage);
			
			// ** Check to see whether current user is logged in 
			$cust_id 	= get_session_var("ecom_login_customer");
			$HTML_img = $HTML_alert = $HTML_treemenu='';
		
			$HTML_treemenu = ' <div class="breadcrumbs">
				<ol class="breadcrumb"><li><a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
				 <li>'.stripslash_normal($row_statpage['title']).'</li>
				</ol>
			</div>';
				echo $HTML_treemenu;
			?>
					<div class="container">

			<?php	
			if($row_statpage['content']!='')
			{
		?>
		<form method="post" name="frm_staticpage" id="frm_staticpage" action="" class="frm_cls">
		<input type="hidden" name="fpurpose" value="" />
		<input type="hidden" name="fproduct_id" value="<?php echo $_REQUEST['product_id']?>" />
				<div class="row-container">

			<table border="0" cellpadding="0" cellspacing="0" class="statictable">
			<tr>
			  <td valign="top"  <?php echo ($row_statpage['page_id']==50421 or $row_statpage['page_id']==666)?'class="static_newclstd"':''?>>
			  <?php
			 if($row_statpage['page_id']==50421 or $row_statpage['page_id']==666)
			 {
			?>
				<p>Unipad has been offering a new level of Student Housing in Lancaster since 2008. Our philosophy is to offer you a home from home. Gone are the days where students have to put up with mediocre standards in housing, as all our properties are built or renovated to the highest standards, often far exceeding student expectations.</p>

<p>We believe in attention to detail and use of only the best fixtures and fittings. Students can now choose a property which lives up to the needs of the more sophisticated individual. With 24/7 maintenance and a dedicated support team to deal with any issues that may occur during your tenancy, you can be assured that our customer service is as good as our fantastic student properties in Lancaster.</p>

<p>The fact that our homes are often let 12 months in advance, is a testimony of the passion we have for providing you with a fabulous home.</p>

<h3>Demand For Our Homes has never been higher</h3>

<p>Student accommodation is very important to those hoping to move away during their time studying at university. This is why we at Unipad are dedicated to providing only luxury student homes to those in need of it and this certainly true of people seeking student housing in Lancaster. With both Lancaster University and the University of Cumbria on location, there's sure to be no shortage of people hoping to find the best housing possible.</p>

<h3>Choosing the right student property for you</h3>
<p>When it comes to student housing in Lancaster, we can offer a wide range of properties that are sure to appeal to you. Whether you're planning to rent alone, with a friend, or with a group, there's bound to be a place that we can provide you with. You can rest assured that we make sure all our properties are of the utmost quality for your satisfaction.
On offer through us in terms of our Lancaster student housing, are bed pads that go up to nine bedrooms. They all differ in what they can provide, but there is an extensive range as to the luxuries on offer. These include luxury bedrooms with ensuites, Flatscreen TV's, Fibre Broadband, 24/7 maintenance and much more. This is to guarantee you the best possible stay.</p>

<p>If you browse through the options, you can see that it even tells you whether you will be in the city centre and, if not, how long it will take to get there by walking. We understand how important it is for you to be near the action, which is why we include the detail so that some of your decisions can come down to this factor, if necessary.
Guaranteeing yourself quality accommodation isn't easy, but our pads are definitely some of the most stylish and best valued ones when it comes to student housing in Lancaster. With the offer of wall-mounted plasma TVs, en-suite bathrooms, Sky TV, Cleaning service and other such luxury qualities, there will be nowhere better for you than a place that we can provide.</p>


<h3>Why you should choose student housing through Unipad</h3>

<p>By choosing your student housing in Lancaster through Unipad, you are assuring yourself that you will be staying at a luxury residence during your time at University. This is one of the most important times in your life and you deserve to have all the comforts and amenities at your disposal during it, which is why Unipad is the way forward.</p>

<p>Not only do all these pads come with a pledge of quality, but they are also on offer to you at a very generous price per week. You won't find student housing in Lancaster that measures up to what is presented here, so you should be taking this into thoughtful consideration.</p>



			<?php	 
			 }
			 else if($row_statpage['page_id']==667 or $row_statpage['page_id']==50257)
			 {
			 ?>
			 <div class="row-container">

			<table class="statictable" border="0" cellpadding="0" cellspacing="0">
			<tbody><tr>
			  <td valign="top">
			  <table style="width: 100%;" border="0" cellpadding="1" cellspacing="1">
<tbody>
<tr>

<td style="vertical-align: top;" align="left">
<span><span style="font-size:13px; padding:10px; display:block;">How To Contact Unipad - Luxury Student Housing Lancaster</span></span></td>
</tr>
<tr>

<td style="vertical-align: top;background:#eee;font-size:12px;fontweight:normal; padding:0px 0 0 10px" align="left" width="84%"><span style="font-weight: bold; font-size: 16px; padding-top:10px;">Call Unipad on: 01524 888880</span><br> <span style="font-weight: normal; font-size: 13px;">Mon, Tues, Weds, Thurs, Fri </span><br>
<p style="font-size: 13px; font-weight: normal;">10:00am - 5:00pm</p>

<p style="font-size: 13px; font-weight: normal;"><span style="font-weight: normal; font-size: 13px;">After Hours :&nbsp;<a title="07872­377266"><strong>07872 377266 or 07931748204</strong></a></span></p></td>
</tr>

<tr>

<td style="font-size: 13px; vertical-align: top;font-weight:normal;background:#eee;padding:0px 0px 0px 10px" align="left" valign="top">
<span style="font-size: 13px;font-weight:bold;"><strong style="font-weight:bold;">Visiting Unipad:</strong></span>


<p>Unipad Student Housing Lancaster<br>
Unit 7<br> Kings Arcade<br> King Street <br> Lancaster <br> LA1 1LE</p>

<p><span style="font-size: 13px;">Unipad Offices are located inside Kings Arcade, adjacent to the Middle Street entrance.</span></p>
<p style=":padding-top:20px;display:block;width:200px%;&quot;"><span style="font-size:13px; display:block; background:#000;display:block; width:90%; color:#fff;padding:10px; color:#fff; float:left; display:block;"> <a style=":color#fff;&quot;" title="Find Unipad Student Housing Lancaster" href="https://www.google.co.uk/maps/dir/''/unipad+lancaster/@54.0472837,-2.8719814,12z/data=!3m1!4b1!4m8!4m7!1m0!1m5!1m1!1s0x487b627e9a55ebc1:0x250dd51108c08982!2m2!1d-2.801942!2d54.047304" target="_blank"><strong style="float:left;color:#C60; padding-top:-8px; display:block; ">Click Here</strong></a> &nbsp;To Find Unipad On Google Maps.</span></p>
</td>
</tr>
<tr>

</tr>
<tr>
<td colspan="2" align="left" valign="middle">
<table style="width: 100%;" border="0">
<tbody>
<tr>

<td style="font-size: 13px; font-weight: bold; vertical-align: middle; padding:10px;" align="left" valign="middle" width="84%"><strong>Email Unipad: <a href="mailto:enquiries@unipad.co.uk" style="color:#C60;">enquiries@unipad.co.uk</a><br></strong></td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<script type="text/javascript" language="javascript">// <![CDATA[
function valContact()
{
	var frm		=	document.frm_staticpage;
	submitform	=	1;
	var emailVal = frm.txt_email.value;
	
	if(frm.txt_name.value == "")
	{
		alert("Please Enter Name");
		frm.txt_name.focus();
		submitform	=	0;
		return false;
	}
	
	if(emailVal == "")
	{
		alert("Please Enter Email Id");
		frm.txt_email.focus();
		submitform	=	0;
		return false;
	}
	else if ((emailVal.indexOf('@') < 0) || ((emailVal.charAt(emailVal.length-4) != '.') && (emailVal.charAt(emailVal.length-3) != '.'))) 
	{
		alert("Please Enter Correct Email Id");
		frm.txt_email.focus();
		submitform	=	0;
		return false;
	}
	
	if(frm.txt_phone.value == "")
	{
		alert("Please Enter Phone Number");
		frm.txt_phone.focus();
		submitform	=	0;
		return false;
	}
	
	if(frm.cbo_location.value == "")
	{
		alert("Please Enter Location");
		frm.cbo_location.focus();
		submitform	=	0;
		return false;
	}
	
	if(frm.cbo_roomtype.value == "")
	{
		alert("Please Enter Room Type");
		frm.cbo_roomtype.focus();
		return false;
		return false;
	}
	
	if(frm.txt_comments.value == "")
	{
		alert("Please Enter Comments");
		frm.txt_comments.focus();
		submitform	=	0;
		return false;
	}
	if(submitform == 1)
		frm.submit();
	else
		return false;
}
// ]]></script>
<table class="formtable" style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
<tbody>
<tr class="formtable_tda">
<td class="page_heading_cls" colspan="3" align="left">&nbsp;</td>
</tr>
<tr class="formtable_tda">
<td style="font-size: 13px; font-weight:normal;" align="left" valign="middle">&nbsp;</td>
<td style="font-size: 13px; font-weight:bold;" colspan="2" align="left" valign="middle"><strong style="font-size: 13px; font-weight:bold;">Name:<span> * </span></strong></td>
</tr>
<tr class="formtable_tdb">

<td width="3%" height="56" align="right" valign="middle">&nbsp;</td>
<td align="left" valign="middle" width="81%"><input class="required form-control" name="txt_name" id="txt_name" type="text" style="font-size: 13px; font-weight:normal;"></td>
</tr>
<tr class="formtable_tdb">
<td style="font-size: 13px; font-weight:normal;" align="left" valign="middle">&nbsp;</td>
<td style="font-size: 13px; font-weight:normal;" colspan="2" align="left" valign="middle"><strong style="font-size: 13px; font-weight:bold;">Email Address:</strong><span> * </span></td>
</tr>
<tr class="formtable_tda">
<td height="54" align="right" valign="middle">&nbsp;</td>

<td align="left" valign="middle"><input class="requiredemailValid form-control" name="txt_email" id="txt_email" type="text" style="font-size: 13px; font-weight:normal;"></td>
</tr>
<tr class="formtable_tda">
<td style="font-size: 13px;" align="left" valign="middle">&nbsp;</td>
<td style="font-size: 13px; font-weight:normal;" colspan="2" align="left" valign="middle"><strong style="font-size: 13px; font-weight:bold;">Phone Number:</strong><span> * </span></td>
</tr>
<tr class="formtable_tdb">
<td height="49" align="right" valign="middle">&nbsp;</td>

<td align="left" valign="middle"><input class="required phoneValid form-control" name="txt_phone" id="txt_phone" type="text" style="font-size: 13px; font-weight:normal;"></td>
</tr>
<tr class="formtable_tdb">
<td style="font-size: 13px;" align="left" valign="middle">&nbsp;</td>
<td style="font-size: 13px; font-weight:normal;" colspan="2" align="left" valign="middle"><strong style="font-size: 13px; font-weight:bold;">Location: </strong><span>*</span></td>
</tr>
<tr class="formtable_tda">
<td height="57" align="right" valign="middle">&nbsp;</td>

<td align="left" valign="middle"><select class="form-control" style="font-size: 13px; font-weight:normal; padding-left:0px" name="cbo_location" id="cbo_location">
<option value="">Please select...</option>
<option value="Lancaster City Centre">Lancaster City Centre</option>
<option value="Greaves">Greaves</option>
<option value="Primrose">Primrose</option>
<option value="Hala">Hala</option>
<option value="Freehold">Freehold</option>
</select></td>
</tr>
<tr class="formtable_tda">
<td style="font-size: 13px;" align="left" valign="middle">&nbsp;</td>
<td style="font-size: 13px; font-weight:normal;" colspan="2" align="left" valign="middle"><strong style="font-size: 13px; font-weight:bold;">Room Type Preferred:</strong><span> * </span></td>
</tr>
<tr class="formtable_tdb">
<td height="50" align="right" valign="middle">&nbsp;</td>

<td align="left" valign="middle"><select name="cbo_roomtype" class="form-control" style="font-size: 13px; font-weight:normal; padding-left:0px" id="cbo_roomtype">
<option value="">Please select...</option>
<option value="Non Ensuite">Non Ensuite</option>
<option value="Ensuite">Ensuite</option>
<option value="Non Ensuite City Centre">Non Ensuite City Centre</option>
<option value="Ensuite City Centre">Ensuite City Centre</option>
<option value="Studio Apartment">Studio Apartment</option>
<option value="No preference">No preference</option>
</select></td>
</tr>
<tr class="formtable_tdb">
<td style="font-size: 13px;" align="left" valign="middle">&nbsp;</td>
<td style="font-size: 13px; font-weight:normal;" colspan="2" align="left" valign="middle"><strong style="font-size: 13px; font-weight:bold;">Referred From:</strong></td>
</tr>
<tr class="formtable_tda">
<td height="51" align="right" valign="top" class="formtable_tdcmt">&nbsp;</td>

<td align="left" valign="middle"><span class="formtable_tdcmt"><select class="form-control" style="font-size: 13px; font-weight:normal; padding-left:0px" id="cbo_referredfrom" name="cbo_referredfrom">
<option value="">Please select...</option>
<option value="Existing Resident">Existing Resident</option>
<option value="Friend">Friend</option>
<option value="Homes for Students">Homes for Students</option>
<option value="Brand Ambassador">Brand Ambassador</option>
<option value="Facebook">Facebook</option>
<option value="Google">Google</option>
<option value="Banner">Banner</option>
<option value="Walking past">Walking past</option>
<option value="Flyer">Flyer</option>
<option value="accommodationforstudents.com">accommodationforstudents.com</option>
<option value="Gum Tree">Gum Tree</option>
<option value="spareroom.com">spareroom.com</option>
<option value="University Housing Department">University Housing Department</option>
<option value="Midlands Academy">Midlands Academy</option>
<option value="Other">Other</option>
</select></span></td>
</tr>
<tr class="formtable_tda">
<td class="formtable_tdcmt" style="font-size: 13px;" align="left" valign="top">&nbsp;</td>
<td class="formtable_tdcmt" style="font-size: 13px; font-weight:normal;" colspan="2" align="left" valign="top"><span class="" style="font-size: 13px;"><strong style="font-size: 13px; font-weight:bold;">Any other comments:</strong><span> * </span></span></td>
</tr>
<tr class="formtable_tda">
<td align="right" valign="middle">&nbsp;</td>

<td align="left" valign="middle"><textarea rows="6" class="form-control" cols="25" name="txt_comments"></textarea></td>
</tr>
<tr class="formtable_tda">
<td align="left" valign="middle">&nbsp;</td>
<td align="left" valign="middle">&nbsp;</td>
<td align="center" valign="middle">&nbsp;</td>
</tr>
<tr class="formtable_tda">
<td align="left" valign="middle">&nbsp;</td>

<td align="left" valign="middle"><input class="buttoninput submitEnquiry add-to-cart" name="submit_button" value="     Send      " id="submit_button" onclick="return valContact();" type="button"> <input name="ContactUs_Submitted" value="1" id="ContactUs_Submitted" type="hidden"> <input name="ContactUs_Title" value="Contact Us" id="ContactUs_Title" type="hidden"> <input name="ContactUs_Subject" value="Contact Us Form Data" id="ContactUs_Subject" type="hidden"></td>
</tr>
<tr class="formtable_tda">
<td align="left" valign="middle">&nbsp;</td>
<td align="left" valign="middle">&nbsp;</td>
<td align="center" valign="middle">&nbsp;</td>
</tr>
<tr class="formtable_tda">
<td align="left" valign="middle">&nbsp;</td>
<td align="left" valign="middle">&nbsp;</td>
<td align="center" valign="middle">&nbsp;</td>
</tr>
<tr class="formtable_tda">
<td align="left" valign="middle">&nbsp;</td>
<td align="left" valign="middle">&nbsp;</td>
<td align="center" valign="middle">&nbsp;</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>			  </td>
			  
			</tr>
			
		  </tbody></table>
		  </div>
			 <?php
			 }
			 else
			 {
			  echo stripslashes($row_statpage['content']);
			 } 
			  ?>
			  </td>
			  
			</tr>
			
		  </table>
		  </div>
			
 		</form>
 		
        <?php
		} 
				
		
		 global $shelf_for_inner;
			$sql_inline = "SELECT display_order,display_component_id,display_id,display_title 
				FROM 
					display_settings a,features b 
				WHERE 
					a.sites_site_id=$ecom_siteid 
					AND a.display_position='middle' 
					AND b.feature_allowedinmiddlesection = 1  
					AND layout_code='".$default_layout."' 
					AND a.features_feature_id=b.feature_id 
					AND b.feature_modulename='mod_shelf' 
				ORDER BY 
						display_order 
						ASC";
			$ret_inline = $db->query($sql_inline); // to dispplay the advert for the category page
			if ($db->num_rows($ret_inline))
			{
				?>
							<div class="container">

				<?php
				while ($row_inline = $db->fetch_array($ret_inline))
				{
					//$modname 			= $row_inline['feature_modulename'];
					$body_dispcompid	= $row_inline['display_component_id'];
					$body_dispid		= $row_inline['display_id'];
					$body_title			= $row_inline['display_title'];
					if($row_statpage['page_id']==50262 || $row_statpage['page_id']==50419)
					$shelf_for_inner	= true;
					include ("includes/base_files/shelf.php");
					$shelf_for_inner	= false;

				}
				?>
				</div>
				<?php
			}	
		?>
			</div>
		<?php	
		}
		
	};	
?>

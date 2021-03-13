<?php
	/*#################################################################
	# Script Name 	: add_newsletter_customers.php
	# Description 	: Page for adding News letter Customer
	# Coded by 		: ANU
	# Created on	: 18-Apr-2008
	# Modified by	: ANU
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'Newsletter Customer';
$help_msg = get_help_messages('ADD_NEWSLETTERCUSTOMER_MESS1');

	
?>	
<script language="javascript" type="text/javascript">
/*ANU*/

/* Function to validate the Customer Registration */
function validate_form(frm)
{
	//alert(feildmsg);
	fieldRequired 		= Array('news_custname','news_custemail');
	fieldDescription 	= Array('Customer Name','Customer Email');
	fieldEmail 			= Array('news_custemail');
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)){
	return true;
	}
	else
	{
		return false;
	}
}

function ajax_return_contents() 
{
	var ret_val = '';
	var disp 	= 'no';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val 	= req.responseText;
			targetdiv 	= document.getElementById('retdiv_id').value;
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			hide_processing();
		}
		else
		{
			 show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}
	
</script>
<form name='frmAddCustomer' action='home.php?request=newsletter_customers' method="post" onsubmit="return validate_form(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=newsletter_customers&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Newsletter Customers</a><span> Add Newsletter Customer</span></div></td>
        </tr>
        <tr>
		  <td colspan="2" align="left" valign="middle" class="helpmsgtd_main">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<?php 
		if($alert)
		{			
		?>
        <tr>
          <td colspan="2" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		
		?>
		
		<tr>
		<td colspan="2"  valign="top"  class="tdcolorgrayleft">
		<div class="editarea_div">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td colspan="2" align="left" class="seperationtd">Personal Details</td>
		</tr>
		<tr>
          <td width="24%" align="left" valign="middle" class="tdcolorgray" >Customer Title </td>
          <td width="76%" align="left" valign="middle" class="tdcolorgray">
		  <select class="input" name="news_title"  />
		  <option value="Mr." <?php if($_REQUEST['news_title']=='Mr.') echo "selected";?> >Mr.</option>
		  <option value="Ms." <?php if($_REQUEST['news_title']=='Ms.') echo "selected";?>>Ms.</option>
		  <option value="Mrs." <?php if($_REQUEST['news_title']=='Mrs.') echo "selected";?>>Mrs.</option>
		  <option value="Miss." <?php if($_REQUEST['news_title']=='Miss.') echo "selected";?>>Miss.</option>
		  <option value="M/s" <?php if($_REQUEST['news_title']=='M/s') echo "selected";?>>M/s.</option>
		  <option value="Dr." <?php if($_REQUEST['news_title']=='Dr.') echo "selected";?>>Dr.</option>
		  <option value="Sir." <?php if($_REQUEST['news_title']=='Sir.') echo "selected";?>>Sir.</option>
		  <option value="Rev." <?php if($_REQUEST['news_title']=='Rev.') echo "selected";?>>Rev.</option>
		 </select>		  </td>
        </tr>
		<tr>
          <td width="24%" align="left" valign="middle" class="tdcolorgray" >Name <span class="redtext">*</span></td>
          <td width="76%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="news_custname" value="<?=$_REQUEST['news_custname']?>" maxlength="100"   />		  </td>
        </tr>
		<tr>
          <td width="24%" align="left" valign="middle" class="tdcolorgray" >Email <span class="redtext">*</span> </td>
          <td width="76%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="news_custemail"  value="<?=$_REQUEST['news_custemail']?>" />		  </td>
        </tr>
		<tr>
          <td width="24%" align="left" valign="middle" class="tdcolorgray" >Phone</td>
          <td width="76%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="news_custphone" value="<?=$_REQUEST['news_custphone']?>"  />		  </td>
        </tr>
		<tr>
          <td width="24%" align="left" valign="middle" class="tdcolorgray" >Hide</td>
          <td width="76%" align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="news_custhide"  value="1" /></td>
        </tr>
		<?PHP
		$sql_group="SELECT custgroup_id,custgroup_name 
						  FROM customer_newsletter_group 
						  		WHERE sites_site_id=".$ecom_siteid." AND custgroup_active='1'";
		$res_group = $db->query($sql_group);
		if ($db->num_rows($res_group))
		{
	?>
		<tr>
          <td colspan="2" align="left" valign="middle" class="seperationtd" >
		  Select the newsletter groups
		  </td>
		 </tr> 
        <tr>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray" >
		   <table width="100%" border="0" cellpadding="0" cellspacing="0" class="tdcolorgray">
			  <tr>
			  <?
			  $tmp_grcnt=0;
			  
			  while($row_group = $db->fetch_array($res_group))
			  {
			  ?>
			  <td><input type="checkbox" name="chk_group[]" value="<?=$row_group['custgroup_id']?>"  /><?=$row_group['custgroup_name']?></td>
			  <?
				  $tmp_grcnt++;
				  if($tmp_grcnt>2)
				  {
					echo "</tr><tr>";
					$tmp_grcnt=0;
				  }
			  }
			  ?>
			  </tr>
			  </table>
		  </td>
    </tr>
	<?php
	}
	?>
		</table>
		</div></td>
		</tr>
	
		<tr>
          <td align="right" valign="middle" class="tdcolorgray" colspan="2" >
		  <div class="editarea_div">
		  <table width="100%">
		  <tr>
		  	<td align="right" valign="middle">		  
			  <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
			  <input type="hidden" name="search_email" id="search_email" value="<?=$_REQUEST['search_email']?>" />
			   <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
			  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
			  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
			  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
			  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
			  <input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
			  <input type="hidden" name="retdiv_id" id="retdiv_id" value="" />
			  <input name="Submit" type="submit" class="red" value="Submit"/>
			</td>
		</tr>
		</table>
		</div>
		</td>
      </tr>
  </table>
</form>	  

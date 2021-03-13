<?php
	/*#################################################################
	# Script Name 	: edit_newsletter_customers.php
	# Description 	: Page for editing news letter Customer
	# Coded by 		: ANU
	# Created on	: 18-Apr-2008
	# Modified by	: ANU
	# Modified On	: 18-Apr-2008
	#################################################################*/
	
#Define constants for this page
$page_type 			= 'Newsletter Customers';
$help_msg 			= get_help_messages('EDIT_NEWSLETTERCUST_MESS1');
$news_customer_id	= ($_REQUEST['news_customer_id']?$_REQUEST['news_customer_id']:$_REQUEST['checkbox'][0]);
$sql_news_customer	= "SELECT * FROM newsletter_customers  WHERE news_customer_id=".$news_customer_id." AND sites_site_id=$ecom_siteid";
$res_news_customer	= $db->query($sql_news_customer);
	if($db->num_rows($res_news_customer)==0)  { echo " <font color='red'> You Are Not Authorised  </a>"; exit; } 
$row_news_customer 	= $db->fetch_array($res_news_customer);

 // #Selecting already assigned groups
 		  $arr_assigned=array();	
		  $sql_group_assign="SELECT custgroup_id FROM customer_newsletter_group_customers_map 
		  							WHERE customer_id=".$news_customer_id;
		  $res_group_assign = $db->query($sql_group_assign);
		  $arr_assigned=array();

		  while($row_assigned = $db->fetch_array($res_group_assign))
		  {
				$arr_assigned[]=$row_assigned['custgroup_id'];
					
		  }
		

		$sql_group="SELECT custgroup_id,custgroup_name 
						  FROM customer_newsletter_group 
						  		WHERE sites_site_id=".$ecom_siteid." AND custgroup_active='1'";
		$res_group = $db->query($sql_group);

?>	
<script language="javascript" type="text/javascript">
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
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val 	= req.responseText;
			targetdiv 	= document.getElementById('retdiv_id').value;
			norecdiv 	= document.getElementById('retdiv_more').value;
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			/* Decide the display of action buttons*/
			
		
			hide_processing();
		}
		else
		{
			 show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}



function call_ajax_deleteall(mod,checkboxname)
{
	var atleastone 			= 0;
	var news_customer_id			= '<?php echo $news_customer_id?>';
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditNewsletterCustomer.elements.length;i++)
	{
		if (document.frmEditNewsletterCustomer.elements[i].type =='checkbox' && document.frmEditNewsletterCustomer.elements[i].name==checkboxname)
		{

			if (document.frmEditNewsletterCustomer.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmEditNewsletterCustomer.elements[i].value;
			}	
		}
	}
	
	if (atleastone==0)
	{
		alert(atleastmsg);
	}
	else
	{
		if(confirm(confirmmsg))
		{ 
			document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */
			document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
			retobj 											= eval("document.getElementById('"+retdivid+"')");
			retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';
			
			Handlewith_Ajax('services/customer_search.php','fpurpose='+fpurpose+'&news_customer_id='+news_customer_id+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}	
</script>
<form name='frmEditNewsletterCustomer' action='home.php?request=newsletter_customers' method="post" onsubmit="return validate_form(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=newsletter_customers&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&search_email=<?=$_REQUEST['search_email']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Newsletter Customers</a><span> Edit Newsletter Customer</span></div></td>
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
		<td colspan="2" valign="top"  class="tdcolorgray">
		<div class="editarea_div">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  
		 <tr>
		<td colspan="2" align="left" class="seperationtd">Personal Details</td>
		</tr>
		<tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Customer Title </td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <select class="input" name="news_title"  />
		  <option value="Mr." <? if($row_news_customer['news_title']=='Mr.') echo "selected";?> >Mr.</option>
		  <option value="Ms." <? if($row_news_customer['news_title']=='Ms.') echo "selected";?>>Ms.</option>
		  <option value="Mrs." <? if($row_news_customer['news_title']=='Mrs.') echo "selected";?>>Mrs.</option>
		  <option value="Miss." <? if($row_news_customer['news_title']=='Miss.') echo "selected";?>>Miss.</option>
		  <option value="M/s" <? if($row_news_customer['news_title']=='M/s.') echo "selected";?>>M/s.</option>
		   <option value="Dr." <?php if($row_news_customer['news_title']=='Dr.') echo "selected";?>>Dr.</option>
		  <option value="Sir." <?php if($row_news_customer['news_title']=='Sir.') echo "selected";?>>Sir.</option>
		  <option value="Rev." <?php if($row_news_customer['news_title']=='Rev.') echo "selected";?>>Rev.</option>
		  </select>		  </td>
        </tr>
		<tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Name <span class="redtext">*</span></td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="news_custname" value="<?=$row_news_customer['news_custname']?>"  maxlength="100" />		  </td>
        </tr>
		<tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Email <span class="redtext">*</span></td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="news_custemail" value="<?=$row_news_customer['news_custemail']?>"  />		  </td>
        </tr>
		<tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Phone</td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="news_custphone" value="<?=$row_news_customer['news_custphone']?>"  />		  </td>
        </tr>
		<tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Hide</td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="news_custhide"  value="1" <? if($row_news_customer['news_custhide']) echo "checked";?> /></td>
        </tr>
		<?php
			if ($db->num_rows($res_group))
			{
		?>
		<tr>
          <td colspan="4" align="left" valign="middle" class="seperationtd" >
		  Select the newsletter groups
		  </td>
		 </tr> 
		<tr>
		<td colspan="4" align="left" >
			 <table width="100%" border="0" cellpadding="0" cellspacing="0" class="tdcolorgray">
			  <tr>
			  <?
			  $tmp_grcnt=0;
			  
			  while($row_group = $db->fetch_array($res_group))
			  {
			  ?>
			  <td><input type="checkbox" name="chk_group[]" value="<?=$row_group['custgroup_id']?>" <? if(in_array($row_group['custgroup_id'],$arr_assigned)) echo "checked";?> /><?=$row_group['custgroup_name']?></td>
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
			  <input type="hidden" name="news_customer_id" id="news_customer_id" value="<?=$news_customer_id?>" />
			  <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
			  <input type="hidden" name="search_email" id="search_email" value="<?=$_REQUEST['search_email']?>" />
			  <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
			  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
			  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
			  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
			  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
			  <input type="hidden" name="fpurpose" id="fpurpose" value="update" />
			  <input type="hidden" name="checkbox[0]" id="checkbox[0]" value="<?php echo $news_customer_id?>" />
			  <input type="hidden" name="src_page" id="src_page" value="customer" />
			  <input type="hidden" name="src_id" id="src_id" value="<?php echo $news_customer_id?>" />
			  <input type="hidden" name="retdiv_id" id="retdiv_id" value="" />
			 <input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
			  <input name="Submit" type="submit" class="red" value="Update" />
			</td>
		</tr>
		</table>
		</div>
		</td>
      </tr>
  </table>
</form>	  

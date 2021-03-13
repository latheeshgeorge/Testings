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
$page_type 			= 'Events';
$help_msg 			= get_help_messages('EDIT_EVENT_MESS1');
$event_id	= ($_REQUEST['event_id']?$_REQUEST['event_id']:$_REQUEST['checkbox'][0]);
$sql_event	= "SELECT * FROM events_calendar  WHERE event_id=".$event_id." AND sites_site_id=$ecom_siteid";
$res_event	= $db->query($sql_event);
	if($db->num_rows($res_event)==0)  { echo " <font color='red'> You Are Not Authorised  </a>"; exit; } 
$row_event 	= $db->fetch_array($res_event);
?>	
<script language="javascript" type="text/javascript">
/* Function to validate the Customer Registration */
function validate_form(frm)
{
	//alert(feildmsg);
	fieldRequired 		= Array('event_date','event_title','event_description');
	fieldDescription 	= Array('Date','Title','Description');
	fieldEmail 			= Array();
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



</script>
<form name='frmeditTdolist' action='home.php?request=todo' method="post" onsubmit="return validate_form(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=todo&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&search_email=<?=$_REQUEST['search_email']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Events</a><span> Edit Event</span></div></td>
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
		<td colspan="2" align="left" class="seperationtd">Edit Event</td>
		</tr>
		
		<?php
		if($row_event['event_date']!='')
		{
			$date_arr 	= explode(' ',$row_event['event_date']);
			$date_array = explode('-',$date_arr[0]) ;
			$hr_array	= explode(':',$date_arr[1]); 		
			$date_format = $date_array[2].'-'.$date_array[1].'-'.$date_array[0];
			$hr  = $hr_array[0];
			$min = $hr_array[1];
		}
		$opt_hrstr = '';
		$opt_hrstr .= "<select name=\"hr\">";
		for($i=0;$i<24;$i++)
		{
			$i = ($i<10)?"0$i":$i;
			if($i==$hr)
			{
				$selectedhr = "selected";
			}
			else
			{
				$selectedhr = "";	
			}
		$opt_hrstr .= "<option value=\"$i\" $selectedhr>".$i."</option>"; 
		}
		$opt_hrstr .= "</select>";
		$opt_minstr = '';
		$opt_minstr .= "<select name=\"min\">";
		for($i=0;$i<60;$i++)
		{
			if($i==$min)
			{
				$selectedmn = "selected";
			}
			else
			{
				$selectedmn = "";	
			}
		$i = ($i<10)?"0$i":$i;

		$opt_minstr .= "<option value=\"$i\" $selectedmn>".$i."</option>"; 
		}
		$opt_minstr .= "</select>";
		?>		
		<tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Date <span class="redtext">*</span></td>
          <td valign="top" align="left" class="tdcolorgray">
               	              <input type="text" size="10" value="<?php echo $date_format?>" id="event_date" name="event_date">
	               &nbsp;<a onmouseout="window.status='';return true;" onmouseover="window.status='Date Picker';return true;" href="javascript:show_calendar('frmeditTdolist.event_date');" style="vertical-align:bottom;"><img width="24" border="0" height="22" src="images/show-calendar.gif"></a>
	                          &nbsp;Hr: <?php echo $opt_hrstr?>&nbsp;Min:<?php echo $opt_minstr ?></td>        </tr>
		
			<tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Title <span class="redtext">*</span></td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="event_title" value="<?=$row_event['event_title']?>"  />		  </td>
        </tr>
         <tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Description <span class="redtext">*</span></td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="event_description" value="<?=$row_event['event_description']?>"  />		  </td>
        </tr>
		<tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Event Order</td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="event_order"  value="<?php echo $row_event['event_order']?>" /></td>
        </tr>
		<tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Event Suspend?</td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray"><select name="event_suspend">
          <option value="1" <?php if($row_event['event_suspend']==1) echo "selected"; ?>>Yes</option> 
          <option value="0" <?php if($row_event['event_suspend']==0) echo "selected"; ?>>No</option> 

          </select></td>
        </tr>
		</table>
		</div></td>
		</tr> 
		<tr>
          <td align="right" valign="middle" class="tdcolorgray" colspan="2" >
		  <div class="editarea_div">
		  <table width="100%">
		  <tr>
		  	<td align="right" valign="middle">
			  <input type="hidden" name="event_id" id="event_id" value="<?=$event_id?>" />
			  <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
			  <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
			  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
			  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
			  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
			  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
			  <input type="hidden" name="fpurpose" id="fpurpose" value="update" />
			  <input type="hidden" name="checkbox[0]" id="checkbox[0]" value="<?php echo $event_id?>" />
			  <input name="Submit" type="submit" class="red" value="Update" />
			</td>
		</tr>
		</table>
		</div>
		</td>
      </tr>
  </table>
</form>	  

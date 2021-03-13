<?php
	/*#################################################################
	# Script Name 	: add_customer.php
	# Description 	: Page for adding Customer
	# Coded by 		: Latheesh
	# Created on	: 03-Sep-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$table_name 	= 'callback';
$page_type 		= 'Callback';
$help_msg		= get_help_messages('EDIT_CALLBACK_MESS1');
$callback_id	= ($_REQUEST['callback_id']?$_REQUEST['callback_id']:$_REQUEST['checkbox'][0]);
$sql_user 		= "SELECT callback_id,callback_fname,callback_lname,callback_email,callback_phone,callback_country,callback_status,callback_comments FROM $table_name where callback_id=".$callback_id."";
$res_group		= $db->query($sql_user);
$row_group 		= $db->fetch_array($res_group);
?>	
<form name='frmAddCallback' action='home.php?request=callback' method="post" onsubmit="return valform(this);">

  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          	<td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=callback&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&search_email=<?=$_REQUEST['search_email']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&status=<?=$_REQUEST['status']?>">List Callback</a><span>Callback Details</span></div></td>
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
			<td colspan="2" class="tdcolorgray" align="left">
		<div class="editarea_div">
		<table width="100%">
		 <tr>
			<td colspan="2" class="seperationtd" align="left">Personal Details</td>
		</tr>
		<tr>
		<td valign="top"  class="tdcolorgrayleft" colspan="2">
			<table width="100%" border="0" cellspacing="2" cellpadding="2">
			<tr>
			  <td align="left" valign="middle" class="tdcolorgray" ><strong>Callback Id </strong></td>
			  <td align="left" valign="middle" class="tdcolorgray"><strong>: <?php echo $row_group['callback_id']?></strong></td>
			  <td align="left" valign="middle" class="tdcolorgray"><strong>Status</strong></td>
			  <td align="left" valign="middle" class="tdcolorgray"><strong>:</strong>
                <select class="input" name="callback_status"  >
                
                <option value="" >-select-</option>
                <option value="NEW" <? if($row_group['callback_status']=='NEW') echo "selected";?> >NEW</option>
                <option value="READ" <? if($row_group['callback_status']=='READ') echo "selected";?>>READ</option>
                <option value="DONE" <? if($row_group['callback_status']=='DONE') echo "selected";?>>DONE</option>
				</select>
&nbsp;</td>
			  </tr>
			<tr>
					  <td width="12%" align="left" valign="middle" class="tdcolorgray" ><strong>Name </strong></td>
					  <td align="left" valign="middle" class="tdcolorgray"><strong>: </strong><? echo $row_group['callback_fname'] ?>&nbsp;&nbsp;<? echo $row_group['callback_lname'] ?></td>
				      <td align="left" valign="middle" class="tdcolorgray"><strong>Country </strong></td>
				      <td align="left" valign="middle" class="tdcolorgray"><strong>:</strong>
                        <?
					  if($row_group['callback_country']!='')
					  {
					  $sql_country="SELECT country_id,country_name FROM general_settings_site_country WHERE sites_site_id=".$ecom_siteid." AND country_id=".$row_group['callback_country']." AND country_hide=1" ;
					  $res_country=$db->query($sql_country);
					  }
					  $row_country=$db->fetch_array($res_country);
					  echo $row_country['country_name']?></td>
			</tr>
				<tr>
					  <td width="12%" align="left" valign="middle" class="tdcolorgray" ><strong>Email </strong></td>
					  <td width="37%" align="left" valign="middle" class="tdcolorgray"><strong>:</strong> <? echo $row_group['callback_email'] ?></td>
				      <td width="10%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
				      <td width="41%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td></tr>
				<tr>
					  <td width="12%" align="left" valign="middle" class="tdcolorgray" ><strong>Phone</strong></td>
					  <td width="37%" align="left" valign="middle" class="tdcolorgray"><strong>:</strong> <? echo $row_group['callback_phone'] ?></td>
				      <td width="10%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
				      <td width="41%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
				</tr>
				<tr>
					  <td colspan="4" align="left" valign="top" class="seperationtd" ><strong>Comments</strong></td>
			    </tr>
				
				
				<tr>
					  <td colspan="4" align="left" valign="middle" class="tdcolorgray" ><table width="100%" border="0" cellspacing="1" cellpadding="1">
                          <tr>
                            <td width="2%">&nbsp;</td>
                            <td width="98%" align="left"><?=nl2br($row_group['callback_comments']) ?></td>
                        </tr>
                        </table></td>
			    </tr>
		  </table>
		</td>
		</tr>
		<tr>
				  <td width="48%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
				  <td width="52%" align="left" valign="middle" class="tdcolorgray" >
				  <input type="hidden" name="callback_id" id="customer_id" value="<?=$callback_id?>" />
				  <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
				  <input type="hidden" name="search_email" id="search_email" value="<?=$_REQUEST['search_email']?>" />
				   <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
				  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
				  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
				  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
				  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
				  <input type="hidden" name="status" id="status" value="<?=$_REQUEST['status']?>" />
				  <input type="hidden" name="fpurpose" id="fpurpose" value="update" />
				  <input type="hidden" name="retdiv_id" id="retdiv_id" value="" /></td>
		</tr>
		</table>
		</div>
		</td>
		</tr>
  </table>
  </div>
  <div class="editarea_div">
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="right" valign="middle">
		<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CALLBACK_STATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> &nbsp;&nbsp;&nbsp;
<input name="Submit" type="submit" class="red" value="Save" />
	</td>
	</tr>
	</table>
  </div>
</form>	  


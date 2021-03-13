<?php
	/*
	#################################################################
	# Script Name 	: list_message.php
	# Description 	: Page for listing messages
	# Coded by 		: LSH
	# Created on	: 12-Nov-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################
	*/
//Define constants for this page

$table_name = 'console_suggestions';
$page_type  = 'Console Suggestion ';
$help_msg   = 'This section lists the Console suggestions Detaily.';
$table_headers 		= array('Slno.','Date','Title','Site','Username','Service','Feature','Status','Action');
$header_positions	= array('center','left','left','left','left','left','left','left','left');
$colspan 			= count($table_headers);

//Search terms
$search_fields 	= array('sugg_title','sugg_status');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
//Sort
$sort_by 		  =  (!$_REQUEST['sort_by'])?'sugg_title':$_REQUEST['sort_by'];
$sort_order 	  =  (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options 	  =  array('sugg_title' => 'Suggestion Title');
$sort_option_txt  =  'Sort by '.generateselectbox('sort_by',$sort_options,$sort_by);
$sort_option_txt .=  ' in '.generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
//Search Options
$where_conditions = "WHERE 1=1 ";
//Search Options
/*if($_REQUEST['help_message']) {
	$where_conditions .= " AND help_help_message LIKE '%".add_slash($_REQUEST['help_message'])."%'";
}*/
if($_REQUEST['sugg_id']) {
	$where_conditions .= " AND sugg_id = '".$_REQUEST['sugg_id']."'";
}
//Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);//Getting total count of records

/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = is_numeric($_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:10;#Total records shown in a page
$pg= $_REQUEST['pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
$startrec = ($pg - 1) * $records_per_page;//Starting record.
$pages = ceil($numcount / $records_per_page);//Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
/////////////////////////////////////////////////////////////////////////////////////

?>
<script language="javascript">
	function send_message() {
		frm = document.frmSavemessageshidden;
		  
		if(frm.hid_reply.value=='on') {
			frm.hid_reply.value='off';
			document.getElementById('titleid').style.display='none';
			document.getElementById('messageid').style.display='none';
			document.getElementById('sendid').style.display='none'; 
			document.getElementById('sendmailid').style.display=''; 
		} else {
			frm.hid_reply.value='on';
			document.getElementById('titleid').style.display='';
			document.getElementById('messageid').style.display='';
			document.getElementById('sendid').style.display='';
			document.getElementById('sendmailid').style.display='none'; 			
		}
		
	}
	function send_cancel() {
		frm = document.frmSavemessageshidden;
		  
		if(frm.hid_reply.value=='on') {
			frm.hid_reply.value='off';
			document.getElementById('titleid').style.display='none';
			document.getElementById('messageid').style.display='none';
			document.getElementById('sendid').style.display='none';
			document.getElementById('sendmailid').style.display=''; 
		} else {
			frm.hid_reply.value='on';
			document.getElementById('titleid').style.display='';
			document.getElementById('messageid').style.display='';
			document.getElementById('sendid').style.display='';
			document.getElementById('sendmailid').style.display='none'; 	
		}
		
	}
	function sendmail() {
	
		frm = document.frmSavemessageshidden;
		  
		if(frm.reply_title.value=="") {
			alert("Please Enter Subject");
			frm.reply_title.focus()
		} else if(frm.reply_message.value=="")
		{
			alert("Please Enter Message");
			frm.reply_title.focus()
		} else {
			frm.request.value='console_suggestion';
			frm.fpurpose.value='sendmail';
			frm.submit();
	  }		
	} 
	function change_status() {
		frm = document.frmSavemessageshidden;
		if(confirm("Are you sure that you want to change the Status this record?")) {
			frm.request.value='console_suggestion';
			frm.fpurpose.value='statuschange';
			frm.submit();
			return true;
			} else {
				return false;
			}
	}
</script>
  <form name="frmSavemessageshidden" method="get" action="home.php">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="2" valign="top">
		<table border="0" cellpadding="2" cellspacing="0" width="100%" align="left">
		  <tr> 
			<td  class="menutabletoptd">&nbsp;<b>View Console Suggestions<font size="1">>></font> 
			  </b><br /><img src="images/blueline.gif" alt="" border="0" height="1" width="400"></td>
	      </tr>
		</table></td>
	</tr>
      <tr>
        <td colspan="2" class="maininnertabletd3">
		<?=$help_msg?></td>
      </tr>
	  <tr>
	  	<td>
		<table width="100%">
  	<?php
	
	$sql_propertytype = "SELECT sugg_id,DATE_FORMAT(sugg_date,'%d %b %Y') AS date,sugg_date,sites_site_id,sugg_user_id,
								sugg_user_name,sugg_email,services_service_id,
								features_feature_id,sugg_status,sugg_title,sugg_text 
								FROM $table_name 
										$where_conditions 
												ORDER BY $sort_by $sort_order LIMIT $startrec, $records_per_page ";
	$res = $db->query($sql_propertytype); 

	$row = $db->fetch_array($res);
	
		$count_no++;
		$array_values = array();
		if($count_no %2 == 0)
			$class_val="maininnertabletd1";
		else
			$class_val="maininnertabletd2";	
			if($row['sites_site_id']>0)
			{
				$sql_sites = "SELECT site_id,site_domain FROM sites WHERE site_id=".$row['sites_site_id'];
				$res_sites = $db->query($sql_sites);
				$row_sites = $db->fetch_array($res_sites);
				$sitename = $row_sites['site_domain'];
			}
			if($row['services_service_id']>0)
			{
				$sql_sites = "SELECT service_name FROM services WHERE service_id=".$row['services_service_id'];
				$res_sites = $db->query($sql_sites);
				$row_sites = $db->fetch_array($res_sites);
				$service_name = $row_sites['service_name'];
			}
			if($row['features_feature_id']>0)
			{
				$sql_sites = "SELECT feature_name FROM features WHERE feature_id=".$row['features_feature_id'];
				$res_sites = $db->query($sql_sites);
				$row_sites = $db->fetch_array($res_sites);
				$feature_name = $row_sites['feature_name'];
			}
		
	if($msg) {	
	?>
	
    <tr class="maininnertabletd1">
      <td colspan="4" valign="middle" class="maininnertabletd1" align="center" nowrap="nowrap">&nbsp;<font color="#FF0000"><?=$msg?></font></td>
      </tr>
	 <? } ?> 
    <tr class="maininnertabletd1">
    <td width="20%" valign="middle" class="maininnertabletd1" style="padding-left:25px;"><strong>&nbsp;Date </strong></td>
    <td width="25%" valign="middle" class="maininnertabletd1">&nbsp;<?=$row['date']?></td>
    <td width="12%" valign="middle" class="maininnertabletd1">&nbsp;<strong>Site Name</strong></td>
    <td width="43%" valign="middle" class="maininnertabletd1">&nbsp;<?=$sitename?></td>
  </tr>
  <tr class="maininnertabletd1">
    <td valign="middle" style="padding-left:25px;" class="maininnertabletd1">&nbsp;<strong>Service</strong></td>
    <td valign="middle" class="maininnertabletd1">&nbsp;<?=$service_name?></td>
    <td valign="middle" class="maininnertabletd1">&nbsp;<strong>Feature</strong></td>
    <td valign="middle" class="maininnertabletd1">&nbsp;<?=$feature_name?></td>
  </tr>
  <tr class="maininnertabletd1">
    <td valign="middle" style="padding-left:25px;" class="maininnertabletd1">&nbsp;<strong>Username</strong></td>
    <td colspan="3" valign="middle" class="maininnertabletd1">&nbsp;<?=$row['sugg_user_name']?></td>
  </tr>
  <tr class="maininnertabletd1">
    <td valign="middle" style="padding-left:25px;" class="maininnertabletd1">&nbsp;<strong>Status</strong></td>
    <td valign="middle" class="maininnertabletd1" nowrap="nowrap">&nbsp;
        <select name="sugg_status" id="sugg_status">
          <option value="NEW" <?=(stripslashes($row['sugg_status_'])=='NEW')?'selected':''; ?>>NEW</option>
          <option value="READ" <?=(stripslashes($row['sugg_status'])=='READ')?'selected':''; ?>>READ</option>
          <option value="CLOSED" <?=(stripslashes($row['sugg_status'])=='CLOSED')?'selected':''; ?>>CLOSED</option>
        </select>
      &nbsp;
      <input type="button" name="status" value=" Change Status " class="smallsubmit" onclick="javascript:change_status()"/></td>
    <td valign="middle" class="maininnertabletd1"><span class="maininnertabletd1" style="padding-left:25px;"><strong>Email</strong></span></td>
    <td valign="middle" class="maininnertabletd1"><?=$row['sugg_email']?>
      <input id="sendmailid" type="button" name="button" value="Send Mail" class="smallsubmit" onclick="javascript:send_message()"/>
      <input type="hidden" name="hid_reply" />
      <input type="hidden" name="hid_send"  />
      <input type="hidden" name="request"  />
      <input type="hidden" name="fpurpose"  />
      <input type="hidden" name="sugg_id" value="<?=$_REQUEST['sugg_id']?>"  /></td>
  </tr>
  
  <tr class="maininnertabletd1"  id="titleid" style="display:none;">
    <td valign="middle" class="maininnertabletd1" style="padding-left:25px;"><strong>&nbsp;</strong></td>
    <td valign="middle" class="maininnertabletd1">&nbsp;</td> 
    <td colspan="2" align="left" valign="middle" class="maininnertabletd1">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
	    <tr >
	      <td valign="middle" class="maininnertabletd3"  style="padding-left:25px;"  colspan="10" nowrap="nowrap">Please Fill the following Details </td>
	      </tr>
	    <tr class="maininnertabletd1">
	      <td height="23" colspan="<?=$colspan?>" valign="middle" class="maininnertabletd1" style="padding-left:25px;">&nbsp;<strong>Subject</strong></td>
      <td colspan="3" valign="middle" class="maininnertabletd1"><input type="text" name="reply_title" size="50" /></td>
    </tr>
	    <tr class="maininnertabletd1" id="messageid" style="display:none;">  
	      <td valign="middle" style="padding-left:25px;" class="maininnertabletd1" colspan="<?=$colspan?>">&nbsp;<strong>Message</strong></td>
      <td colspan="3" valign="middle" class="maininnertabletd1"><textarea name="reply_message" cols="45" rows="5"></textarea></td>
    </tr>
	    <tr class="maininnertabletd1" id="sendid" style="display:none;">
	      <td valign="middle" style="padding-left:25px;" class="maininnertabletd1" colspan="<?=$colspan?>">&nbsp;</td>
      <td colspan="3" valign="middle" class="maininnertabletd1">
	  <input type="button" name="button" value=" Cancel " class="smallsubmit" onclick="send_cancel()" />
	  &nbsp;
	  <input type="button" name="button" value=" Send Mail " class="smallsubmit" onclick="sendmail()"/></td>
    </tr>
          </table></td>
    </tr>
  <tr class="maininnertabletd1">
    <td valign="middle" style="padding-left:25px;" class="maininnertabletd1">&nbsp;<strong>Title</strong></td>
    <td colspan="3" valign="middle" class="maininnertabletd1">&nbsp;<?=$row['sugg_title']?></td>
  </tr>
  <tr class="maininnertabletd1"> 
    <td valign="top" class="maininnertabletd1" style="padding-left:25px;"><strong>&nbsp;Message</strong></td>
    <td colspan="3" valign="middle" class="maininnertabletd1">&nbsp;<?= nl2br($row['sugg_text'])?></td>
  </tr>
		</table></td></tr>
	  
	
    
		  <!-- Search Section Ends here -->

    
    
	<?php
	
	 /*
	?>
 <!-- <tr class="maininnertabletd1">
        <td align="center" valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>"><input type="button" name="add_news" value="Add <?=$page_type?>" onclick="location.href='home.php?request=console_news&fpurpose=add&news_title_search=<?=$_REQUEST['news_title_search']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>';" class="input-button"/></td>      
  </tr> -->
  <?php 
  */
 // $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=console_news&startrec=$startrec";
//  paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 

  ?>
  </table>
  </form> 

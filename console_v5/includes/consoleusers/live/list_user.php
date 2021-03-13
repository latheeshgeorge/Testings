<?php
	/*#################################################################
	# Script Name 	: list_user.php
	# Description 	: Page for listing Site Users
	# Coded by 		: SKR
	# Created on	: 12-June-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name='sites_users_7584';
$page_type='Console Users';
$help_msg = get_help_messages('LIST_CONUSERS_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistUser,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistUser,\'checkbox[]\')"/>','Slno.','Name','Email','Branch','User Type','Active');
$header_positions=array('left','left','left','left','left','left','left');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('search_name');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'user_fname':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('user_fname' => 'Name','user_email_9568' => 'Email');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( user_fname LIKE '%".add_slash($_REQUEST['search_name'])."%' OR user_lname LIKE '%".add_slash($_REQUEST['search_name'])."%')";
}
if($_REQUEST['shop_id']==0 || $_REQUEST['shop_id']>0) {
	$where_conditions .= " AND shop_id='".$_REQUEST['shop_id']."'";
}
//#Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";

$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);#Getting total count of records
/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = (is_numeric($_REQUEST['records_per_page']) and $_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:10;#Total records shown in a page
$pg = ($_REQUEST['search_click'])?1:$_REQUEST['pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
$pages = ceil($numcount / $records_per_page);#Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}
$start = ($pg - 1) * $records_per_page;#Starting record.
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
/////////////////////////////////////////////////////////////////////////////////////
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=console_user&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start";
?>
<script language="javascript">
function call_ajax_changestatus(search_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var curid				= 0;
	var user_ids 			= '';
	var ch_status			= document.frmlistUser.cbo_changestatus.value;
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistUser.elements.length;i++)
	{
		if (document.frmlistUser.elements[i].type =='checkbox' && document.frmlistUser.elements[i].name=='checkbox[]')
		{

			if (document.frmlistUser.elements[i].checked==true)
			{
				atleastone = 1;
				if (user_ids!='')
					user_ids += '~';
				 user_ids += document.frmlistUser.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the User to change the hide status');
	}
	else
	{
		if(confirm('Change Status of Seleted User(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/consoleuser.php','fpurpose=change_status&'+qrystr+'&user_ids='+user_ids);
		}	
	}	
}

function checkSelected()
{
	len=document.frmlistUser.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistUser.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one user ');
		return false;
	}
	show_processing();
	return true;
}
function edit_selected()
{
	
	len=document.frmlistUser.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistUser.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one user ');
	}
	else if(cnt>1 ){
		alert('Please select only one user to edit');
	}
	else
	{
		show_processing();
		document.frmlistUser.fpurpose.value='edit';
		document.frmlistUser.submit();
	}
	
	
}
function call_ajax_delete(search_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 	= 0;
	var del_ids 	= '';
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistUser.elements.length;i++)
	{
		if (document.frmlistUser.elements[i].type =='checkbox' && document.frmlistUser.elements[i].name=='checkbox[]')
		{

			if (document.frmlistUser.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistUser.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select user to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected User?'))
		{
			show_processing();
			Handlewith_Ajax('services/consoleuser.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function ajax_return_contents() 
{
	var ret_val='';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val = req.responseText;
			document.getElementById('maincontent').innerHTML=ret_val;
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
<form name="frmlistUser" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="console_user" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td align="left" valign="middle" class="treemenutd" colspan="3"><div class="treemenutd_div"><span> List Console Users</span></div></td>
    </tr>
	<tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="3">
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
          			<td colspan="3" align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
          		</tr>
		 <?php
		 	}
		 ?> 
	<?php
			if($numcount) {
	?>
	<tr>
		<td class="sorttd" colspan="3" align="right">
		<?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?>
		</td>
	</tr>
	<?php 	} ?>
    <tr>
      <td height="48" class="sorttd" colspan="3">
	  <div class="sorttd_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
        <tr>
          <td width="7%" height="30" align="left" valign="middle">User Name </td>
          <td width="17%" height="30" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
          <td width="4%" height="30" align="left" valign="middle">Branch</td>
          <td width="12%" height="30" align="left" valign="middle"> <?php 
		  	// Get the list of shops under current site
			$sql_shops = "SELECT shop_id,shop_title,shop_active FROM sites_shops WHERE sites_site_id=$ecom_siteid ORDER BY shop_title";
			$ret_shops =$db->query($sql_shops);
		  ?>
		  	<select name="shop_id">
		  	<option value="0" <?php echo ($row_user['shop_id']==$_REQUEST['shop_id'])?'selected="selected"':''?>>Web</option>
		  <?php
		  	if($db->num_rows($ret_shops))
			{
				while ($row_shops = $db->fetch_array($ret_shops))
				{
					if($row_shops['shop_active']==1)
						$stat = 'Active';
					else
						$stat = 'Inactive';
			?>
					 <option value="<?php echo $row_shops['shop_id']?>"<?php echo ($_REQUEST['shop_id']==$row_shops['shop_id'])?'selected="selected"':''?>><?php echo stripslashes($row_shops['shop_title']).'('.$stat.')'?></option>
			<?php		
				}
			}
		  ?>
		  </select></td>
          <td width="10%" height="30" align="left" valign="middle">Records Per Page </td>
          <td width="9%" height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
          <td width="5%" height="30" align="left" valign="middle">Sort By</td>
          <td width="24%" height="30" align="left" valign="middle"><?=$sort_option_txt?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp; <?=$sort_by_txt?></td>
          <td width="12%" height="30" align="right" valign="middle"><input name="button5" type="submit" class="red" id="button5" value="Go" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('CONUSERS_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
      </table>
	  </div>
      </td>
    </tr>
	
    <tr>
      <td class="listingarea" colspan="3">
	  <div class="listingarea_div">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr class="maintable">
			<td class="listeditd">
				<a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=console_user&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()" >Add</a> <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
			</td>
			<td class="listeditd" width="162" align="center">&nbsp; </td>
			<td class="listeditd" align="right">
			<?php
				if($numcount)
				{
			?>
				Change Active Status to 
				<select name="cbo_changestatus" class="dropdown" id="cbo_changestatus">
					<option value="1">Yes</option>
					<option value="0">No</option>
				</select>&nbsp;<input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('CONUSERS_CHANGE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
				}
			?>
			</td>
		</tr>
		</table>
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount) {
	   $sql_user = "SELECT user_id,shop_id,user_fname,user_lname,user_email_9568,user_type,user_active 
	   					FROM $table_name 
	   							$where_conditions 
	   								ORDER BY $sort_by $sort_order 
										LIMIT $start,$records_per_page ";
	  
	   $res = $db->query($sql_user);
	   $srno = getStartOfPageno($records_per_page,$pg);
	   while($row = $db->fetch_array($res))
	   {
			$count_no++;
			$array_values = array();
			if($count_no %2 == 0)
				$class_val="listingtablestyleA";
			else
				$class_val="listingtablestyleB";	
	   $sql_shop_name = "SELECT shop_title 
	   						FROM sites_shops 
	   							 WHERE sites_site_id=$ecom_siteid AND shop_id='".$row['shop_id']."'
	   									ORDER BY shop_title";
	   $ret_shop_name = $db->query($sql_shop_name);
	   $ret_row_name = $db->fetch_array($ret_shop_name);
	   if($ret_row_name['shop_title']=='') $shopname = "Web"; else $shopname = $ret_row_name['shop_title'];
	   ?>
        <tr >
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="6%"><input name="checkbox[]" value="<? echo $row['user_id']?>" type="checkbox"></td>
		    <td align="left" valign="middle" class="<?=$class_val;?>" width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=console_user&fpurpose=edit&checkbox[0]=<?php echo $row['user_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="<? echo $row['user_fname']?>" class="edittextlink" onclick="show_processing()"><? echo $row['user_fname']?>&nbsp;<? echo $row['user_lname']?></a></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><? echo $row['user_email_9568']?></td>
	      <td align="left" valign="middle" class="<?=$class_val;?>"><? echo $shopname;?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><? echo $row['user_type']?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['user_active'] == 1)?'Yes':'No'; ?></td>
         
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="<?=$colspan?>" >
				  	No Users  exists.				  </td>
			</tr>
		<?
		}
		?>	
		<tr>
      <td class="listeditd" colspan="<?=($colspan-1)?>"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=console_user&fpurpose=add&records_per_page=<?=$records_per_page?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()" >Add</a> <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a></td>
	   <td class="listeditd" width="162" align="center">
		</td>
    </tr>
      </table>
	  </div>
	  <tr>
      
	   <td class="listing_bottom_paging" colspan="2" align="center">
	  <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
    </tr>
	  </td>
    </tr>
    
    </table>
</form>
<!-- Script for auto complete starts here -->
<script language="javascript">
var $pnc = jQuery.noConflict();
$pnc().ready(function() { 
	auto_search('search_name','sitecons'); 
});
</script>
<!-- Script for auto complete ends here -->
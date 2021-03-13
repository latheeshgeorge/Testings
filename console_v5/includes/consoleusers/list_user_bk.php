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
$table_name='sites_users';
$page_type='Console Users';
$help_msg = 'This section lists the Users available on the site. Here there is provision for adding a User, editing, & deleting it.';
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistUser,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistUser,\'checkbox[]\')"/>','Slno.','Name','Email','User Type','Hidden');
$header_positions=array('left','left','left','left','left','left');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('user_email');
foreach($search_fields as $v) {
	$query_string .= "$v=${$v}&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'user_fname':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('user_fname' => 'Name','user_email' => 'Email');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( user_fname LIKE '%".add_slash($_REQUEST['search_name'])."%' OR user_lname LIKE '%".add_slash($_REQUEST['search_name'])."%')";
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
</script>
<form name="frmlistUser" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="console_user" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
  <table border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td align="left" valign="middle" class="treemenutd" colspan="3"> List Users</td>
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
    <tr>
      <td height="48" class="sorttd" colspan="3">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttableleft">
        <tr>
          <td width="22%" align="left" valign="middle">User Name </td>
          <td colspan="3" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
          </tr>
      
      </table>
      <table width="18%" border="0" cellpadding="0" cellspacing="0" class="sorttableright">
        <tr>
          <td width="12%" align="left">Show</td>
          <td colspan="2" align="left"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/>
            <?=$page_type?> Per Page</td>
          </tr>
        <tr>
          <td align="left">Sort By</td>
          <td width="41%" align="left"><?=$sort_option_txt?>
            in
           	<?=$sort_by_txt?>		   </td>
          <td width="47%" align="left"><input name="button5" type="submit" class="red" id="button5" value="Go" /> <a href="#" onmouseover ="ddrivetip('Use \'Go\' button to search for users.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
      </table>
	  </td>
    </tr>
    <tr>
      <td class="listeditd"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=console_user&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()" >Add</a> <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="" onclick="getSelected('delete')" class="deletelist">Delete</a></td>
	   <td class="listeditd" width="162" align="center">
	  <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
	  <td class="listeditd" align="right">
	  <?
	  if($numcount)
	  {
	  ?>
        Change Status
          <select name="cmbstatus" class="dropdown" id="cmbstatus">
            <option value="0">Yes</option>
			<option value="1">No</option>
          </select>&nbsp;<input name="Update_Status" type="submit" class="red" id="button4" value="Change" onclick="return checkSelected()" />
		  <a href="#" onmouseover ="ddrivetip('Use \'Change\' button to change the hide status of users. Select the hide status in the drop down, mark the users to be changed and press \'Change\' button.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
		<?
		}
		?>
   	   </td>
    </tr>
    <tr>
      <td class="listingarea" colspan="3">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
       <?
	   echo table_header($table_headers,$header_positions);
	   $sql_user = "SELECT user_id,user_fname,user_lname,user_email,user_type,user_active FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
	   $res = $db->query($sql_user);
	   $srno = 1;  
	   while($row = $db->fetch_array($res))
	   {
			$count_no++;
			$array_values = array();
			if($count_no %2 == 0)
				$class_val="listingtablestyleA";
			else
				$class_val="listingtablestyleB";	
	   
	   ?>
        <tr >
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="6%"><input name="checkbox[]" value="<? echo $row['user_id']?>" type="checkbox"></td>
		    <td align="left" valign="middle" class="<?=$class_val;?>" width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=console_user&fpurpose=edit&checkbox[0]=<?php echo $row['user_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="<? echo $row['user_fname']?>" class="edittextlink" onclick="show_processing()"><? echo $row['user_fname']?>&nbsp;<? echo $row['user_lname']?></a></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><? echo $row['user_email']?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><? echo $row['user_type']?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['user_active'] == 1)?'No':'Yes'; ?></td>
         
        </tr>
      <?
	  }
	  ?>
      </table></td>
    </tr>
    <tr>
      <td class="listeditd"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=console_user&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()" >Add</a> <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="" onclick="getSelected('delete')" class="deletelist">Delete</a></td>
	   <td class="listeditd" width="162" align="center">
	  <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
	  <td class="listeditd" align="right">&nbsp;
	 
   	   </td>
    </tr>
    </table>
</form>

<?php
	/*#################################################################
	# Script Name 	: list_group_selshelf.php
	# Description 	: Page for listing shelves
	# Coded by 		: Joby
	# Created on	: 05-May-2011
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
$table_name='product_shelf';
$page_type='Shelves';
$help_msg = get_help_messages('LIST_SHELVES_ASSIGN_PROD_MESS1');
$shelf_group_id=($_REQUEST['pass_shelfgroup_id']?$_REQUEST['pass_shelfgroup_id']:'0');

$tabale = "shelf_group";
$where  = "id=".$shelf_group_id;
if(!server_check($tabale, $where)) {
	echo " <font color='red'> You Are Not Authorised  </a>";
	exit;
}	


$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_shelves,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_shelves,\'checkbox[]\')"/>','Slno.','Shelf','Hide');
$header_positions=array('center','left','left','center');
$colspan = count($table_headers);
$search_fields = array('shelf_name','shelf_hide','sort_by','sort_order');
$query_string = "request=shelfgroup&fpurpose=ShelfGroupAssign&pass_shelfgroup_id=".$shelf_group_id."";

$option_search_style_display = 'style="display:"';
// to hold the options TR if searched using the options feild
if(($_REQUEST['shelf_name']!='') || ($_REQUEST['shelf_hide']!='')){
	$option_search_style_display = 'style="display:"';
}
else{
	$option_search_style_display = 'style="display:none"';
}
//#Search terms

foreach($search_fields as $v) {
	$query_string .= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
}
	$query_string .="&shelf_group_name=".$_REQUEST['shelf_group_name']."&pass_searchname=".$_REQUEST['pass_searchname']."&pass_sortby=".$_REQUEST['pass_sortby']."&pass_sortorder=".$_REQUEST['pass_sortorder']."&pass_records_per_page=".$_REQUEST['pass_records_per_page']."&pass_start=".$_REQUEST['pass_start']."&pass_pg=".$_REQUEST['pass_pg']."&start=$start";
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'shelf_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('shelf_name' => 'Name');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";

//#Avoiding already assigned product
$sql_assigned="SELECT shelf_shelf_id FROM shelf_group_shelf WHERE  shelf_group_id 	=".$shelf_group_id;
$ret_assigned = $db->query($sql_assigned);
$str_assigned='-1';

while($row_assigned = $db->fetch_array($ret_assigned))
{
	$str_assigned.=','.$row_assigned['shelf_shelf_id'];
	
}
$str_assigned='('.$str_assigned.')';	
$where_conditions.=" AND shelf_id NOT IN $str_assigned";
	
// Product Name Condition
if($_REQUEST['shelf_group_name'])
{
	$where_conditions .= " AND ( shelf_name LIKE '%".add_slash($_REQUEST['shelf_group_name'])."%') ";
}


// ==================================================================================================
// ==================================================================================================

// Hidden
$hide 		= trim($_REQUEST['shelf_hide']);
if($hide)
{
	$where_conditions .= " AND ( shelf_hide ='".$hide."') ";
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
if ($pg>=1)
{
	$start = ($pg - 1) * $records_per_page;//#Starting record.
	$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
}	
else
{
	$start = $count_no = 0;	
}

/////////////////////////////////////////////////////////////////////////////////////

$sql_qry = "SELECT * FROM product_shelf	$where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
//echo $sql_qry;
$ret_qry = $db->query($sql_qry);
?>
<script type="text/javascript">
function call_save_selected(cname,sortby,sortorder,recs,start,pg,group_id) 
{
	
	var atleastone 			= 0;
	for(i=0;i<document.frm_shelves.elements.length;i++)
	{
		if (document.frm_shelves.elements[i].type =='checkbox' && document.frm_shelves.elements[i].name=='checkbox[]')
		{

			if (document.frm_shelves.elements[i].checked==true)
			{
				atleastone ++;
				
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the shelf  to assign');
	}
	
	else
	{
		if(confirm('Are you sure you want to assign selected shelf(s) ?'))
		{
			show_processing();
			document.frm_shelves.fpurpose.value='save_ShelfGroupAssign';
			document.frm_shelves.submit();
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
<form method="post" name="frm_shelves" class="frmcls" action="home.php">
<input type="hidden" name="request" value="shelfgroup" />
<input type="hidden" name="fpurpose" value="ShelfGroupAssign" />
<input type="hidden" name="search_click" value="" />
<input type="hidden" name="pass_shelfgroup_id" value="<?=$shelf_group_id?>" />
<input type="hidden" name="shelfgroup_id" value="<?=$shelf_group_id?>" />
<input type="hidden" name="pass_searchname" value="<?=$_REQUEST['pass_searchname']?>" />
<input type="hidden" name="pass_sortby" value="<?=$_REQUEST['pass_sortby']?>" />
<input type="hidden" name="pass_sortorder" value="<?=$_REQUEST['pass_sortorder']?>" />
<input type="hidden" name="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
<input type="hidden" name="pass_start" value="<?=$_REQUEST['pass_start']?>" />
<input type="hidden" name="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
<?
$sql_shelf="SELECT name FROM shelf_group  WHERE id=".$shelf_group_id;
$res_shelf= $db->query($sql_shelf);
$row_shelf = $db->fetch_array($res_shelf);
?>


<table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=shelfgroup&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>">List Shelf Menus </a> <a href="home.php?request=shelfgroup&fpurpose=edit&checkbox[0]=<?php echo $shelf_group_id?>&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=shelfs_tab_td">Edit Shelf Menu</a><span>Assign shelf for '<? echo $row_shelf['name'];?>'</span></div></td>
    </tr>
	<tr>
	  <td align="left" valign="middle" class="helpmsgtd_main">
	  <?php 
		  Display_Main_Help_msg($help_arr,$help_msg);
	  ?>	 </td>
	</tr>
	<?php 
			if($alert)
			{			
		?>
        		<tr>
          			<td align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
          		</tr>
		 <?php
		 	}
		 ?>
	<?php
		if ($db->num_rows($ret_qry))
		{
	?> 
    <tr>
		<td align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td>
	</tr>
	<?php
		}
	?>
	<tr>
      <td height="48" class="sorttd">
		<div class="sorttd_div">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="66%" align="left" valign="top">
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="9%" height="30" align="left" valign="middle">Shelf Name</td>
              <td width="21%" height="30" align="left" valign="middle"><input name="shelf_group_name" type="text" class="textfeild" id="shelf_group_name" value="<?php echo $_REQUEST['shelf_group_name']?>" /></td>
              <td width="10%" height="30" align="left" valign="middle"><!--Category-->Records Per Page </td>
              <td width="16%" height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3" value="<?php echo $records_per_page?>" />		  		    </td>
              <td width="6%" height="30" align="left" valign="middle">Sort By</td>
              <td width="31%" height="30" align="left" valign="middle"><?php echo $sort_option_txt;?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;<?php echo $sort_by_txt?> </td>
              <td width="7%" height="30" align="right" valign="middle"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_shelves.search_click.value=1" />
                <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_SHELVES_ASSIGN_PROD_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				</tr>
            <tr>
              <td align="left"></td>
              <td align="left"></td>
              <td align="left"></td>
              <td colspan="4" align="left">			 	  </td>
            </tr>
          </table>            </td>
          </tr>
      </table>
		</div>      </td>
    </tr>
    <tr>
      <td class="listingarea">
	  <div class="listingarea_div">
      <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	  	<tr>
		  <td align="right" valign="middle" class="listeditd" colspan="<?php echo $colspan?>">      
			<input name="change_hide" type="button" class="red" id="change_hide" onClick="call_save_selected()" value="Assign Selected">	  </td>
		</tr>
	 <?php  
	 	echo table_header($table_headers,$header_positions); 
		if ($db->num_rows($ret_qry))
		{ 

			 $srno = getStartOfPageno($records_per_page,$pg);
			while ($row_qry = $db->fetch_array($ret_qry))
			{
				$cls 		= ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
				
	 ?>
			   	<tr>
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="5%">
				  <input type="checkbox" name="checkbox[]" id="checkbox[]" value="<?php echo $row_qry['shelf_id']?>" /></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="1%"><?php echo $srno++?></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="20%"><a href="home.php?request=shelfs&fpurpose=edit&checkbox[0]=<?php echo $row_qry['shelf_id']?>&shelf_group_name=<?php echo $_REQUEST['shelf_group_name']?>&shelf_hide=<?php echo $_REQUEST['shelf_hide']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="edit" class="edittextlink"><?php echo stripslashes($row_qry['shelf_name'])?></a></td>
				 

				  
				  <td align="center" valign="middle" class="<?php echo $cls?>">
				  	<?php
				  		echo ($row_qry['shelf_hide']=='1')?'Yes':'No';	
					?>				</td>
				</tr>
	<?php
			}
		}
		else
		{
	?>	
			<tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>">
				  	No Shelves found.				  </td>
			</tr>	  
	<?php
		}
	?>
		
      </table>
	  </div></td>
    </tr><?php 
		if ($db->num_rows($ret_qry))
		{
	?>
	  <tr>
		  <td align="right" valign="middle" class="listing_bottom_paging" colspan="<?php echo $colspan?>">
		  <?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);?> </td>
	  </tr>
	<?php
		}
		?>
  </table>
</form>

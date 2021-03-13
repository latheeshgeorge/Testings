<?php
	/*
	#################################################################
	# Script Name 	: list_seo.php
	# Description 	: Page for managing Seo 
	# Coded by 		: LSH
	# Created on	: 09-Oct-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################
	*/

//Define constants for this page
$table_name = 'static_pages';
$page_type = 'Static Pages';
$help_msg = 'This section Helps to manage Static Pages';

/////////////////////////////////For paging///////////////////////////////////////////

$cbo_sites = $_REQUEST['cbo_sites'];
$keytype		= $_REQUEST['records_per_page'];

$table_headers = array('Slno.','Page Title','Page in Groups','Type','Hidden','Action');
$header_positions=array('center','left','left','left','center','center');
$colspan = count($table_headers);
//#Sort

$sort_by = (!$_REQUEST['sort_by'])?'title':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('title' => 'Title','page_type'=>'Type');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= 'Sort by '.generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

$where_conditions = "WHERE sites_site_id=$cbo_sites ";
$search_name = $_REQUEST['search_name'];
if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( title LIKE '%".add_slash($_REQUEST['search_name'])."%')";
}

$sql_count = "SELECT count(*) as cnt
				 FROM $table_name  
				 	$where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);#Getting total count of records

$records_per_page = (is_numeric($_REQUEST['records_per_page']) &&($_REQUEST['records_per_page']))?$_REQUEST['records_per_page']:10;#Total records shown in a page
$pg= ($_REQUEST['search_click']==1)?1:$_REQUEST['pg'];
if (!($pg > 0) || $pg == 0) { $pg = 1; }
$start = ($pg - 1) * $records_per_page;#Starting record.
$pages = ceil($numcount / $records_per_page);#Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
$colspan = 6;
/////////////////////////////////////////////////////////////////////////////////////
$query_string .= "request=seo&fpurpose=staticpage&sort_by=$sort_by&sort_order=$sort_order&cbo_sites=$cbo_sites&search_name=$search_name";

?>
<script type="text/javascript">
	function handle_searchsubmit()
	{
		if(document.main.cbo_sites.value=='')
		{
			//alert("Please select a Site");
			//return false;
		}
		formhandler('sel_site');
	}
	function formhandler(purpose)
	{
		var purp
		purp=purpose
		document.main.fpurpose.value=purp
		document.main.submit()	
	}
	function handle_typechange()
	{
	
		document.frmlistStaticpages.retain_val.value 	= 1;
		document.frmlistStaticpages.type_change.value 	= 1;
		document.frmlistStaticpages.submit();
	}
	
</script>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="<?=$colspan?>" valign="top"><table border="0" cellpadding="2" cellspacing="0" width="100%" align="left">
      <tr>
        <td class="menutabletoptd">&nbsp;<b><a href="home.php?request=seo&amp;cbo_sites=<?=$cbo_sites?>">Manage SEO</a><font size="1">&gt;&gt;</font> List
          <?=$page_type?>
          </b><br />
          <img src="images/blueline.gif" alt="" border="0" height="1" width="400" /></td>
      </tr>
    </table>
        <br />    </td>
  </tr>
  <tr>
    <td colspan="<?=$colspan?>" class="maininnertabletd3"><?=$help_msg?>    </td>
  </tr>
  <?php
	  	if($error_msg)
		{
	  ?>
  <tr>
    <td colspan="<?=$colspan?>" align="center" class="error_msg"><?php echo $error_msg?></td>
  </tr>
  <?php
	  	}
	  ?>
  <!-- Search Section Starts here -->
  <form name="frmlistStaticpages" method="post" action="home.php?request=seo">
  <tr class="maininnertabletd1">
    <td valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>"> Show
      <input type="text" id="records_per_page" name="records_per_page" value="<?=$records_per_page?>" size="2"/>
        <?=$page_type?>
      per page. &nbsp;&nbsp;&nbsp;Title 
      <input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>" />
      &nbsp; <?php echo $sort_by_txt?>
      <input type="submit" class="smallsubmit" name="cbo_keytype" value="Go"  onclick="handle_typechange()" />
      <br />
      <br />
      <input type="hidden" name="pg" value="1" />
      <input type="hidden" name="type_change" value="" />
      <input type="hidden" name="retain_val" value="" />    </td>
  </tr>
  <!-- Search Section Ends here -->
  <!-- Search Section Starts here -->
  <input type="hidden" name="fpurpose" value="staticpage" />
  <input type="hidden" name="request" value="seo" />
  <input type="hidden" name="cbo_sites" value="<?=$cbo_sites?>" />
  <input type="hidden" name="start" value="<?=$start?>" />
  <input type="hidden" name="pg" value="<?=$pg?>" />
  <input type="hidden" name="search_click" value="" />
    <?php 
			if($alert)
			{			
		?>
    <tr>
      <td  colspan="7" align="center" valign="middle" class="errormsg"><?php echo $alert?></td>
    </tr>
    <?php
		 	}
		 ?>
    <?
	   echo table_header($table_headers,$header_positions);
	   $sql_page = "SELECT page_id,title,hide,page_type,allow_edit 
								    FROM $table_name 
									   $where_conditions 
										 ORDER BY $sort_by $sort_order 
											LIMIT $start,$records_per_page ";
	   $res = $db->query($sql_page); 
	   if ($db->num_rows($res)){
	   while($row = $db->fetch_array($res))
	   {
			$count_no++;
			$array_values = array();
			if($count_no %2 == 0)
				$class_val="listingtablestyleA";
			else
				$class_val="listingtablestyleA";	
	   
	   ?>
    <tr >
      <td width="5%" align="center" valign="middle"  class="<?=$class_val;?>"><?=$count_no?></td>
      <td  align="left" valign="middle" class="<?=$class_val;?>">
	  <a href="home.php?request=seo&fpurpose=edit_stat_page&page_id=<?=$row['page_id']?>&cbo_sites=<?=$_REQUEST['cbo_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">
	  <?PHP echo $row['title']; ?></a>
	  </td>
      <td align="left" valign="middle" class="<?=$class_val;?>"><?
		 $sql_page_group="SELECT group_name 
		 					FROM static_pagegroup a,static_pagegroup_static_page_map b 
								WHERE b.static_pages_page_id=".$row['page_id']." AND a.group_id=b.static_pagegroup_group_id";
		 $res_page_group=$db->query($sql_page_group);
		 $num_page_group=$db->num_rows($res_page_group);
		  if($num_page_group)
		  {
		  ?>
		  <select name="page_group">
		  <?
		  while($row_page_group=$db->fetch_array($res_page_group))
		  {
		  	echo "<option value=$row_page_group[group_name]>$row_page_group[group_name]</option>";
		  }
		  ?>
	     </select>
	     <?
		 }
		 else
		 {
		 	echo "NOT Assigned to any group";
		 }
		 ?></td>
	  <td width="16%" align="left" valign="middle" class="<?=$class_val;?>"><?php echo $row['page_type']; ?></td>
	  <td width="16%" align="center" valign="middle" class="<?=$class_val;?>"><?php echo ($row['hide'] == 1)?'Yes':'No'; ?></td>	 
      <td width="16%" align="center" valign="middle"><a href="#" onclick="if(delete_confirm()) { location.href='home.php?request=seo&amp;fpurpose=deletestat&amp;cbo_sites=<?=$cbo_sites?>&amp;page_id=<?=$row['page_id']?>&amp;sort_by=<?=$sort_by?>&amp;sort_order=<?=$sort_order?>&amp;records_per_page=<?=$records_per_page?>';}" title="Delete"><img src="images/delete.gif"  border="0" /></a></td>
    </tr>
    <?
	  }
	}
	  else
		{
	?>
    <tr>
      <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>"> No Static Pages for this site. </td>
    </tr>
    <?php
		}
	?>
  <tr>
    <td></td></td>
  </tr>
  <tr>
    <td></tr></td>
  </tr>
  <tr>
    <td class="listeditd" align="center"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td>
    <td class="listeditd">&nbsp;</td>
  </tr>
  <tr>
    <td class="listeditd" valign="middle" align="center" colspan="6">
	<input type="button" name="add_staticpage" value="Add <?=$page_type?>" onclick="location.href='home.php?request=seo&fpurpose=add_stat_page&cbo_sites=<?=$cbo_sites?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>';" class="input-button"/>
  </tr>
  </form>
</table>

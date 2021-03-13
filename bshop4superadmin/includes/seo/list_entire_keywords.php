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

//#Define constants for this page
$table_name = 'se_keywords';
$page_type = 'List EntireKeywords';
$cbo_sites = $_REQUEST['cbo_sites'];
$keytype		= $_REQUEST['records_per_page'];
$help_msg = 'This section show the entire list of keywords added for this site.';
$table_headers = array('Slno.','Keyword','Currently Assigned','Action');
$header_positions=array('left','center','center','center');
$colspan = count($table_headers);
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'keyword_keyword':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('keyword_keyword' => 'Keywords');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= 'Sort by '.generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

$where_conditions = "WHERE sites_site_id=$cbo_sites ";
$keywords = $_REQUEST['keywords'];
if($_REQUEST['keywords']) {

	$where_conditions .= " AND  keyword_keyword LIKE '%".add_slash($_REQUEST['keywords'])."%' ";
}
//#Search Options
//#Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
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
/////////////////////////////////////////////////////////////////////////////////////
//$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=general_settings&fpurpose=captions&records_per_page=$records_per_page&start=$start";
$query_string .= "request=seo&fpurpose=Entirekeywords&sort_by=$sort_by&sort_order=$sort_order&cbo_sites=$cbo_sites&keywords=$keywords";
?>
<script src="js/overlib_tree.js" language="javascript"></script>
<script type="text/javascript">
	
	function handle_typechange()
	{
	
		document.frmlistEntireKeywords.retain_val.value 	= 1;
		document.frmlistEntireKeywords.type_change.value 	= 1;
		document.frmlistEntireKeywords.submit();
	}
	
</script>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="<?=$colspan?>" valign="top">
		<table border="0" cellpadding="2" cellspacing="0" width="100%" align="left">
		  <tr> 
			<td class="menutabletoptd">&nbsp;<b><a href="home.php?request=seo&cbo_sites=<?=$cbo_sites?>">Manage SEO</a><font size="1">>></font> 
			  List <?=$page_type?> 
			  </b><br /><img src="images/blueline.gif" alt="" border="0" height="1" width="400"></td>
		  </tr>
		</table><br />		</td>
	</tr>
      <tr>
        <td colspan="<?=$colspan?>" class="maininnertabletd3">
		<?=$help_msg?>		</td>
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
		<form name="frmlistEntireKeywords" method="post" action="home.php?request=seo">
	   <tr class="maininnertabletd1">
       <td valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>">
		 Show <input type="text" id="records_per_page" name="records_per_page" value="<?=$records_per_page?>" size="2"/> <?=$page_type?> per page. &nbsp;&nbsp;&nbsp;Key words <input name="keywords" type="text" class="textfeild" id="keywords" value="<?=$_REQUEST['keywords']?>" />
		&nbsp;
		<?php echo $sort_by_txt?>
		<input type="submit" class="smallsubmit" name="cbo_keytype" value="Go"  onclick="handle_typechange()" /><br /><br />
		<input type="hidden" name="pg" value="1" />
		<input type="hidden" name="type_change" value="" />
		<input type="hidden" name="retain_val" value="" />
		</td>
      </tr>
	  <!-- Search Section Ends here -->
	  <!-- Search Section Starts here -->
	     
	
<input type="hidden" name="fpurpose" value="Save_entire_keyword" />
<input type="hidden" name="request" value="seo" />
<input type="hidden" name="cbo_sites" value="<?=$cbo_sites?>" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
<input type="hidden" name="search_click" value="" />
  <table border="0" cellpadding="0" cellspacing="0" class="maintable">
    
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
	$sql_settings_currency = "SELECT keyword_id,keyword_keyword FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	  
	   $res = $db->query($sql_settings_currency); 
	   if ($db->num_rows($res)){
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
          <td width="2%" align="center" valign="middle"  class="<?=$class_val;?>"><?=$count_no?></td>
          <td  align="center" valign="middle" class="<?=$class_val;?>"><input type="text" size="45" name="txtkey_<?php echo $row['keyword_id']?>" value="<?php echo stripslashes($row['keyword_keyword']); ?>" /></td>
          <td align="center" valign="middle" class="<?=$class_val;?>"><?php 
	  	$assigned 		= 'No';
	  	//Check whether this keyword is assigned to any of the categories in current site
		$sql_check = "SELECT se_keywords_keyword_id FROM se_category_keywords WHERE se_keywords_keyword_id = ".$row['keyword_id']." LIMIT 1";
		$ret_check = $db->query($sql_check);
		if ($db->num_rows($ret_check))
		{
			$assigned 	= 'Yes';
		}
		else
		{
			//Check whether this keyword is assigned to any of the properties in current site
			$sql_check = "SELECT se_keywords_keyword_id FROM se_product_keywords WHERE se_keywords_keyword_id = ".$row['keyword_id']." LIMIT 1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check))
			{
				$assigned 	= 'Yes';
			}
			else
			{
				//Check whether this keyword is assigned to any of the static pages in current site
				$sql_check = "SELECT se_keywords_keyword_id FROM se_static_keywords WHERE se_keywords_keyword_id = ".$row['keyword_id']." LIMIT 1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check))
				{
					$assigned 	= 'Yes';
				}
			}
		}
		if($assigned=='No')
			echo "<span class='redtext'><strong>$assigned</strong></span>";
		else
			echo $assigned;
	  ?></td>
          <td width="16%" align="center" valign="middle">
      <a href="#" onclick="if(delete_confirm()) { location.href='home.php?request=seo&fpurpose=deletekey&cbo_sites=<?=$cbo_sites?>&key_id=<?=$row['keyword_id']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>';}" title="Delete"><img src="images/delete.gif"  border="0" /></a></td>
        </tr>
        <?
	  }
	}
	  else
		{
	?>
        <tr>
          <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>"> No Keywords for this site. </td>
        </tr>
        <?php
		}
	?>
      </table></td>
    </tr>
   
    <tr>
      <td class="listeditd" align="center"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td>
      <td class="listeditd">&nbsp;</td>
    </tr>
	 <tr>
	 <td valign="middle" align="center" colspan="2"><input name="TitleSubmit" type="submit" class="red" value="Save Keywords"  id="TitleSubmit" onclick="show_processing();"/></td>
              
      </tr>
    </table>
</form>

</td>
      </tr>
	  <!-- Search Section Ends here -->
	<?php
		?>
    <tr  class="<?=$class_val;?>">
      <td height="20" align="center">
        &nbsp;&nbsp;&nbsp;</td>
      </tr>
   
  <?php 
  $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=seo";
  ?>
   </table>
  

	


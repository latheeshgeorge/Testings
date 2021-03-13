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
$table_name='saved_search';
$page_type='Saved Keywords';
$help_msg = 'This section lists the Saved Keywords available on the site. ';
$table_headers = array('Slno.','Saved Keyword','Description','Action');
$header_positions=array('left','center','center','center');

$colspan = count($table_headers);
$cbo_sites = $_REQUEST['cbo_sites'];
//#Search terms
$search_fields = array('search_keyword');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'search_keyword':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('search_keyword' => 'Keyword');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= 'Sort by '.generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$cbo_sites ";
$keywords = $_REQUEST['keywords'];
if($_REQUEST['keywords']) {
	$where_conditions .= "AND ( search_keyword LIKE '%".add_slash($_REQUEST['keywords'])."%')";
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
$query_string .= "request=seo&fpurpose=SavedKeywords&sort_by=$sort_by&sort_order=$sort_order&cbo_sites=$cbo_sites&keywords=$keywords";
?>
<script src="js/overlib_tree.js" language="javascript"></script>
<script type="text/javascript">
	
	function handle_typechange()
	{ 
	    document.frmsearchKeyword.retain_val.value 	= 1;
		document.frmsearchKeyword.type_change.value 	= 1;
		document.frmsearchKeyword.submit();
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
	  <form name="frmsearchKeyword" method="post" action="home.php?request=seo">
	   <tr class="maininnertabletd1" >
       <td valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>">
	   
		 Show <input type="text" id="records_per_page" name="records_per_page" value="<?=$records_per_page?>" size="2"/> <?=$page_type?> per page. &nbsp;&nbsp;&nbsp;Key words <input name="keywords" type="text" class="textfeild" id="keywords" value="<?=$_REQUEST['keywords']?>" />
		&nbsp;
		<?php echo $sort_by_txt?>
		<input type="submit" class="smallsubmit" name="cbo_keytype" value="Go"  />
		<span style="padding-left:15px">
		<input type="button" class="smallsubmit" name="key_save" value=" Save " onclick="document.frmsearchKeyword.fpurpose.value='SaveKeywordDesc';document.frmsearchKeyword.submit();"  /></span><br /><br />
		<input type="hidden" name="pg" value="1" />
		<input type="hidden" name="type_change" value="" />
		<input type="hidden" name="retain_val" value="" />
		
		</td>
      </tr>
	  <!-- Search Section Ends here -->
	      
<input type="hidden" name="fpurpose" value="saved_keyword" />
<input type="hidden" name="request" value="seo" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
<input type="hidden" name="cbo_sites" value="<?=$cbo_sites?>" />
<tr><td>
  <table border="0" cellpadding="0" cellspacing="0" class="maintable">
	<?php 
			if($alert)
			{			
		?>
        		<tr>
          			<td colspan="4" align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
          		</tr>
		 <?php
		 	}
		 ?> 
    <tr>
      <td colspan="4" class="listingarea">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_keyword="SELECT * from saved_search $where_conditions ORDER BY $sort_by $sort_order LIMIT $start, $records_per_page ";
	   $res = $db->query($sql_keyword);
	   $srno = 1; 
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
		  <td align="right" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
		   <td align="center" valign="middle" class="<?=$class_val;?>" ><?php echo $row['search_keyword']; ?></td>
		    <td align="center" valign="middle" class="<?=$class_val;?>" ><textarea name="txt_<?php echo $row['search_id']; ?>" rows="1" cols="50"><?php echo $row['search_desc']; ?></textarea>
			<input type="hidden" name="searchid[]" value="<?php echo $row['search_id']; ?>" />
			</td>
		     <td width="10%" align="center" valign="middle">
      <a href="#" onclick="if(delete_confirm()) { location.href='home.php?request=seo&fpurpose=deletekeysaved&cbo_sites=<?=$cbo_sites?>&key_id=<?=$row['search_id']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>';}" title="Delete"><img src="images/delete.gif"  border="0" /></a></td>
       
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td colspan="4" align="center" valign="middle" class="norecordredtext" >
				  	No Keyword exists.				  </td>
			</tr>
		<?
		}
		?>	
      </table></td>
    </tr>
	
  <tr>
	   <td class="listeditd"  align="center">
	  <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
	   <td class="listeditd" align="right">&nbsp;
	 
   	   </td>
	    <td class="listeditd" align="right">&nbsp;
	 
   	   </td>
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
  

	


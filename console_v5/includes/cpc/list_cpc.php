<?php
	/*#################################################################
	# Script Name 	: list_callback.php
	# Description 	: Page for listing Customer
	# Coded by 		: Latheesh
	# Created on	: 03-Sep-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name			='seo_revenue_total a, seo_revenue_keywords b, search_engine c';
$page_type			='Hits to Sale Report';
$help_msg 			= get_help_messages('LIST_CPC_MESS1');
$table_headers  	= array('Slno.','Keyword','Search Engine','Total Clicks','Total Sales','Total Sale Amount','Current Month Amount','Action');
$header_positions	= array('left','left','left','left','left','left','left','left');
$colspan 			= count($table_headers);
$cur_user = $_SESSION['console_id'];
//#Search terms
$search_fields 		= array('search_se_id','search_keyword');
foreach($search_fields as $v) {
	 $query_string .= "$v=${$v}&";#For passing search terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by 			= (!$_REQUEST['sort_by'])?'a.click_to_sale':$_REQUEST['sort_by'];
$sort_order 		= (!$_REQUEST['sort_order'])?'DESC':$_REQUEST['sort_order'];
$sort_options 		= array('a.click_total' => 'Sale Amount','a.click_count' => 'Total Clicks','a.click_to_sale' => 'Total Sales');
$sort_option_txt 	= generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt		= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions 	= "WHERE a.site_id=$ecom_siteid AND a.keyword_id=b.keyword_id AND a.se_id=c.se_id ";
if($_REQUEST['search_se_id']) {
	$where_conditions .= " AND a.se_id=".$_REQUEST['search_se_id'];
}
if($_REQUEST['search_keyword']) {
	$where_conditions .= " AND b.keyword LIKE '".$_REQUEST['search_keyword']."%'";
}

$se_array[0] = '--Select Search Engine--';
$sql_se = "SELECT se_id,se_name_string FROM search_engine ORDER BY se_name_string";
$res_se = $db->query($sql_se);
while($row_se = $db->fetch_array($res_se)) {
	$se_array[$row_se['se_id']] = $row_se['se_name_string'];
}
//#Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);#Getting total count of records
/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = (is_numeric($_REQUEST['records_per_page']) and $_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:10;#Total records shown in a page
$pg = ($_REQUEST['search_click'])?1:$_REQUEST['pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
$pages = ceil($numcount / $records_per_page);#Getting the total pages.
if($pg > $pages) {
	$pg = $pages;
}
$start = ($pg - 1) * $records_per_page;#Starting record.
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
/////////////////////////////////////////////////////////////////////////////////////
 $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=cpc&records_per_page=$records_per_page&search_se_id=".$_REQUEST['search_se_id']."&search_keyword=".$_REQUEST['search_keyword']."&start=$start";

?>

<form name="frmlistCallback" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="cpc" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span>List Hits to Sale Report</span></div></td>
    </tr>
	<tr>
	  <td align="left" valign="middle" class="helpmsgtd_main" colspan="4">
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
          			<td colspan="4" align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
          		</tr>
		 <?php
		 	}
		 ?> 
    <tr>
      <td height="48" class="sorttd" colspan="4" >
		  <div class="sorttd_div">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" >
        <tr>
          <td  align="left" valign="top">
      	<table width="100%" border="0" cellpadding="1" cellspacing="1" >
			 <tr>
				  <td width="14%"  align="left" valign="middle">Search Keyword </td>
				  <td width="27%" align="left" valign="middle"><input name="search_keyword" type="text" class="textfeild" id="search_keyword" value="<?=$_REQUEST['search_keyword']?>"  /> 
				  </td>
				  <td width="14%"  align="left" valign="middle">Records Per Page </td>
			  <td width="19%"  align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
			  <td width="26%"  align="left" valign="middle">&nbsp;</td>
			 </tr>
			  <tr>
				  <td  align="left" valign="middle">Search Engine </td>
				  <td align="left" valign="middle"><?=generateselectbox('search_se_id',$se_array,$_REQUEST['search_se_id']);?> 
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CPC_SEARCHENGINE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
				 <td align="left" valign="middle">Sort By</td>
			  <td align="left" valign="middle" nowrap="nowrap"><?=$sort_option_txt?>&nbsp;&nbsp;in&nbsp;&nbsp;<?=$sort_by_txt?>  </td>
			  <td align="right" valign="middle">&nbsp;&nbsp;&nbsp;&nbsp;
			    <input name="button5" type="submit" class="red" id="button5" value="Go" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CPC_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			
			  </tr>
      
      </table>
      </td>
     
         <?php /*?>  <td width="50%" align="left" valign="top">
     <table width="50%" border="0" cellpadding="1" cellspacing="1" >
			<tr>
			  <td  align="left">Show</td>
			  <td  align="left"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/>
				<?=$page_type?> Per Page</td>
			  <td  align="left">&nbsp;</td>
			</tr>
			<tr>
			  <td align="left">Sort By</td>
			  <td align="left" nowrap="nowrap"><?=$sort_option_txt?> in <?=$sort_by_txt?>  </td>
			  <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;<input name="button5" type="submit" class="red" id="button5" value="Go" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CPC_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			</tr>
			 </table><?php 
	  </td>*/?>
    </tr>
      </table>
      </div>
	  </td>
    </tr>
        
    <tr>
      <td colspan="4" class="listingarea">
		  <div class="listingarea_div">
      <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	$sql_user = "SELECT a.click_id,b.keyword,c.se_name_string,a.click_count,a.click_to_sale,a.click_total as total_amt FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   //echo $sql_user;
	   $res = $db->query($sql_user);
	   $srno = 1; 
	   while($row = $db->fetch_array($res))
	   {
	   		$sql_month = "SELECT d.click_total FROM seo_revenue_month d WHERE d.click_id=".$row['click_id']." AND d.month=".date("m")." AND d.year=".date("Y");
			$res_month = $db->query($sql_month);
			$row_month = $db->fetch_array($res_month);
			$count_no++;
			$array_values = array();
			if($count_no %2 == 0)
				$class_val="listingtablestyleA";
			else
				$class_val="listingtablestyleB";
			$tot_click_count += $row['click_count'];
			$tot_click_to_sale += $row['click_to_sale'];
			$tot_total_amt += $row['total_amt'];
			$tot_total_click_total += $row_month['click_total'];
	  ?>
        <tr >
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $row['keyword']; ?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><? echo $row['se_name_string']?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $row['click_count']; ?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $row['click_to_sale']; ?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo display_price($row['total_amt']); ?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo display_price($row_month['click_total']); ?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=cpc&fpurpose=list_month&start=<?=$start?>&pg=<?=$pg?>&records_per_page=<?=$records_per_page?>&search_keyword=<?=$_REQUEST['search_keyword']?>&search_se_id=<?=$_REQUEST['search_se_id']?>&click_id=<?=$row['click_id']?>" title="Monthly View" class="edittextlink"><?php echo 'Monthly View'; ?></a></td>
        </tr>
      <?php
	  }
	  ?>
	  <tr >
          <td valign="middle" class="listeditd" colspan="3" align="right"><b>Page Totals</b></td>
          
		  <td align="left" valign="middle" class="listeditd"><b><?=$tot_click_count?></b></td>
		  <td align="left" valign="middle" class="listeditd"><b><?=$tot_click_to_sale?></b></td>
		  <td align="left" valign="middle" class="listeditd"><b><?php echo display_price($tot_total_amt); ?></b></td>
		  <td align="left" valign="middle" class="listeditd"><b><?php echo display_price($tot_total_click_total); ?></b></td>
		  <td align="left" valign="middle" class="listeditd"></td>
        </tr>
	  <?php
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext"  colspan="8">
				  	No Cost Per Click exists.				  </td>
			</tr>
		<?
		}
		?>	
      </table>
      </div>
      </td>
    </tr>
	
  <tr>
	   <td class="listing_bottom_paging" colspan="8" align="center">
	  <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
    </tr>
  </table>
</form>

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
$table_name			='seo_revenue_month a, seo_revenue_keywords b, search_engine c';
$page_type			='Hits to Sale Report';
$help_msg 			= get_help_messages('LIST_CPCMONTH_MESS1');
$table_headers  	= array('Slno.','Date','Total Clicks','Total Sale Clicks','Monthly Sale Amount');
$header_positions	= array('left','left','left','left','left','left','left','left');
$colspan 			= count($table_headers);


	
//#Sort
$sort_by 			= (!$_REQUEST['sort_by'])?'click_to_sale':$_REQUEST['sort_by'];
$sort_order 		= (!$_REQUEST['sort_order'])?'DESC':$_REQUEST['sort_order'];
$sort_options 		= array('click_total' => 'Sale Amount','click_count' => 'Total Clicks','click_to_sale' => 'Total Sale Clicks','year,month' => 'Date');
$sort_option_txt 	= generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt		= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

if($_REQUEST['search_year']) {
	$where_conditions .= " AND year = '".$_REQUEST['search_year']."'";
}
$sql_se = "SELECT b.keyword,c.se_name_string FROM seo_revenue_total a, seo_revenue_keywords b, search_engine c WHERE a.site_id=$ecom_siteid AND a.keyword_id=b.keyword_id AND a.se_id=c.se_id AND a.click_id=".$_REQUEST['click_id'];
$res_se = $db->query($sql_se);
$row_se = $db->fetch_array($res_se);

?>

<form name="frmlistCallback" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="list_month" />
<input type="hidden" name="request" value="cpc" />
<input type="hidden" name="start" value="<?=$_REQUEST['start']?>" />
<input type="hidden" name="pg" value="<?=$_REQUEST['pg']?>" />
<input type="hidden" name="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
<input type="hidden" name="search_keyword" value="<?=$_REQUEST['search_keyword']?>" />
<input type="hidden" name="search_se_id" value="<?=$_REQUEST['search_se_id']?>" />
<input type="hidden" name="click_id" value="<?=$_REQUEST['click_id']?>" />
<input type="hidden" name="search_year" value="<?=$_REQUEST['search_year']?>" />
  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=cpc&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_keyword=<?=$_REQUEST['search_keyword']?>&search_se_id=<?=$_REQUEST['search_se_id']?>">List Hits to Sale Report</a>  <span>Monthly View</span></div></td>
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
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
			<tr>
			  <td align="left" valign="top">
		  		<table width="100%" border="0" cellpadding="1" cellspacing="1" >
				<tr>
				  <td width="10%"  align="left">Year				  </td>
				  <td width="28%" align="left"><select name="search_year" id="search_year">
				  <option value=""></option>
				  <?php for($y=2009; $y<=date('Y'); $y++) {
					if($y == $_REQUEST['search_year']) {
						?>
						<option value="<?=$y?>" selected="selected"><?=$y?></option>
						<?php
					} else {
						?>
						<option value="<?=$y?>"><?=$y?></option>
						<?php
					}
				  }
				  ?>
				  </select>
					</td>
					 <td width="19%" align="left" valign="middle">Search Keyword </td>
					  <td width="2%"  align="left" valign="middle"><b><?=stripslashes($row_se['keyword'])?></b>					  </td>
				  <td width="41%" align="left">&nbsp;</td>
				</tr>
				<tr>
				  <td align="left">Sort By</td>
				  <td align="left" nowrap="nowrap"><?=$sort_option_txt?> in <?=$sort_by_txt?>  </td>
				   <td width="19%" align="left" valign="middle">Search Engine </td>
					  <td  align="left" valign="middle"><b><?=stripslashes($row_se['se_name_string'])?></b>
					 </td>
				  <td align="center">&nbsp;&nbsp;&nbsp;&nbsp;
				    <input name="button5" type="submit" class="red" id="button5" value="Go" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CPCMONTH_GO')?>')" onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				</tr>
		  </table>
		   </td>
		</tr>
		  </table>
      </div>
	  </td>
    </tr>
        
    <tr>
      <td colspan="4" class="listingarea">
		  		  <div class="listingarea_div">

      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
       <?
	   $month_array = array(1 => 'Jan',2 => 'Feb',3 => 'Mar', 4 => 'Apr',5 =>'May',6 => 'Jun',7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
	   $numcount = 1;
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	
		   $sql_user = "SELECT month,year,click_count,click_to_sale,click_total FROM seo_revenue_month WHERE click_id=".$_REQUEST['click_id']." $where_conditions ORDER BY $sort_by $sort_order";
		   
		   $res = $db->query($sql_user);
		   $srno = 1;
		   $pt_total_clicks = 0;
		   $pt_total_sales = 0;
		   $pt_total_amount = 0; 
		   while($row = $db->fetch_array($res))
		   {
				$count_no++;
				$array_values = array();
				if($count_no %2 == 0)
					$class_val="listingtablestyleA";
				else
					$class_val="listingtablestyleB";
				$sql_month = "SELECT ";	
		  ?>
			<tr >
			  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
			  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $month_array[$row['month']]; ?> - <?php echo $row['year']; ?></td>
			  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $row['click_count']; $pt_total_clicks += $row['click_count']; ?></td>
			  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $row['click_to_sale']; $pt_total_sales += $row['click_to_sale']; ?></td>
			  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo display_price($row['click_total']); $pt_total_amount += $row['click_total']; ?></td>
			</tr>
		  <?php
		  }
		  ?>
		  <tr >
			  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"></td>
			  <td align="right" valign="middle" class="<?=$class_val;?>"><b>Page Total</b></td>
			  <td align="left" valign="middle" class="<?=$class_val;?>"><b><?php echo $pt_total_clicks; ?></b></td>
			  <td align="left" valign="middle" class="<?=$class_val;?>"><b><?php echo $pt_total_sales; ?></b></td>
			  <td align="left" valign="middle" class="<?=$class_val;?>"><b><?php echo display_price($pt_total_amount); ?></b></td>
			</tr>
		  <?php
	  }
		?>	
      </table>
      </div></td>
    </tr>
    </table>
</form>

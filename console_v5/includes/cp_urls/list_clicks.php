<?php
/*#################################################################
# Script Name 	: list_clicks.php
# Description 		: Page for listing cost per click of a selected month
# Coded by 		: SNY
# Created on		: 31-Oct-2008
#################################################################*/
//Define constants for this page
$table_name='costperclick_time';
$page_type='Cost Per Clicks';
$help_msg = get_help_messages('LIST_COMPANY_TYPES_MESS1');
$table_headers = array('Slno','Date','IP Address','Time');
$header_positions=array('left','center','center','center');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('search_ipaddress','search_start_date','search_end_date');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
// Get the details related to curret url id
$sql_urls = "SELECT a.url_id, a.url_mypage,a.url_hidden,a.url_total_clicks,a.url_total_sale_clicks,a.url_total_fraud_clicks,a.url_total_sale_amount,
												b.keyword_word,c.advertplace_name,a.url_setting_rateperclick 
					FROM 
						costperclick_adverturl a,costperclick_keywords b,costperclick_advertplacedon c 
					WHERE 
						a.sites_site_id=$ecom_siteid 
						AND a.url_id=".$_REQUEST['url_id']." 
						AND a.costperclick_keywords_keyword_id=b.keyword_id 
						AND a.costperclick_adverplaced_on_advertplace_id = c.advertplace_id 
					LIMIT 
						1";
$ret_urls = $db->query($sql_urls);
if ($db->num_rows($ret_urls))
{
	$row_urls 				= $db->fetch_array($ret_urls);
	$show_keyword		= stripslashes($row_urls['keyword_word']);
	$show_adverton		= stripslashes($row_urls['advertplace_name']);
	$show_mypage		= stripslashes($row_urls['url_mypage']);
	$show_rate				= $row_urls['url_setting_rateperclick'];
}

// Get the details of month
$sql_mon = "SELECT month_id, month_total_clicks, month_total_sale_clicks, month_total_fraud_clicks, month_total_sale_amount, month_mon, month_year 
						FROM 
							costperclick_month 
						WHERE 
							month_id = ".$_REQUEST['month_id'] ." 
							AND sites_site_id = $ecom_siteid 
						LIMIT 
							1";
$ret_mon = $db->query($sql_mon);
if ($db->num_rows($ret_mon))
{
	$row_mon 		= $db->fetch_array($ret_mon);
	$show_date	= date('M-Y',mktime(0,0,0,$row_mon['month_mon'],1,$row_mon['month_year']));
	
}
switch($_REQUEST['typ'])
{
	case 'gen':
		$show_type = 'Genuine Clicks';
		$fraud = 0;
	break;	
	case 'fraud':
		$show_type = 'Fraud Clicks';
		$fraud = 1;
	break;	
};
							

//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'time_time':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('time_time' => 'Date','time_ipaddress'=>'IP Address');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE 
									sites_site_id=$ecom_siteid 
									AND costperclick_month_month_id = ".$_REQUEST['month_id']." 
									AND time_isfraud=$fraud" ;

if (trim($_REQUEST['search_ipaddress'])!='')
{
	$where_conditions .=" AND 
										time_ipaddress LIKE '%".add_slash(trim($_REQUEST['search_ipaddress']))."%' ";
}
$from_date 	= trim($_REQUEST['search_start_date']);
$to_date 		= trim($_REQUEST['search_end_date']);
if($from_date!='')
{
	$from_arr = explode('-',$from_date);
	if (count($from_arr)==3) 
	{
		if (is_numeric($from_arr[0]) and is_numeric($from_arr[1]) and is_numeric($from_arr[2]))
		{
			if(checkdate($from_arr[1],$from_arr[0],$from_arr[2]))
			{
				$from_str = mktime(0,0,0,$from_arr[1],$from_arr[0],$from_arr[2]);
				$show_from_date = $from_arr[0].'-'.$from_arr[1].'-'.$from_arr[2];
			}
		}
	}
}
if($to_date!='')
{
	$to_arr = explode('-',$to_date);
	if (count($to_arr)==3) 
	{
		if (is_numeric($to_arr[0]) and is_numeric($to_arr[1]) and is_numeric($to_arr[2]))
		{
			if(checkdate($to_arr[1],$to_arr[0],$to_arr[2]))
			{
				$to_str = mktime(23,59,59,$to_arr[1],$to_arr[0],$to_arr[2]);
				$show_to_date = $to_arr[0].'-'.$to_arr[1].'-'.$to_arr[2];
			}
		}
	}
}
if (!$from_str and $to_str) // case if only end date is given 
{
	$where_conditions .=" AND 
										time_time<= $to_str";
}
elseif($from_str and !$to_str)
{
	$where_conditions .=" AND 
										time_time >= $from_str";
}
elseif($from_str and $to_str)
{
	$where_conditions .=" AND 
										(time_time BETWEEN $from_str and $to_str) ";
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
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=costperclick_urls&fpurpose=list_clicks&url_id=".$_REQUEST['url_id']."&month_id=".$_REQUEST['month_id']."&typ=".$_REQUEST['typ']."&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start";


?>
<form name="frmlistclicks" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="list_clicks" />
<input type="hidden" name="request" value="costperclick_urls" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
<input type="hidden" name="url_id" value="<?=$_REQUEST['url_id']?>" />
<input type="hidden" name="month_id" value="<?=$_REQUEST['month_id']?>" />
<input type="hidden" name="typ" value="<?=$_REQUEST['typ']?>" />
<input type="hidden" name="pass_seach_name" value="<?=$_REQUEST['pass_seach_name']?>" />
<input type="hidden" name="pass_cbo_keyword" value="<?=$_REQUEST['pass_cbo_keyword']?>" />
<input type="hidden" name="pass_cbo_advertlocation" value="<?=$_REQUEST['pass_cbo_advertlocation']?>" />
<input type="hidden" name="pass_start" value="<?=$_REQUEST['pass_start']?>" />
<input type="hidden" name="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
<input type="hidden" name="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
<input type="hidden" name="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />


  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td align="left" valign="middle" class="treemenutd">
		  		  <div class="treemenutd_div">
<a href="home.php?request=costperclick_urls&&search_name=<?php echo $_REQUEST['pass_seach_name']?>&cbo_keyword=<?php echo $_REQUEST['pass_cbo_keyword']?>&cbo_advertlocation=<?php echo $_REQUEST['pass_cbo_advertlocation']?>&start=<?php echo $_REQUEST['pass_start']?>&pg=<?php echo $_REQUEST['pass_pg']?>&sort_by=<?php echo $_REQUEST['pass_sort_by']?>&sort_order=<?php echo $_REQUEST['pass_sort_order']?>&records_per_page=<?php echo $_REQUEST['pass_records_per_page']?>" class="edittextlinks">Cost Per Clicks </a>  <a href="home.php?request=costperclick_urls&fpurpose=list_monthly&url_id=<?php echo $_REQUEST['url_id']?>&pass_seach_name=<?php echo $_REQUEST['pass_seach_name']?>&pass_cbo_keyword=<?php echo $_REQUEST['pass_cbo_keyword']?>&pass_cbo_advertlocation=<?php echo $_REQUEST['pass_cbo_advertlocation']?>&pass_start=<?php echo $_REQUEST['pass_start']?>&pass_pg=<?php echo $_REQUEST['pass_pg']?>&pass_sort_by=<?php echo $_REQUEST['pass_sort_by']?>&pass_sort_order=<?php echo $_REQUEST['pass_sort_order']?>&pass_records_per_page=<?php echo $_REQUEST['pass_records_per_page']?>">Cost Per Click Monthly Report</a> <span> List of <strong><?php echo $show_type?></strong> for the selected month</span></div></td>
    </tr>
	<tr>
	<td align="left" valign="middle" class="helpmsgtd_main">
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
          			<td align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
          		</tr>
		 <?php
		 	}
		 ?> 
    <tr>
      <td height="48" class="sorttd" ><div class="editarea_div"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="50%" valign="top"><table width="100%" border="0" cellpadding="1" cellspacing="1">
            <tr>
              <td align="left">IP Address</td>
              <td width="88%" align="left"><input type="text" name="search_ipaddress" value="<?php echo $_REQUEST['search_ipaddress']?>" /></td>
            </tr>
            <tr>
              <td width="25%" align="left">Dates   (dd-mm-yyyy)</td>
              <td align="left">Between
                  <input name="search_start_date" type="text" size="10" value="<?php echo $show_from_date?>" />
                  <a href="javascript:show_calendar('frmlistclicks.search_start_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a> &nbsp;and
                  <input name="search_end_date" type="text" size="10" value="<?php echo $show_to_date?>" />
                <a href="javascript:show_calendar('frmlistclicks.search_end_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a></td>
            </tr>

          </table></td>
          <td width="50%" valign="top">
			  <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td align="left">Show</td>
              <td align="left"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/>
                  <?=$page_type?>
                Per Page</td>
              <td align="left">&nbsp;</td>
            </tr>
            <tr>
              <td align="left">Sort By</td>
              <td align="left" nowrap="nowrap"><?=$sort_option_txt?>
                in
                <?=$sort_by_txt?>              </td>
              <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;
                  <input name="button5" type="submit" class="red" id="button5" value="Go" />
                <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_COMPANY_TYPES_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
			<?php
				if($numcount>0)
				{
			?>
					<tr>
					<td colspan="3" align="center">
					<input type="button" name="Button" value=" Export " class="red" onclick="document.frmlistclicks.fpurpose.value='list_export';document.frmlistclicks.submit();" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('CLICK_EXPORT_HELP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
					</td>
					</tr>
			 <?php
			 	}
			 ?> 
          </table></td>
        </tr>
         </table></div></td>
        </tr>
        <tr>
          <td colspan="2" valign="top">
			  <div class="editarea_div">
			  <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="50%" align="left" valign="top"><table width="100%" border="0" cellpadding="1" cellspacing="1">
                <tr>
                  <td colspan="2" align="left" valign="middle" class="listingtableheader">Sponsored Link Details </td>
                </tr>
                <tr>
                  <td align="left" valign="middle" class="listingtablestyleB"><strong>Keyword</strong></td>
                  <td align="left" valign="middle" class="listingtablestyleB"><strong>:</strong> <?php echo $show_keyword?></td>
                </tr>
                <tr>
                  <td width="27%" align="left" valign="middle" class="listingtablestyleB"><strong>Advertised  on </strong></td>
                  <td width="73%" align="left" valign="middle" class="listingtablestyleB"><strong>:</strong> <?php echo $show_adverton?></td>
                </tr>
                <tr>
                  <td align="left" valign="middle" class="listingtablestyleB"><strong>My Page </strong></td>
                  <td align="left" valign="middle" class="listingtablestyleB"><strong>:</strong> <?php echo $show_mypage?></td>
                </tr>
                <tr>
                  <td align="left" valign="middle" class="listingtablestyleB"><strong>Cost per click </strong></td>
                  <td align="left" valign="middle" class="listingtablestyleB"><strong>:</strong> <?php echo display_price($show_rate)?></td>
                </tr>
              </table></td>
              <td valign="top">
			  	<table width="100%" border="0" cellspacing="1" cellpadding="1">
                <tr>
                  <td colspan="5" align="left" class="listingtableheader"><strong>Cost per click summary for the month of </strong><?php echo $show_date?></td>
                </tr>
                <tr>
                  <td class="listingtablestyleB" align="center"><strong>Total Genuine Clicks </strong></td>
                  <td class="listingtablestyleB" align="center"><strong>Total Sale Clicks </strong></td>
                  <td class="listingtablestyleB" align="center"><strong>Total Fraud Clicks </strong></td>
                  <td class="listingtablestyleB" align="center"><strong>Total Sale Amount </strong></td>
                  <td class="listingtablestyleB" align="center"><strong>Total Cost Per Click</strong></td>
                </tr>
                <tr>
                  <td class="listingtablestyleA" align="center"><?php echo $row_mon['month_total_clicks']?></td>
                  <td class="listingtablestyleA" align="center"><?php echo $row_mon['month_total_sale_clicks']?></td>
                  <td class="listingtablestyleA" align="center"><?php echo $row_mon['month_total_fraud_clicks']?></td>
                  <td class="listingtablestyleA" align="center"><?php echo display_price($row_mon['month_total_sale_amount'])?></td>
                  <td class="listingtablestyleA" align="center"><?php echo display_price($row_mon['month_total_clicks']*$show_rate)?></td>
                </tr>
                <tr>
                  <td colspan="5" align="center" class="listingtablestyleA">
				  <?php 
				  	if ($fraud==1)
					{
					?>
				 <a href="home.php?request=costperclick_urls&fpurpose=list_clicks&typ=gen&url_id=<?php echo $_REQUEST['url_id']?>&month_id=<?php echo $_REQUEST['month_id']?>&pass_seach_name=<?php echo $_REQUEST['pass_seach_name']?>&pass_cbo_keyword=<?php echo $_REQUEST['pass_cbo_keyword']?>&pass_cbo_advertlocation=<?php echo $_REQUEST['pass_cbo_advertlocation']?>&pass_start=<?php echo $_REQUEST['pass_start']?>&pass_pg=<?php echo $_REQUEST['pass_pg']?>&pass_sort_by=<?php echo $_REQUEST['pass_sort_by']?>&pass_sort_order=<?php echo $_REQUEST['pass_sort_order']?>&pass_records_per_page=<?php echo $_REQUEST['pass_records_per_page']?>" title="Click here to view Genuine Clicks" class="edittextlink">Click to View Genuine Clicks</a>
				  <?php
				  }
				  else
				  {
				  ?>
				  <a href="home.php?request=costperclick_urls&fpurpose=list_clicks&typ=fraud&url_id=<?php echo $_REQUEST['url_id']?>&month_id=<?php echo $_REQUEST['month_id']?>&pass_seach_name=<?php echo $_REQUEST['pass_seach_name']?>&pass_cbo_keyword=<?php echo $_REQUEST['pass_cbo_keyword']?>&pass_cbo_advertlocation=<?php echo $_REQUEST['pass_cbo_advertlocation']?>&pass_start=<?php echo $_REQUEST['pass_start']?>&pass_pg=<?php echo $_REQUEST['pass_pg']?>&pass_sort_by=<?php echo $_REQUEST['pass_sort_by']?>&pass_sort_order=<?php echo $_REQUEST['pass_sort_order']?>&pass_records_per_page=<?php echo $_REQUEST['pass_records_per_page']?>" title="Click here to view Fraud Clicks" class="edittextlink">Click to View Fraud Clicks</a>
				  <?php
				  }
				  ?>
				  </td>
                </tr>
              </table></td>
            </tr>
          </table>
          </div>
          </td>
        </tr>
      
    <tr>
      <td align="center" class="listeditd">
	    <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
    </tr>
     
    <tr>
      <td class="listingarea"><div class="editarea_div">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
		   $sql_click = "SELECT time_id, costperclick_month_month_id, costperclick_adverturl_url_id, time_time, time_ipaddress, time_isfraud 
		   								FROM 
											$table_name 
											$where_conditions 
										ORDER BY 
											$sort_by $sort_order 
										LIMIT 
											$start,$records_per_page ";
		   
		   $res = $db->query($sql_click);
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
			  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?>.</td>
			   <td align="center" valign="middle" class="<?=$class_val;?>" width="33%"><? echo date('d-M-Y',$row['time_time'])?></td>
			  <td align="center" valign="middle" class="<?=$class_val;?>" width="33%"><? echo $row['time_ipaddress']?></td>
			  <td align="center" valign="middle" class="<?=$class_val;?>" width="32%"><? echo date('h:i A',$row['time_time'])?></td>
			</tr>
      <?
	  			$tot_gen_clicks += $row['month_total_clicks'];
				$tot_fraud_clicks += $row['month_total_fraud_clicks'];
				$tot_sale_amt += $row['month_total_sale_amount'];
				$tot_sale_clicks += $row['month_total_sale_clicks'];
				$tot_cost			+= $cur_cost;
	  		}
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>" >
				  	Cost per click details found.				  
				  	</td>
		  </tr>
		<?
		}
		?>	
      </table>
      </div>
      </td>
    </tr>
	
  <tr>
      <td align="RIGHT" class="listeditd"> 
	    <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?>   	   </td>
    </tr>
  </table>
</form>

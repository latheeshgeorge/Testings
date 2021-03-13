<?php
/*#################################################################
# Script Name 	: list_monthly.php
# Description 		: Page for listing cost per click monthly
# Coded by 		: SNY
# Created on		: 31-Oct-2008
#################################################################*/
//Define constants for this page
$table_name='costperclick_month';
$page_type='Cost Per Clicks';
$help_msg = get_help_messages('LIST_COMPANY_TYPES_MESS1');
$table_headers = array('Slno','Date','Total Genuine Clicks','Total Sale Clicks','Total Fraud Clicks','Total Sale Amount','Total Cost Per Click','Action');
$header_positions=array('left','center','center','center','center','right','right','center');
$colspan = count($table_headers);

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
//#Sort
$sort_order = (!$_REQUEST['sort_order'])?'DESC':$_REQUEST['sort_order'];
if ($sort_by!='month_mon,month_year')
	$sort_by = (!$_REQUEST['sort_by'])?"month_mon $sort_order,month_year ":$_REQUEST['sort_by'];
else
{
	$sort_by = "month_mon $sort_order,month_year ";
}

$sort_options = array('month_mon,month_year' => 'Date', 'month_total_sale_amount' => 'Total Sale Amount','month_total_clicks'=>'Total Genuine Clicks','month_total_sale_clicks'=>'Total Sale Clicks','month_total_fraud_clicks'=>'Total Fraud Clicks','month_total_sale_amount'=>'Total Sale Amount');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE 
									sites_site_id=$ecom_siteid ";
if($_REQUEST['cbo_month']!='')
{
	$where_conditions .=  " AND month_mon = ".$_REQUEST['cbo_month'];
}
if($_REQUEST['cbo_year']!='')
{
	$where_conditions .=  " AND month_year = ".$_REQUEST['cbo_year'];
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
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=costperclick_urls&fpurpose=list_monthly&url_id=".$_REQUEST['url_id']."&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start";
?>
<form name="frmlistmonthly" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="list_monthly" />
<input type="hidden" name="request" value="costperclick_urls" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
<input type="hidden" name="url_id" value="<?=$_REQUEST['url_id']?>" />
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
		  <a href="home.php?request=costperclick_urls&search_name=<?php echo $_REQUEST['pass_seach_name']?>&cbo_keyword=<?php echo $_REQUEST['pass_cbo_keyword']?>&cbo_advertlocation=<?php echo $_REQUEST['pass_cbo_advertlocation']?>&start=<?php echo $_REQUEST['pass_start']?>&pg=<?php echo $_REQUEST['pass_pg']?>&sort_by=<?php echo $_REQUEST['pass_sort_by']?>&sort_order=<?php echo $_REQUEST['pass_sort_order']?>&records_per_page=<?php echo $_REQUEST['pass_records_per_page']?>" class="edittextlinks">Cost Per Clicks</a> <span> Cost Per Click Monthly Report</span></div></td>
    </tr>
	<tr>
	<td align="left" valign="middle" class="helpmsgtd_main" >
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
      <td height="48" >
		  	  
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="53%" valign="top">
			 <div class="editarea_div"> <table width="100%" border="0" cellpadding="1" cellspacing="1">
            <tr>
              <td colspan="2" align="left" valign="middle" class="listingtableheader">Sponsered Link Details </td>
              </tr>
            <tr>
              <td align="left" valign="middle" class="listingtablestyleB"><strong>Keyword</strong></td>
              <td align="left" valign="middle" class="listingtablestyleB"><strong>:</strong> <?php echo $show_keyword?></td>
            </tr>
            <tr>
              <td width="20%" align="left" valign="middle" class="listingtablestyleB"><strong>Advertised  on </strong></td>
              <td width="80%" align="left" valign="middle" class="listingtablestyleB"><strong>:</strong> <?php echo $show_adverton?></td>
              </tr>
            <tr>
              <td align="left" valign="middle" class="listingtablestyleB"><strong>My Page </strong></td>
              <td align="left" valign="middle" class="listingtablestyleB"><strong>:</strong> <?php echo $show_mypage?></td>
            </tr>
            <tr>
              <td align="left" valign="middle" class="listingtablestyleB"><strong>Cost per click </strong></td>
              <td align="left" valign="middle" class="listingtablestyleB"><strong>:</strong> <?php echo display_price($show_rate)?></td>
            </tr>
          </table></div></td>
          </tr>
          <?
	if($numcount)
	  {
	  ?>
	<tr>
	<td  class="sorttd" align="right">
	<?php
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  ?>
	</td>
	</tr>
	<?  }?>
          <tr>
          <td width="47%" valign="top" class="sorttd">
			  <div class="editarea_div">
			  <table width="100%" border="0" cellpadding="1" cellspacing="1">
            <tr>
              <td align="left">Month</td>
              <td align="left">
			  <select name="cbo_month">
			  <option value="">All</option>
			  <option value="1" <?php echo ($_REQUEST['cbo_month']==1)?'selected':''?>>Jan</option>
			  <option value="2" <?php echo ($_REQUEST['cbo_month']==2)?'selected':''?>>Feb</option>
			  <option value="3" <?php echo ($_REQUEST['cbo_month']==3)?'selected':''?>>Mar</option>
			  <option value="4" <?php echo ($_REQUEST['cbo_month']==4)?'selected':''?>>Apr</option>
			  <option value="5" <?php echo ($_REQUEST['cbo_month']==5)?'selected':''?>>May</option>
			  <option value="6" <?php echo ($_REQUEST['cbo_month']==6)?'selected':''?>>Jun</option>
			  <option value="7" <?php echo ($_REQUEST['cbo_month']==7)?'selected':''?>>Jul</option>
			  <option value="8" <?php echo ($_REQUEST['cbo_month']==8)?'selected':''?>>Aug</option>
			  <option value="9" <?php echo ($_REQUEST['cbo_month']==9)?'selected':''?>>Sep</option>
			  <option value="10" <?php echo ($_REQUEST['cbo_month']==10)?'selected':''?>>Oct</option>
			  <option value="11" <?php echo ($_REQUEST['cbo_month']==11)?'selected':''?>>Nov</option>
			  <option value="12" <?php echo ($_REQUEST['cbo_month']==12)?'selected':''?>>Dec</option>
              </select>
			  &nbsp;&nbsp;&nbsp;Year              <select name="cbo_year">
                <option value="">All</option>
                <?php
			  		$date = date('Y',mktime(0,0,0,1,1,date('Y')-1));
					for($i=date('Y');$i>=$date;$i--)
					{
				?>
                <option value="<?php echo $i?>" <?php echo ($_REQUEST['cbo_year']==$i)?'selected':''?>><?php echo $i?></option>
                <?php	
					}
			  ?>
              </select></td>
            
              <td width="12%" align="right">Show</td>
              <td width="41%" align="left"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/>
                  <?=$page_type?>
                Per Page</td>
            
              <td align="left">Sort By</td>
              <td align="left" nowrap="nowrap"><?=$sort_option_txt?>
                in
                <?=$sort_by_txt?>              </td>
              <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;
                  <input name="button5" type="submit" class="red" id="button5" value="Go" />
                <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_COMPANY_TYPES_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
			
          </table>
          </div>
          </td>
        </tr>
      </table></td>
           
    </tr>
    <?php 
			if ($numcount>0)
			{
			?>
				 <tr>
              <td  align="center">
				  <div class="editarea_div">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td  align="right"><input type="button" name="Button" value=" Export " class="red" onclick="document.frmlistmonthly.fpurpose.value='list_export';document.frmlistmonthly.submit();" />
			  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('CLICK_EXPORT_HELP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>			  </td>
            </tr>
              </table>
              </div>
              </td>
              </tr>
              
		 <?php
		 }
		 ?>    
     
    <tr>
      <td class="listingarea">
		  				  <div class="editarea_div">

      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
		   $sql_comptype = "SELECT month_id,costperclick_adverturl_url_id,month_total_clicks,month_total_sale_clicks,month_total_fraud_clicks,
		   									month_total_sale_amount,month_mon,month_year 
										FROM 
											$table_name 
											$where_conditions 
										ORDER BY 
											$sort_by $sort_order 
										LIMIT 
											$start,$records_per_page ";
		   
		   $res = $db->query($sql_comptype);
		   $srno = 1; 
		   $tot_gen_clicks = $tot_fraud_clicks = $tot_sale_amt = $tot_sale_clicks =  $tot_cost = 0;
		   while($row = $db->fetch_array($res))
		   {
				$count_no++;
				$array_values = array();
				if($count_no %2 == 0)
					$class_val="listingtablestyleA";
				else
					$class_val="listingtablestyleB";	
		   		$cur_cost = $row['month_total_clicks']*$show_rate;
		   ?>
			<tr >
			  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?>.</td>
			   <td align="center" valign="middle" class="<?=$class_val;?>" width="12%"><? echo date('M-Y',mktime(0,0,0,$row['month_mon'],1,$row['month_year']))?></td>
			  <td align="center" valign="middle" class="<?=$class_val;?>" width="14%"><? echo $row['month_total_clicks']?></td>
			  <td align="center" valign="middle" class="<?=$class_val;?>" width="14%"><? echo $row['month_total_sale_clicks']?></td>
			 <td align="center" valign="middle" class="<?=$class_val;?>" width="14%"><? echo $row['month_total_fraud_clicks']?></td>
			 <td align="right" valign="middle" class="<?=$class_val;?>" width="12%"><? echo display_price($row['month_total_sale_amount'])?></td>
			 <td align="right" valign="middle" class="<?=$class_val;?>" width="12%"><? echo display_price($cur_cost)?></td>
			 <td align="center" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=costperclick_urls&amp;fpurpose=list_clicks&amp;typ=gen&amp;url_id=<?php echo $_REQUEST['url_id']?>&month_id=<?php echo $row['month_id']?>&pass_seach_name=<?php echo $_REQUEST['pass_seach_name']?>&pass_cbo_keyword=<?php echo $_REQUEST['pass_cbo_keyword']?>&pass_cbo_advertlocation=<?php echo $_REQUEST['pass_cbo_advertlocation']?>&pass_start=<?php echo $_REQUEST['pass_start']?>&pass_pg=<?php echo $_REQUEST['pass_pg']?>&pass_sort_by=<?php echo $_REQUEST['pass_sort_by']?>&pass_sort_order=<?php echo $_REQUEST['pass_sort_order']?>&pass_records_per_page=<?php echo $_REQUEST['pass_records_per_page']?>" title="Click to view Genuine Click Details" class="edittextlink">Genuine Clicks</a> <strong>|</strong> <a href="home.php?request=costperclick_urls&amp;fpurpose=list_clicks&amp;typ=fraud&amp;url_id=<?php echo $_REQUEST['url_id']?>&month_id=<?php echo $row['month_id']?>&pass_seach_name=<?php echo $_REQUEST['pass_seach_name']?>&pass_cbo_keyword=<?php echo $_REQUEST['pass_cbo_keyword']?>&pass_cbo_advertlocation=<?php echo $_REQUEST['pass_cbo_advertlocation']?>&pass_start=<?php echo $_REQUEST['pass_start']?>&pass_pg=<?php echo $_REQUEST['pass_pg']?>&pass_sort_by=<?php echo $_REQUEST['pass_sort_by']?>&pass_sort_order=<?php echo $_REQUEST['pass_sort_order']?>&pass_records_per_page=<?php echo $_REQUEST['pass_records_per_page']?>" title="Click to view Fraud Click Details" class="edittextlink">Fraud Clicks</a></td>
			</tr>
      <?
	  			$tot_gen_clicks += $row['month_total_clicks'];
				$tot_fraud_clicks += $row['month_total_fraud_clicks'];
				$tot_sale_amt += $row['month_total_sale_amount'];
				$tot_sale_clicks += $row['month_total_sale_clicks'];
				$tot_cost			+= $cur_cost;
	  		}
	?>
			<tr >
			 <td align="right" valign="middle" class="listingtableheader" colspan="2">Page Total</td>
			  <td align="center" valign="middle" class="listingtableheader"><? echo $tot_gen_clicks?></td>
			 <td align="center" valign="middle" class="listingtableheader"> <? echo $tot_sale_clicks?></td>
			  <td align="center" valign="middle" class="listingtableheader"> <? echo $tot_fraud_clicks?></td>
			 <td align="right" valign="middle" class="listingtableheader"><? echo display_price($tot_sale_amt)?></td>
			 <td align="right" valign="middle" class="listingtableheader"><? echo display_price($tot_cost)?></td>
			 <td align="center" valign="middle" class="listingtableheader">&nbsp;</td>
			</tr>
	<?php		
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>" >
				  	Cost per click details found.				  </td>
		  </tr>
		<?
		}
		?>	
		
      </table>
      </div>
      </td>
    </tr>
	<tr>
      <td align="right" class="listeditd" colspan="8"> 
	    <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?>   	   </td>
    </tr>
  
  </table>
</form>

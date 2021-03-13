<?php
	/*#################################################################
	# Script Name 	: list_export.php
	# Description 		: Page for listing cost per click exports
	# Coded by 		: Sny
	# Created on		: 01-Nov-2008
	# Modified by		: 
	# Modified On		: 
	#################################################################*/
//Define constants for this page
$help_msg 		= get_help_messages('LIST_VISIT_DETAILS_EXPORT_MESS1');
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
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
?>
<form name="frmlistCallback" action="costperclick_export.php" method="post" >	
<input type="hidden" name="cur_mod" value="check" />
<input type="hidden" name="url_id" value="<?=$_REQUEST['url_id']?>" />
<input type="hidden" name="month_id" value="<?=$_REQUEST['month_id']?>" />
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
  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td width="100%" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div">
	  <a href="home.php?request=costperclick_urls&search_name=<?php echo $_REQUEST['pass_seach_name']?>&cbo_keyword=<?php echo $_REQUEST['pass_cbo_keyword']?>&cbo_advertlocation=<?php echo $_REQUEST['pass_cbo_advertlocation']?>&start=<?php echo $_REQUEST['pass_start']?>&pg=<?php echo $_REQUEST['pass_pg']?>&sort_by=<?php echo $_REQUEST['pass_sort_by']?>&sort_order=<?php echo $_REQUEST['pass_sort_order']?>&records_per_page=<?php echo $_REQUEST['pass_records_per_page']?>">Cost Per Clicks </a>
	 <?php
	 	if($_REQUEST['month_id'])
		{
			if ($_REQUEST['typ']=='gen')
				$typ = 'Genuine Clicks';
			else
				$typ = 'Fraud Clicks';
		?>
			<a href="home.php?request=costperclick_urls&fpurpose=list_clicks&typ=gen&url_id=<?php echo $_REQUEST['url_id']?>&month_id=<?php echo $_REQUEST['month_id']?>&pass_seach_name=<?php echo $_REQUEST['pass_seach_name']?>&pass_cbo_keyword=<?php echo $_REQUEST['pass_cbo_keyword']?>&pass_cbo_advertlocation=<?php echo $_REQUEST['pass_cbo_advertlocation']?>&pass_start=<?php echo $_REQUEST['pass_start']?>&pass_pg=<?php echo $_REQUEST['pass_pg']?>&pass_sort_by=<?php echo $_REQUEST['pass_sort_by']?>&pass_sort_order=<?php echo $_REQUEST['pass_sort_order']?>&pass_records_per_page=<?php echo $_REQUEST['pass_records_per_page']?>">List of <?php echo  $typ?> for the selected month</a>  
		<?php	
		}
		else
		{
		?>
			<a href="home.php?request=costperclick_urls&fpurpose=list_monthly&url_id=<?php echo $_REQUEST['url_id']?>&pass_seach_name=<?php echo $_REQUEST['pass_seach_name']?>&pass_cbo_keyword=<?php echo $_REQUEST['pass_cbo_keyword']?>&pass_cbo_advertlocation=<?php echo $_REQUEST['pass_cbo_advertlocation']?>&pass_start=<?php echo $_REQUEST['pass_start']?>&pass_pg=<?php echo $_REQUEST['pass_pg']?>&pass_sort_by=<?php echo $_REQUEST['pass_sort_by']?>&pass_sort_order=<?php echo $_REQUEST['pass_sort_order']?>&pass_records_per_page=<?php echo $_REQUEST['pass_records_per_page']?>">Cost Per Click Monthly Report</a>  	
		<?php
		}
	 ?>
	  <span>Export Visit Details</span></td>
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
			
		if($_REQUEST['url_id'] and $_REQUEST['month_id'])
		{
			$sort_options = array('time_ipaddress' => 'IP Address', 'time_time' => 'Date');
			$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);

		?>
		<tr>
      <td >
	  <div class="editarea_div">
	  <table width="100%" border="0" cellpadding="1" cellspacing="1">
        <tr>
          <td align="left" valign="middle" class="listingtableheader">Sponsered Link Details </td>
          <td align="left" valign="middle" class="listingtableheader">&nbsp;</td>
          <td colspan="2" align="left" valign="middle" class="listingtableheader">Export Criteria </td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="listingtablestyleB"><strong>Keyword</strong></td>
          <td width="34%" align="left" valign="middle" class="listingtablestyleB"><strong>:</strong> <?php echo $show_keyword?></td>
          <td width="13%" align="left" valign="middle" class="listingtablestyleB">
		  
		  IP Address </td>
          <td width="33%" align="left" valign="middle" class="listingtablestyleB"><input name="search_ipaddress" type="text" id="search_ipaddress" value="<?php echo $_REQUEST['search_ipaddress']?> " /></td>
        </tr>
        <tr>
          <td width="20%" align="left" valign="middle" class="listingtablestyleB"><strong>Advertised  on </strong></td>
          <td align="left" valign="middle" class="listingtablestyleB"><strong>:</strong> <?php echo $show_adverton?></td>
          <td align="left" valign="middle" class="listingtablestyleB">Dates Between </td>
          <td align="left" valign="middle" class="listingtablestyleB"><input name="start_date" type="text" class="textfeild" value="<?=$_REQUEST['search_start_date']?>" size="10" />
            <a href="javascript:show_calendar('document.frmlistCallback.start_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a>&nbsp;AND&nbsp;
            <input name="end_date" type="text" class="textfeild" value="<?=$_REQUEST['search_end_date']?>" size="10" />
            <a href="javascript:show_calendar('document.frmlistCallback.end_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a>&nbsp;(dd-mm-yyyy)<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_VISIT_DETAILS_EXPORT_DATES')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="listingtablestyleB"><strong>My Page </strong></td>
          <td align="left" valign="middle" class="listingtablestyleB"><strong>:</strong> <?php echo $show_mypage?></td>
          <td align="left" valign="middle" class="listingtablestyleB">Sort By</td>
          <td align="left" valign="middle" class="listingtablestyleB"><?=$sort_option_txt?>
            in
              <?=$sort_by_txt?>
&nbsp;&nbsp;&nbsp;&nbsp;</td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="listingtablestyleB"><strong>Cost per click </strong></td>
          <td align="left" valign="middle" class="listingtablestyleB"><strong>:</strong> <?php echo display_price($show_rate)?></td>
          <td align="left" valign="middle" class="listingtablestyleB">Export Type </td>
          <td align="left" valign="middle" class="listingtablestyleB">
		  <select name="click_type">
            <option value="-1">All Clicks</option>
            <option value="0" <?php echo ($_REQUEST['typ']=='gen')?'selected':''?>>Genuine Clicks</option>
            <option value="1" <?php echo ($_REQUEST['typ']=='fraud')?'selected':''?>>Fraud Clicks</option>
          </select>
          </td>
        </tr>
      </table>
	  </div>
	  
	  </td>
    </tr>
		<?php
		}
		elseif($_REQUEST['url_id'])
		{			
			$sort_options = array('month_mon,month_year' => 'Date', 'month_total_sale_amount' => 'Total Sale Amount','month_total_clicks'=>'Total Genuine Clicks','month_total_sale_clicks'=>'Total Sale Clicks','month_total_fraud_clicks'=>'Total Fraud Clicks','month_total_sale_amount'=>'Total Sale Amount');
			$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);

		 ?> 
    <tr>
      <td >
	  <div class="editarea_div">
	  <table width="100%" border="0" cellpadding="1" cellspacing="1">
        <tr>
          <td align="left" valign="middle" class="listingtableheader">Sponsered Link Details </td>
          <td align="left" valign="middle" class="listingtableheader">&nbsp;</td>
          <td colspan="2" align="left" valign="middle" class="listingtableheader">Export Criteria </td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="listingtablestyleB"><strong>Keyword</strong></td>
          <td width="34%" align="left" valign="middle" class="listingtablestyleB"><strong>:</strong> <?php echo $show_keyword?></td>
          <td width="13%" align="left" valign="middle" class="listingtablestyleB">
		  
		  Month</td>
          <td width="33%" align="left" valign="middle" class="listingtablestyleB"><select name="cbo_month">
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
&nbsp;&nbsp;&nbsp;Year
<select name="cbo_year">
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
        </tr>
        <tr>
          <td width="20%" align="left" valign="middle" class="listingtablestyleB"><strong>Advertised  on </strong></td>
          <td align="left" valign="middle" class="listingtablestyleB"><strong>:</strong> <?php echo $show_adverton?></td>
          <td align="left" valign="middle" class="listingtablestyleB">Sort By</td>
          <td align="left" valign="middle" class="listingtablestyleB"><?=$sort_option_txt?>
in
  <?=$sort_by_txt?>
&nbsp;&nbsp;&nbsp;&nbsp;</td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="listingtablestyleB"><strong>My Page </strong></td>
          <td colspan="3" align="left" valign="middle" class="listingtablestyleB"><strong>:</strong> <?php echo $show_mypage?></td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="listingtablestyleB"><strong>Cost per click </strong></td>
          <td colspan="3" align="left" valign="middle" class="listingtablestyleB"><strong>:</strong> <?php echo display_price($show_rate)?></td>
        </tr>
      </table>
	  </div>
	  
	  </td>
    </tr>
    <?php
		}
		if($_REQUEST['month_id'])
		{
	?>    
    <tr>
      <td class="listingarea">&nbsp;</td>
    </tr>
	<?php
	}
	?>
	 <tr >
	  <td align="right" valign="middle" class="listeditd">
	  <div class="editarea_div">
	  <input name="button7" type="submit" class="red" id="button7" value="Click to Download File" /><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_VISIT_DETAILS_EXPORT_DOWNLOAD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
	  </div>
	  </td>
	 </tr>
    </table>
</form>

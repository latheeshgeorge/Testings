<?php
/*#################################################################
# Script Name 	: list_summary.php
# Description 		: Page for listing cost per click summary report
# Coded by 		: SNY
# Created on		: 31-Oct-2008
#################################################################*/
//Define constants for this page
$table_name='costperclick_adverturl a,costperclick_keywords b,costperclick_advertplacedon c ';
$page_type='Cost Per Clicks';
$help_msg = get_help_messages('LIST_COMPANY_TYPES_MESS1');
$table_headers = array('Slno','Keyword (Cost per click)','Advertised On','My Page','Total Genuine Clicks','Total Fraud Clicks','Total Sale Amount','Total Cost Per Click','Hidden'	,'Action');
$header_positions=array('left','left','left','left','center','right','right','right','center','center');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('search_name','cbo_keyword','cbo_advertlocation');
foreach($search_fields as $v) {
	$query_string .= "$v=${$v}&";#For passing searh terms to javascript for passing to different pages.
}
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'b.keyword_word':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('b.keyword_word' => 'Keyword', 'c.advertplace_name' => 'Advertised On','a.url_total_clicks'=>'Total Genuine Clicks','a.url_total_fraud_clicks'=>'Total Fraud Clicks','a.url_total_sale_amount'=>'Total Sale Amount');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE 
									a.sites_site_id=$ecom_siteid 
									AND a.costperclick_keywords_keyword_id=b.keyword_id 
									AND a.costperclick_adverplaced_on_advertplace_id = c.advertplace_id 
									";
// case if search name is given 
if(trim($_REQUEST['search_name'])!='') {
	$where_conditions .= " AND ( 
												b.keyword_word LIKE '%".add_slash($_REQUEST['search_name'])."%' 
												OR c.advertplace_name LIME '%".add_slash($_REQUEST['search_name'])."%' 
												OR a.url_mypage LIKE '%".add_slash($_REQUEST['search_name'])."%' 
											) ";
}
// Case if keyword is selected
if(trim($_REQUEST['cbo_keyword'])!=0) {
	$where_conditions .= " AND b.keyword_id = ".$_REQUEST['cbo_keyword']." ";
}

// Case if cbo_advertlocation on  is selected
if(trim($_REQUEST['cbo_advertlocation'])!=0) {
	$where_conditions .= " AND  c.advertplace_id = ".$_REQUEST['cbo_advertlocation']." ";
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
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=costperclick_report&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start";
?>
<form name="frmlistcomptype" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="costperclick_report" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
<input type="hidden" name="pass_seach_name" value="<?=$_REQUEST['pass_seach_name']?>" />
<input type="hidden" name="pass_cbo_keyword" value="<?=$_REQUEST['pass_cbo_keyword']?>" />
<input type="hidden" name="pass_cbo_advertlocation" value="<?=$_REQUEST['pass_cbo_advertlocation']?>" />
<input type="hidden" name="pass_start" value="<?=$_REQUEST['pass_start']?>" />
<input type="hidden" name="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
<input type="hidden" name="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
<input type="hidden" name="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />

  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td align="left" valign="middle" class="treemenutd">Cost Per Click Report Summary</td>
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
      <td height="48" class="sorttd" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="53%" valign="top"><table width="100%" border="0" cellpadding="1" cellspacing="1">
            <tr>
              <td width="20%" align="left" valign="middle">Text like  </td>
              <td width="80%" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"></td>
              </tr>
            <tr>
              <td align="left" valign="middle">Keyword</td>
              <td align="left" valign="middle">
			  <?php
			  	// Get the list of keywords set for current site
				$sql_kw = "SELECT keyword_id,keyword_word 
										FROM 
											costperclick_keywords  
										WHERE 
											sites_site_id = $ecom_siteid 
										ORDER BY 
											keyword_word";
				$ret_kw = $db->query($sql_kw);
				$kw_arr = array(0=>'Any');
				if ($db->num_rows($ret_kw))
				{
					while ($row_kw = $db->fetch_array($ret_kw))
					{
						$kw_arr[$row_kw['keyword_id']] = stripslashes($row_kw['keyword_word']);
					}
				}
				echo generateselectbox('cbo_keyword',$kw_arr,$_REQUEST['cbo_keyword']);
			  ?>              </td>
              </tr>
            <tr>
              <td align="left" valign="middle">Advertised on </td>
              <td align="left" valign="middle">
			  <?php
			  	// Get the list of advertised locations set for current site
				$sql_adv = "SELECT advertplace_id,advertplace_name 
										FROM 
											costperclick_advertplacedon  
										WHERE 
											sites_site_id = $ecom_siteid 
										ORDER BY 
											advertplace_name";
				$ret_adv = $db->query($sql_adv);
				$adv_arr = array(0=>'Any');
				if ($db->num_rows($ret_adv))
				{
					while ($row_adv = $db->fetch_array($ret_adv))
					{
						$adv_arr[$row_adv['advertplace_id']] = stripslashes($row_adv['advertplace_name']);
					}
				}
				echo generateselectbox('cbo_advertlocation',$adv_arr,$_REQUEST['cbo_advertlocation']);
			  ?></td>
              </tr>
          </table></td>
          <td width="47%" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="12%" align="left">Show</td>
              <td width="41%" align="left"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/>
                  <?=$page_type?>
                Per Page</td>
              <td width="47%" align="left">&nbsp;</td>
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
          </table></td>
        </tr>
      </table></td>
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
      <td class="listingarea">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
		   $sql_comptype = "SELECT a.url_id, a.url_mypage,a.url_hidden,a.url_total_clicks,a.url_total_sale_clicks,a.url_total_fraud_clicks,a.url_total_sale_amount,
												b.keyword_word,c.advertplace_name,a.url_setting_rateperclick  
										FROM 
											$table_name 
											$where_conditions 
										ORDER BY 
											$sort_by $sort_order 
										LIMIT 
											$start,$records_per_page ";
		   
		   $res = $db->query($sql_comptype);
		   $srno = 1; 
		   $tot_gen_clicks = $tot_fraud_clicks = $tot_sale_amt = $tot_cost= 0;
		   while($row = $db->fetch_array($res))
		   {
				$count_no++;
				$array_values = array();
				if($count_no %2 == 0)
					$class_val="listingtablestyleA";
				else
					$class_val="listingtablestyleB";	
		   		$cur_cost = ($row['url_total_clicks']*$row['url_setting_rateperclick']);
		   ?>
			<tr >
			  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?>.</td>
			  <td align="left" valign="middle" class="<?=$class_val;?>" width="15%"><a href="" class="edittextlink" title="Click to go to url managing section"><? echo $row['keyword_word']?></a> (<?php echo display_price($row['url_setting_rateperclick'])?> )</td>
			   <td align="left" valign="middle" class="<?=$class_val;?>" width="15%"><? echo $row['advertplace_name']?></td>
			 <td align="left" valign="middle" class="<?=$class_val;?>" width="16%"><? echo $row['url_mypage']?></td>
			  <td align="center" valign="middle" class="<?=$class_val;?>" width="10%"><? echo $row['url_total_clicks']?></td>
			 <td align="center" valign="middle" class="<?=$class_val;?>" width="9%"><? echo $row['url_total_fraud_clicks']?></td>
			 <td align="right" valign="middle" class="<?=$class_val;?>" width="9%"><? echo display_price($row['url_total_sale_amount'])?></td>
			  <td align="right" valign="middle" class="<?=$class_val;?>" width="12%"><? echo display_price($cur_cost)?></td>
			 <td align="center" valign="middle" class="<?=$class_val;?>" width="5%"><? echo ($row['url_hidden']==1)?'Yes':'No'?></td>
			 <td align="center" valign="middle" class="<?=$class_val;?>" width="8%"><a href="home.php?request=costperclick_report&fpurpose=list_monthly&url_id=<?php echo $row['url_id']?>&pass_seach_name=<?php echo $_REQUEST['search_name']?>&pass_cbo_keyword=<?php echo $_REQUEST['cbo_keyword']?>&cbo_advertlocation=<?php echo $_REQUEST['cbo_advertlocation']?>&pass_start=<?php echo $start?>&pass_pg=<?php echo $pg?>&pass_sort_by=<?php $sort_by?>&pass_sort_order=<?php $sort_order?>&pass_records_per_page=<?php echo $records_per_page?>" title="Click to view monthly details" class="edittextlink">Monthly View</a></td>
			</tr>
      <?
	  			$tot_gen_clicks += $row['url_total_clicks'];
				$tot_fraud_clicks += $row['url_total_fraud_clicks'];
				$tot_sale_amt += $row['url_total_sale_amount'];
				$tot_cost		+= $cur_cost;
	  		}
	?>
			<tr >
			 <td align="right" valign="middle" class="listingtableheader" colspan="4">Page Total</td>
			  <td align="center" valign="middle" class="listingtableheader"><? echo $tot_gen_clicks?></td>
			 <td align="center" valign="middle" class="listingtableheader"><? echo $tot_fraud_clicks?></td>
			 <td align="right" valign="middle" class="listingtableheader"><? echo display_price($tot_sale_amt)?></td>
			 <td align="right" valign="middle" class="listingtableheader"><?php echo display_price($tot_cost)?></td>
			 <td align="center" valign="middle" class="listingtableheader" >&nbsp;</td>
			  <td align="center" valign="middle" class="listingtableheader" >&nbsp;</td>
			</tr>
	<?php		
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>" >
				  	No Cost per click details found.				  </td>
		  </tr>
		<?
		}
		?>	
      </table></td>
    </tr>
	
  <tr>
      <td align="center" class="listeditd"> 
	    <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?>   	   </td>
    </tr>
  </table>
</form>

<?php
/*#################################################################
# Script Name 	: list_payonaccount_customers.php
# Description 		: Page for listing pay on account customers
# Created by 		: Sny
# Created on		: 16-Oct-2008	
# Modified by 		: 
# Modified on		: 
#################################################################*/
//Define constants for this page
$page_type			= 'Pay on Account Details';
$help_msg 			= get_help_messages('LIST_PAYONACCOUNT_MESS1');
$table_headers 		= array('Slno.','Customer Name','Billing Cycle','Credit Limit','Credits Used','Credits Remaining','Action');
$header_positions	= array('left','left','left','right','right','right','center');
$colspan 			= count($table_headers);

//#Search terms
$search_fields = array('txt_name','cbo_selectlimit','limit_from','limit_to','cbo_payon_status');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'customer_fname':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('customer_fname' => 'Customer Name', 'customer_payonaccount_billcycle_day' => 'Billing Cycle','customer_payonaccount_maxlimit'=>'Credit Limit','customer_payonaccount_usedlimit'=>'Credits Used');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

// Get the ids of customers from order_payonaccount_details table
$sql_dist = "SELECT distinct customers_customer_id 
						FROM 
							order_payonaccount_details 
						WHERE 
							sites_site_id = $ecom_siteid";
$ret_dist = $db->query($sql_dist);
$distcust_arr = array();
if ($db->num_rows($ret_dist))
{	
	while ($row_dist = $db->fetch_array($ret_dist))
	{	
		$distcust_arr[] = $row_dist['customers_customer_id'];
	}
}
if(count($distcust_arr))
	$cust_str = " AND customer_id IN (".implode(',',$distcust_arr).")";
else
	$cust_str = " AND customer_id IN (-1)";
//#Search Options
$where_conditions = " WHERE 
						a.sites_site_id=$ecom_siteid  $cust_str ";
if($_REQUEST['txt_name'])
{
	$where_conditions .= "AND (customer_fname LIKE '%".add_slash($_REQUEST['txt_name'])."%' 
	OR 
	customer_surname LIKE '%".add_slash($_REQUEST['txt_name'])."%' OR
	concat(customer_fname,' ',customer_surname) LIKE '%".add_slash($_REQUEST['txt_name'])."%'
	OR (customer_email_7503 LIKE '%".add_slash($_REQUEST['txt_name'])."%' ))";
}
if($_REQUEST['cbo_selectlimit']!='')
{
	$min_exists 		= $max_exists = false;
	$min 				= trim($_REQUEST['limit_from']);
	$max				= trim($_REQUEST['limit_to']);
	$between_column 	= '';
	if ($min!='')
	{
		if (is_numeric($min))
		{
			$min_exists = true;
		}	
		else
			$_REQUEST['limit_from'] = '';
	}	
	if ($max!='')
	{
		if (is_numeric($max))
		{
			$max_exists = true;
		}	
		else
			$_REQUEST['limit_to'] = '';
	}
	if ($min_exists or $max_exists)
	{
		switch($_REQUEST['cbo_selectlimit'])
		{
			case 'max_credit':
				$between_column = 'customer_payonaccount_maxlimit';
			break;
			case 'used_credit':
				$between_column = 'customer_payonaccount_usedlimit';
			break;
		};
		if ($min_exists and $max_exists)
		{
			$where_conditions .=" AND ($between_column BETWEEN $min and $max) ";
		}
		elseif ($min_exists and !$max_exists)
		{
			$where_conditions .=" AND $between_column >= $min ";
		}
		elseif (!$min_exists and $max_exists)
		{
			$where_conditions .=" AND $between_column <= $max ";
		}
	}
}
if ($_REQUEST['cbo_payon_status']!='')
{
	if($_REQUEST['cbo_payon_status']!='ANY')
		$where_conditions .= " AND customer_payonaccount_status = '".$_REQUEST['cbo_payon_status']."' ";
}


//#Select condition for getting total count
$sql_count = "SELECT distinct a.customer_id,(a.customer_payonaccount_maxlimit-a.customer_payonaccount_usedlimit) as  remaining  FROM customers a $where_conditions";
$res_count = $db->query($sql_count);
$numcount = $db->num_rows($res_count);
/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = (is_numeric($_REQUEST['records_per_page']) and $_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:25;#Total records shown in a page
$pg = ($_REQUEST['search_click'])?1:$_REQUEST['pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
$pages = ceil($numcount / $records_per_page);#Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}
$start = ($pg - 1) * $records_per_page;#Starting record.
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
/////////////////////////////////////////////////////////////////////////////////////
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=payonaccount&records_per_page=$records_per_page&start=$start";
?>
<form name="frm_payonaccount" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="payonaccount" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> List Pay on Account Details</span></div></td>
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
	  if($numcount)
	  {
	    
		 ?> 
    <tr>
		<td align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td>
	</tr>
	<?php
	  }
	?>
	<tr>
      <td height="48" class="sorttd" >
	  <div class="sorttd_div">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="100%" align="left"><table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="15%">Customer Name or  Email Id</td>
              <td width="19%" align="left"><input type="text" name="txt_name" value="<?=$_REQUEST['txt_name']?>" /></td>
              <td width="40%" align="left"><select name="cbo_selectlimit">
			  <option value="">-- Select --</option>
                <option value="max_credit" <?php echo ($_REQUEST['cbo_selectlimit']=='max_credit')?'selected':''?>>Credit Limit Between</option>
                <option value="used_credit" <?php echo ($_REQUEST['cbo_selectlimit']=='used_credit')?'selected':''?>>Used Credit Between</option>
              </select>              
                <input name="limit_from" id="limit_from" type="text" size="12" value="<?php echo $_REQUEST['limit_from']?>" /> 
                and
                  <input name="limit_to" id="limit_to" type="text" size="12" value="<?php echo $_REQUEST['limit_to']?>" /></td>
              <td>Current Pay on Account Status 
                <select name="cbo_payon_status">
                  <option value="ANY" <?php echo ($_REQUEST['cbo_payon_status']=='ANY')?'selected':''?>>Any</option>
                  <option value="ACTIVE" <?php echo ($_REQUEST['cbo_payon_status']=='ACTIVE')?'selected':''?>>Active</option>
                  <option value="INACTIVE" <?php echo ($_REQUEST['cbo_payon_status']=='INACTIVE')?'selected':''?>>Inactive</option>
                </select>                </td>
            </tr>
            <tr>
              <td align="left">Records Per Page                 </td>
              <td align="left"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
              <td align="left">Sort By
                <?=$sort_option_txt?>
in
<?=$sort_by_txt?></td>
              <td align="right"><input name="main_go" type="submit" class="red" id="main_go" value="Go" />
                <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PAYON_ACC_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
            </table></td>
          </tr>
      </table></div></td>
    </tr>
     
    <tr>
      <td class="listingarea">
	  <div class="listingarea_div">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	  	$sql_payoncust = "SELECT a.customer_id,a.customer_title,a.customer_fname,a.customer_mname,a.customer_surname,a.customer_payonaccount_status,
                                            a.customer_payonaccount_maxlimit,a.customer_payonaccount_usedlimit,a.customer_payonaccount_billcycle_day,
                                            a.customer_payonaccount_laststatementdate,
                                            (a.customer_payonaccount_maxlimit-a.customer_payonaccount_usedlimit) as remaining ,
                                            customer_payonaccount_billcycle_month_duration 
                                    FROM 
                                            customers a 
                                    $where_conditions 
                                    ORDER BY 
                                            $sort_by $sort_order 
                                    LIMIT 
                                            $start,$records_per_page ";
	   
	   $res = $db->query($sql_payoncust);
	   $srno = 1; 
	   $tot_used = 0;
	   while($row = $db->fetch_array($res))
	   {
			$count_no++;
			$array_values = array();
			if($count_no %2 == 0)
				$class_val="listingtablestyleA";
			else
				$class_val="listingtablestyleB";	
			$tot_used += $row['customer_payonaccount_usedlimit'];
	   
	   ?>
        <tr >
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="5%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>" width="20%"><a href="home.php?request=customer_search&fpurpose=edit&checkbox[]=<?php echo $row['customer_id']?>"  class="edittextlink" onclick="show_processing()"><? echo stripslashes($row['customer_title']).stripslashes($row['customer_fname']).' '.stripslashes($row['customer_surname'])?></a></td>
		 <td align="left" valign="middle" class="<?=$class_val;?>"  width="15%">Day <? echo $row['customer_payonaccount_billcycle_day'];
                if($row['customer_payonaccount_billcycle_month_duration']==1)
                    echo ' <strong>(Every Month)</strong>';
                else
                    echo " <strong>(Once in Every ".$row['customer_payonaccount_billcycle_month_duration']." Months)</strong>";
                 ?></td>
          <?php /*?><td align="left" valign="middle" class="<?=$class_val;?>"  width="10%">
		  <?php 
		  if($row['customer_payonaccount_laststatementdate']=='0000-00-00')
		  	echo 'No Statements found';
		  else
		  {
			 $lastdate_arr = explode('-',$row['customer_payonaccount_laststatementdate']);
			 echo $lastdate_arr[2].'-'.$month_arr[$lastdate_arr[1]].'-'.$lastdate_arr[0];
			  
			}  
			  ?></td><?php */?>
		   <td align="right" valign="middle" class="<?=$class_val;?>"  width="10%"><?php echo  display_price($row['customer_payonaccount_maxlimit'])?></td>
		   <td align="right" valign="middle" class="<?=$class_val;?>" width="10%"><?php echo  display_price($row['customer_payonaccount_usedlimit'])?></td>
		   <td align="right" valign="middle" class="<?=$class_val;?>" width="15%"><?php echo  display_price($row['remaining'])?></td>
		    <td align="center" valign="middle" class="<?=$class_val;?>" width="15%"><a href="home.php?request=payonaccount&fpurpose=account_summary&customer_id=<?php echo $row['customer_id']?>&txt_name=<?=$_REQUEST['txt_name']?>&max_credit=<?=$_REQUEST['max_credit']?>&used_credit=<?=$_REQUEST['used_credit']?>&limit_from=<?=$_REQUEST['limit_from']?>&limit_to=<?=$_REQUEST['limit_to']?>&cbo_payon_status=<?=$_REQUEST['cbo_payon_status']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&cbo_selectlimit=<?=$_REQUEST['cbo_selectlimit']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>" class="edittextlink">Account Summary</a></td>
        </tr>
      <?
	  	}
	  ?>
		  <tr >
			  <td colspan="4" align="right" valign="middle" class="shoppingcartpriceB">Total Used </td>
		     <td align="right" valign="middle" class="shoppingcartpriceB" width="10%"><?php echo  display_price($tot_used)?></td>
			   <td colspan="3" align="right" valign="middle" class="shoppingcartpriceB"></td>
		  </tr>
	  <?php
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="8" >
				  	No pay	on Account	details	found	  </td>
		  </tr>
		<?
		}
		?>	
		
      </table>
	  </div></td>
    </tr>
	<tr>
      <td align="right" valign="middle" colspan="2" class="listing_bottom_paging">
	    <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?>   	   </td>
    </tr>
  
  </table>
</form>

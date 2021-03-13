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
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlisturls,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlisturls,\'checkbox[]\')"/>','Slno','Keyword','Advertised On','Cost Per Click','Genuine Clicks','Fraud Clicks','Sale Amount','Total Cost Per Click','Hidden','Action');
$header_positions=array('left','left','left','left','right','right','right','right','right','center');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('search_name','cbo_keyword','cbo_advertlocation');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'b.keyword_word':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('b.keyword_word' => 'Keyword', 'c.advertplace_name' => 'Advertised On');
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
												OR c.advertplace_name LIKE '%".add_slash($_REQUEST['search_name'])."%' 
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
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=costperclick_urls&records_per_page=$records_per_page&start=$start";
?>
<script language="javascript">


function edit_selected()
{
	
	len=document.frmlisturls.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlisturls.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				bow_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one URL ');
	}
	else if(cnt>1 ){
		alert('Please select only one URL to edit');
	}
	else
	{
		show_processing();
		document.frmlisturls.fpurpose.value='edit';
		document.frmlisturls.url_id.value=bow_id;
		document.frmlisturls.submit();
	}
	
	
}
function ajax_return_contents() 
{
	var ret_val='';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val = req.responseText;
			document.getElementById('maincontent').innerHTML=ret_val;
			hide_processing();
		}
		else
		{
			 show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}
function call_ajax_delete(search_name,cbo_keyword,cbo_advertlocation,sortby,sortorder,recs,start,pg)
{
	var atleastone 	= 0;
	var del_ids 	= '';
	var qrystr				= 'search_name='+search_name+'&cbo_keyword='+cbo_keyword+'&cbo_advertlocation='+cbo_advertlocation+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg; 
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlisturls.elements.length;i++)
	{
		if (document.frmlisturls.elements[i].type =='checkbox' && document.frmlisturls.elements[i].name=='checkbox[]')
		{

			if (document.frmlisturls.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlisturls.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select URL to delete');
	}
	else
	{
		if(confirm('If You delete this URL all details linked with this URL will also delete. Are you sure you want to delete selected URL?'))
		{
			show_processing();
			Handlewith_Ajax('services/costperclick_urls.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function call_ajax_changestatus(search_name,cbo_keyword,cbo_advertlocation,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var curid				= 0;
	var group_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frmlisturls.cbo_changehide.value;
	var qrystr				= 'search_name='+search_name+'&cbo_keyword='+cbo_keyword+'&cbo_advertlocation='+cbo_advertlocation+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlisturls.elements.length;i++)
	{
		if (document.frmlisturls.elements[i].type =='checkbox' && document.frmlisturls.elements[i].name=='checkbox[]')
		{

			if (document.frmlisturls.elements[i].checked==true)
			{
				atleastone = 1;
				if (group_ids!='')
					group_ids += '~';
				 group_ids += document.frmlisturls.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the Urls to change the status');
	}
	else
	{
		if(confirm('Change Status of Seleted Menu(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/costperclick_urls.php','fpurpose=change_hide&'+qrystr+'&group_ids='+group_ids);
		}	
	}	
}
</script>
<form name="frmlisturls" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="costperclick_urls" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
<input type="hidden" name="url_id" id="url_id" value="<?=$_REQUEST['url_id']?>" />

  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span>Cost Per Clicks </span></div></td>
    </tr>
	<tr>
		<td align="left" valign="middle" class="helpmsgtd_main" colspan="3">
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
          			<td colspan="3" align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
          		</tr>
		 <?php
		 	}
		 ?>
		 <tr>
	<td align="right" class="sorttd" colspan="3">
		<?php
	 if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?>
	</td>
	</tr> 
    <tr>
      <td height="48" colspan="3" class="sorttd" >
		  		   <div class="sorttd_div">
		 <table width="100%" border="0" cellspacing="0" cellpadding="0">
		 <tr>
		   <td width="7%">Text like </td>
		   <td width="18%"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>" /></td>
		   <td width="10%">Advertised on</td>
		   <td colspan="4"><?php
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
		 <tr>
		   <td>Keyword</td>
		   <td><?php
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
			  ?></td>
		   <td>Records Per Page </td>
		   <td width="12%"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
		   <td width="5%">Sort By</td>
		   <td width="16%"><?=$sort_option_txt?>&nbsp;&nbsp;in&nbsp;&nbsp;<?=$sort_by_txt?></td>
		   <td width="5%" align="right"><input name="button5" type="submit" class="red" id="button5" value="Go" />
             <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_COMPANY_TYPES_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		 </tr>
		 </table>	
		           </div></td>
    </tr>
        <tr>
      <td  class="listingarea">
	  <div class="listingarea_div">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
    <tr>
      <td  align="center" class="listeditd">
	  <a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=costperclick_urls&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&cbo_advertlocation=<?=$_REQUEST['cbo_advertlocation']?>&cbo_keyword=<?=$_REQUEST['cbo_keyword']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist"> Edit </a> 
	  <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $_REQUEST['cbo_keyword']?>','<?php echo $_REQUEST['cbo_advertlocation']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?></td>     
      <td  align="right" class="listeditd" colspan="2">
	  <?
	  if($numcount)
	  {
	  ?>
	  Change Status  &nbsp;
         <select name="cbo_changehide" class="dropdown" id="cbo_changehide">
            <option value="1">Yes</option>
			<option value="0">No</option>
        </select> &nbsp; 
		<input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['search_name']?>','<?php echo $_REQUEST['cbo_keyword']?>','<?php echo $_REQUEST['cbo_advertlocation']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" /> &nbsp;&nbsp;
		<?
		}
		?>
	  </td>
    </tr>
     
    <tr>
      <td colspan="3" class="listingarea">
      <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">
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
		   		
				$url_id = $row['url_id'];
				$cur_cost = ($row['url_total_clicks']*$row['url_setting_rateperclick']);
		   ?>
			<tr >
			  <td align="left" valign="middle" class="<?=$class_val;?>"  width="6%"><input name="checkbox[]" value="<? echo $row['url_id'];?>" type="checkbox"></td>
			  <td align="left" valign="middle" class="<?=$class_val;?>"  width="1%"><?php echo $srno++;?>.</td>
			  <td align="left" valign="middle" class="<?=$class_val;?>" width="10%"><a href="home.php?request=costperclick_urls&fpurpose=edit&url_id=<?=$url_id?>&search_name=<?=$_REQUEST['search_name']?>&cbo_advertlocation=<?=$_REQUEST['cbo_advertlocation']?>&cbo_keyword=<?=$_REQUEST['cbo_keyword']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()" class="edittextlink" title="Click to go to url managing section"><? echo $row['keyword_word']?></a></td>
			   <td align="left" valign="middle" class="<?=$class_val;?>" width="10%"><? echo $row['advertplace_name'];?></td>
			
			  <td align="right" valign="middle" class="<?=$class_val;?>" width="8%"><?php echo display_price($row['url_setting_rateperclick']);?></td>
			   <td align="right" valign="middle" class="<?=$class_val;?>" width="10%"><?php echo $row['url_total_clicks'];?></td>
			    <td align="right" valign="middle" class="<?=$class_val;?>" width="8%"><?php echo $row['url_total_fraud_clicks'];?></td>
				 <td align="right" valign="middle" class="<?=$class_val;?>" width="10%"><?php echo display_price($row['url_total_sale_amount']);?></td>
				  <td align="right" valign="middle" class="<?=$class_val;?>" width="11%"><?php echo display_price($cur_cost);?></td>
			 
			 <td align="center" valign="middle" class="<?=$class_val;?>" width="11%"><? echo ($row['url_hidden']==1)?'Yes':'No'?></td>
			 <td align="center" valign="middle" class="<?=$class_val;?>" width="15%"><a href="home.php?request=costperclick_urls&fpurpose=list_monthly&url_id=<?php echo $row['url_id']?>&pass_seach_name=<?php echo $_REQUEST['search_name']?>&pass_cbo_keyword=<?php echo $_REQUEST['cbo_keyword']?>&pass_cbo_advertlocation=<?php echo $_REQUEST['cbo_advertlocation']?>&pass_start=<?php echo $start?>&pass_pg=<?php echo $pg?>&pass_sort_by=<?php $sort_by?>&pass_sort_order=<?php $sort_order?>&pass_records_per_page=<?php echo $records_per_page?>" title="Click to view monthly details" class="edittextlink">Monthly View</a></td>
			</tr>
      <?php	
	  			$tot_gen_clicks += $row['url_total_clicks'];
				$tot_fraud_clicks += $row['url_total_fraud_clicks'];
				$tot_sale_amt += $row['url_total_sale_amount'];
				$tot_cost		+= $cur_cost;
	  		}
			?>
				<tr >
			 <td align="right" valign="middle" class="listingtableheader" colspan="5">Page Total</td>
			  <td align="right" valign="middle" class="listingtableheader"><? echo $tot_gen_clicks?></td>
			 <td align="right" valign="middle" class="listingtableheader"><? echo $tot_fraud_clicks?></td>
			 <td align="right" valign="middle" class="listingtableheader" ><? echo display_price($tot_sale_amt)?></td>
			  <td align="right" valign="middle" class="listingtableheader" ><?php echo display_price($tot_cost)?></td>
			  <td align="center" valign="middle" class="listingtableheader" >&nbsp;</td>
			  <td align="center" valign="middle" class="listingtableheader" >&nbsp;</td>
			</tr>
			<?PHP
	  }
	  else
	  {
	  ?>
	  <tr>
	    <td colspan="9" align="center" valign="middle" class="norecordredtext" >
	  	    No Cost per click details found.				  </td>
		  </tr>
		<?
		}
		?>	
      </table></td>
    </tr>
	
  <tr>
      <td align="center" class="listeditd">
	  <a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=costperclick_urls&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&cbo_advertlocation=<?=$_REQUEST['cbo_advertlocation']?>&cbo_keyword=<?=$_REQUEST['cbo_keyword']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist"> Edit </a> 
	  <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $_REQUEST['cbo_keyword']?>','<?php echo $_REQUEST['cbo_advertlocation']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?></td>
      <td align="right" class="listeditd" colspan="2"></td>
  </tr>
  </table>
  </div>
  </td>
  </tr>
  <tr>
   <td align="right" class="listing_bottom_paging" colspan="2"><?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
  </tr>
  </table>
</form>

<?php
	/*#################################################################
	# Script Name 	: list_promotionalcode.php
	# Description 	: Page for listing promotional codes
	# Coded by 		: Sny
	# Created on	: 25-Oct-2007
	# Modified by	: Sny
	# Modified On	: 29-Oct-2007
	#################################################################*/
// Define constants for this page
$table_name			= 'promotional_code';
$page_type			= 'Promotional Codes';
$help_msg 			= get_help_messages('LIST_PROM_CODE_MESS1');
$table_headers 		= array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_promotional,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_promotional,\'checkbox[]\')"/>','Slno.','Code','Code Type','Start date','End Date','Hidden','Total Usage');
$header_positions	= array('center','left','left','center','center','center');
$colspan 			= count($table_headers);

//#Search terms
$search_fields 		= array('codenumber','sort_order','sort_by');

$query_string 		= "request=prom_code";
foreach($search_fields as $v) {
	$query_string .= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by 			= (!$_REQUEST['sort_by'])?'code_number':$_REQUEST['sort_by'];
$sort_order 		= (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options 		= array('code_number' => 'Promotional Code','code_startdate'=>'Startdate','code_enddate'=>'End Date');
$sort_option_txt 	= generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt		= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

// Search Options
$where_conditions 	= " WHERE sites_site_id=$ecom_siteid ";
if ($_REQUEST['codenumber'])
	$where_conditions .= " AND code_number LIKE '%".$_REQUEST['codenumber']."%' ";

// Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name $where_conditions";
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
if ($pg>=1)
{
	$start = ($pg - 1) * $records_per_page;//#Starting record.
	$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
}	
else
{
	$start = $count_no = 0;	
}

/////////////////////////////////////////////////////////////////////////////////////

$sql_qry = "SELECT * FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
$ret_qry = $db->query($sql_qry);
?>
<script type="text/javascript">
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
function call_ajax_delete(cname,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var del_ids 			= '';
	var codenumber			= '<?php echo $_REQUEST['codenumber']?>';
	var sortby				= '<?php echo $sort_by?>';
	var sortorder			= '<?php echo $sort_order?>';
	var records_per_page	= '<?php echo $records_per_page?>';
	var start				= '<?php echo $start?>';
	var pg					= '<?php echo $pg?>';
	var qrystr				= 'codenumber='+codenumber+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+records_per_page+'&start='+start+'&pg='+pg;
	
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_promotional.elements.length;i++)
	{
		if (document.frm_promotional.elements[i].type =='checkbox' && document.frm_promotional.elements[i].name=='checkbox[]')
		{

			if (document.frm_promotional.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frm_promotional.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the promotional code(s) to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected promotional codes?'))
		{
			show_processing();
			Handlewith_Ajax('services/promotional_code.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function call_ajax_changestatus()
{
	var atleastone 			= 0;
	var prom_ids			= '';
	var cat_ids 			= '';
	var cat_orders			= '';
	var codenumber			= '<?php echo $_REQUEST['codenumber']?>';
	var sortby				= '<?php echo $sort_by?>';
	var sortorder			= '<?php echo $sort_order?>';
	var records_per_page	= '<?php echo $records_per_page?>';
	var start				= '<?php echo $start?>';
	var pg					= '<?php echo $pg?>';
	var ch_status			= document.frm_promotional.cbo_changehide.value;
																	
	var qrystr				= 'codenumber='+codenumber+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+records_per_page+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_promotional.elements.length;i++)
	{
		if (document.frm_promotional.elements[i].type =='checkbox' && document.frm_promotional.elements[i].name=='checkbox[]')
		{

			if (document.frm_promotional.elements[i].checked==true)
			{
				atleastone = 1;
				if (prom_ids!='')
					prom_ids += '~';
				 prom_ids += document.frm_promotional.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the Promotional Code to change the hide status');
	}
	else
	{
		if(confirm('Change Hide Status of Seleted Promotional Code(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/promotional_code.php','fpurpose=change_hide&'+qrystr+'&promids='+prom_ids);
		}	
	}	
}
/////////////////

function edit_selected(id)
{
	var id_exists = false;	
	if(id!=0)
		id_exists = true;
	len=document.frm_promotional.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frm_promotional.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		{
			if(id_exists)
			{
				if (el.value==id)
				{
					el.checked=true;
				}			
			}
			if(el.checked)
			{
				cnt++;
				voucher_id=el.value;
			}		
		 }  
	}
	if(cnt==0) {
		alert('Please select atleast one Promotional Code ');
	}
	else if(cnt>1 ){
		alert('Please select only one Promotional Code to edit');
	}
	else
	{
		show_processing();
		document.frm_promotional.fpurpose.value='edit';
		document.frm_promotional.submit();
	}
	
	
}


//////////////////
function go_edit(cname,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var del_ids 			= '';
	var qrystr				= 'catgroupname='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_promotional.elements.length;i++)
	{
		if (document.frm_promotional.elements[i].type =='checkbox' && document.frm_promotional.elements[i].name=='checkbox[]')
		{

			if (document.frm_promotional.elements[i].checked==true)
			{
				atleastone += 1;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the promotional code to edit');
	}
	else
	{
		if(atleastone==1)
		{
			show_processing();
			document.frm_promotional.fpurpose.value='edit';
			document.frm_promotional.submit();
		}	
	}	
}
</script>
<form method="post" name="frm_promotional" class="frmcls" action="home.php">
<input type="hidden" name="request" value="prom_code" />
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="search_click" value="" />
<input type="hidden" name="sort_by" value="<?php echo $_REQUEST['sort_by']?>">
	<input type="hidden" name="sort_order" value="<?php echo $_REQUEST['sort_order']?>">
	<input type="hidden" name="start" value="<?php echo $_REQUEST['start']?>">
	<input type="hidden" name="pg" value="<?php echo $_REQUEST['pg']?>">
	<input type="hidden" name="records_per_page" value="<?php echo $_REQUEST['records_per_page']?>">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span>List Promotional Codes</span></div></td>
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
	<?php
		if ($db->num_rows($ret_qry))
		{
	?>
    <tr><td colspan="3" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td></tr>
	<?php
		}
	?>
	<tr>
      <td height="48" colspan="3" class="sorttd">
		<div class="sorttd_div">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">		
        <tr>
          <td width="10%" height="30" align="left" valign="middle">Promotional Code</td>
		  <td width="17%" height="30" align="left" valign="middle"><input name="codenumber" type="text" class="textfeild" id="codenumber" value="<?php echo $_REQUEST['codenumber']?>" /></td>
		  <td width="10%" height="30" align="left" valign="middle">Records Per Page </td>
		  <td width="13%" height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3" value="<?php echo $records_per_page?>" /></td>
          <td width="5%" height="30" align="left" valign="middle">Sort By</td>
          <td width="35%" height="30" align="left" valign="middle"><?php echo $sort_option_txt;?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;<?php echo $sort_by_txt?></td>
          <td width="10%" height="30" align="right" valign="middle"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_promotional.search_click.value=1" />
            <a href="#" onmouseover ="ddrivetip(<?=get_help_messages('LIST_PROM_CODE_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
      </table>
		</div></td>
    </tr>
    
    <tr>
      <td colspan="3" class="listingarea">
	  <div class="listingarea_div">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	  <tr>
      <td align="left" valign="middle" class="listeditd" colspan="<?php echo round($colspan/2);?>"><a href="home.php?request=prom_code&fpurpose=add&codenumber=<?php echo $_REQUEST['codenumber']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>" class="addlist">Add</a><a href="#" onclick="edit_selected(0)" class="editlist">Edit</a>
	  	<?php
			if ($db->num_rows($ret_qry))
			{
		?>	
				<a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['catgroupname']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
		<?php
			}
		?>		</td>
      <td colspan="<?php echo round($colspan/2);?>" align="right" valign="middle" class="listeditd"><?php
			if ($db->num_rows($ret_qry))
			{
		?>
			Change Hide Status to
  				<?php
					$chhide_array = array('0'=>'No','1'=>'Yes');
					echo generateselectbox('cbo_changehide',$chhide_array,0);
				?>
 <input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus()" />
  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROM_CODE_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
  <?php
			}
		?></td>
    </tr>
	 <?php  
	 	echo table_header($table_headers,$header_positions); 
		$voucher_type_arr = array('val'=>'Value','per'=>'%');
		if ($db->num_rows($ret_qry))
		{ 
			 $srno = getStartOfPageno($records_per_page,$pg);
			while ($row_qry = $db->fetch_array($ret_qry))
			{
				$cls 		= ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
	 ?>
			   	<tr>
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="5%">
				  <input type="checkbox" name="checkbox[]" id="checkbox[]" value="<?php echo $row_qry['code_id']?>" /></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="1%"><?php echo $srno++?></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="15%"><a href="#" onclick="edit_selected('<?php echo $row_qry['code_id']?>')" title="View Details" class="edittextlink"><?php echo stripslashes($row_qry['code_number'])?></a></td>
				  <td align="center" valign="middle" class="<?php echo $cls?>">
				  <?php 
					switch(stripslashes($row_qry['code_type']))
					{
						case 'money':
							echo "Money Off for Min Spent";
						break;
						case 'percent':
							echo "% Off for Min Spent";
						break;
						case 'product':
							echo "Off on selected products";
						break;
						case 'freeproduct':
							echo "Adds products with off";
						break;
						case 'orddiscountpercent':
							echo "% Off on grand total if selected products in cart";
						break;
						default:
							echo "% Off on grand total";
						break;
						
					};
				 ?>				  </td>	
				  <td align="center" valign="middle" class="<?php echo $cls?>">
				  <?php
				  		$st_arr		= explode("-",$row_qry['code_startdate']);
						echo date('d/M/Y',mktime(0,0,0,$st_arr[1],$st_arr[2],$st_arr[0]));
				  ?>				  </td>
				  <td align="center" valign="middle" class="<?php echo $cls?>">
				  <?php
				  		$end_arr		= explode("-",$row_qry['code_enddate']);
						echo date('d/M/Y',mktime(0,0,0,$end_arr[1],$end_arr[2],$end_arr[0]));
				  ?>				  </td>	
				  <td align="center" valign="middle" class="<?php echo $cls?>">
				  	<?php
				  		echo ($row_qry['code_hidden']=='1')?'Yes':'No';	
					?>				  </td>
				 <td align="center" valign="middle" class="<?php echo $cls?>" >
				 <?php 
				 if($row_qry['code_unlimit_check']==1) 
				 	echo "Unlimited";
				else 
				{
					$sql_cnt = "SELECT count(orders_order_id) as cust_usedcnt 
										FROM
											order_promotionalcode_track a, orders b 
										WHERE
											a.sites_site_id=$ecom_siteid 
											AND b.order_id = a.orders_order_id 
											AND b.order_status NOT IN ('NOT_AUTH') 
											AND code_number='".stripslashes($row_qry['code_number'])."' 
											AND a.promotional_code_code_id = ".$row_qry['code_id'];
					$ret_cnt = $db->query($sql_cnt);
					list($totalused_cnt) = $db->fetch_array($ret_cnt);
					if($totalused_cnt<=$row_qry['code_limit'])
						echo $totalused_cnt.'/'.$row_qry['code_limit'];
					else
						echo $row_qry['code_limit'].'/'.$row_qry['code_limit'];
					//echo $row_qry['code_usedlimit'].'/'.$row_qry['code_limit'];
				}	
					?></td>

				</tr>
	<?php
			}
		}
		else
		{
	?>	
			<tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>">
				  	No Promotional Codes found.				  </td>
			</tr>	  
	<?php
		}
	?>
	  <tr>
      <td class="listeditd" align="left" valign="middle" colspan="<?php echo round($colspan/2);?>"><a href="home.php?request=prom_code&fpurpose=add&codenumber=<?php echo $_REQUEST['codenumber']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>" class="addlist" onclick="show_processing();">Add</a><a href="#" onclick="edit_selected()" class="editlist">Edit</a>
	  <?php 
		if ($db->num_rows($ret_qry))
		{
	?>
			<a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['catgroupname']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	 <?php
	 	}
	 ?>	  </td>
      <td class="listeditd" align="right" valign="middle" colspan="<?php echo round($colspan/2);?>">
	 </td>
    </tr>
      </table>
	  </div></td>
    </tr>
	 <tr>
     
      <td class="listing_bottom_paging" align="right" valign="middle" colspan="2">
	  <?php 
		if ($db->num_rows($ret_qry))
		{
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		}
		?></td>
    </tr>
  </table>
</form>

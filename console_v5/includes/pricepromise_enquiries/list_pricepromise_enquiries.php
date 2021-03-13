<?php
	/*#################################################################
	# Script Name 	: LIST_PRICE_PROMISE.php
	# Description 	: Page for listing price promise enquiries
	# Coded by 		: Latheesh
	# Created on	: 03-Sep-2007
	# Modified by	: Sny
	# Modified On	: 20-Apr-2010
	#################################################################*/
//Define constants for this page
$table_name 		= 'pricepromise a,products b, customers c';
$page_type 			= 'Pricepromise';
$help_msg 			= get_help_messages('LIST_PRICEPROMISE_MESS1');
$table_headers  	= array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistPricepromise,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistPricepromise,\'checkbox[]\')"/>','Slno.','Date Added','Product Name','Customer','Status','Usage');
$header_positions	= array('left','left','left','left','left','center','center');
$colspan 			= count($table_headers);
$cur_user			= $_SESSION['console_id'];
//#Search terms
$search_fields 		= array('search_name','search_cust','status');
foreach($search_fields as $v) {
	 $query_string .= "$v=".$_REQUEST[$v]."&";#For passing search terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by 			= (!$_REQUEST['sort_by'])?'a.prom_date':$_REQUEST['sort_by'];
$sort_order 		= (!$_REQUEST['sort_order'])?'DESC':$_REQUEST['sort_order'];
$sort_options 		= array('b.product_name' => 'Product Name','a.prom_status' => 'Status','a.prom_date' => 'Date Added','a.prom_used'=>'Usage');
$sort_option_txt 	= generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt		= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions 	= "WHERE a.sites_site_id=$ecom_siteid  AND a.products_product_id=b.product_id AND a.customers_customer_id = c.customer_id ";
if($_REQUEST['search_name']) {
	$where_conditions .= " AND ( b.product_name LIKE '%".add_slash($_REQUEST['search_name'])."%') ";
}
if($_REQUEST['search_cust']) {
	$where_conditions .= " AND ( 
									(c.customer_fname LIKE '%".add_slash($_REQUEST['search_cust'])."%') 
									OR 
									(c.customer_mname LIKE '%".add_slash($_REQUEST['search_cust'])."%') 
									OR 
									(c.customer_surname LIKE '%".add_slash($_REQUEST['search_cust'])."%') 
									OR 
									(c.customer_email_7503 LIKE '%".add_slash($_REQUEST['search_cust'])."%') 
									OR 
									(concat(c.customer_fname,' ',c.customer_surname) LIKE '%".add_slash($_REQUEST['search_cust'])."%' )
									OR 
									(concat(c.customer_fname,' ',c.customer_mname) LIKE '%".add_slash($_REQUEST['search_cust'])."%')
									OR 
									(concat(c.customer_mname,' ',c.customer_surname) LIKE '%".add_slash($_REQUEST['search_cust'])."%') 
									OR 
									(concat(c.customer_fname,' ',c.customer_mname,' ',c.customer_surname) LIKE '%".add_slash($_REQUEST['search_cust'])."%') 
								) ";
}
if($_REQUEST['status']=='')
{
 $_REQUEST['status'] ='';
 $status = '';
}
if($_REQUEST['status']){
$where_conditions .= "AND (a.prom_status LIKE '%".add_slash($_REQUEST['status'])."%')";
}

//#Select condition for getting total count
$sql_count = "SELECT count(a.prom_id) as cnt FROM $table_name  $where_conditions";
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
 $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=price_promise&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start";

?>
<script language="javascript">
function call_ajax_changestatus(search_name,sortby,sortorder,recs,start,pg,status)
{
	var atleastone 			= 0;
	var curid				= 0;
	var pricepromise_enquiries_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frmlistPricepromise.cbo_changestatus.value;
	var search_cust		= '<?php echo $_REQUEST['search_cust']?>';
	var status			= '<?php echo $_REQUEST['status']?>';
	var qrystr				= 'search_name='+search_name+'&search_cust='+search_cust+'&status='+status+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg+'&status='+status;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistPricepromise.elements.length;i++)
	{
		if (document.frmlistPricepromise.elements[i].type =='checkbox' && document.frmlistPricepromise.elements[i].name=='checkbox[]')
		{

			if (document.frmlistPricepromise.elements[i].checked==true)
			{
				atleastone = 1;
				if (pricepromise_enquiries_ids!='')
					pricepromise_enquiries_ids += '~';
				 pricepromise_enquiries_ids += document.frmlistPricepromise.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the Price Promise Enquiry to change the hide status');
	}
	else
	{
		if(confirm('Change Status of Seleted Customer(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/pricepromise_enquiries.php','fpurpose=change_status&'+qrystr+'&pricepromise_enquiries_ids='+pricepromise_enquiries_ids);
		}	
	}	
}
function call_ajax_delete(search_name,sortby,sortorder,recs,start,pg,status)
{
	var atleastone 	= 0;
	var del_ids 	= '';
	var search_email= '<?=$_REQUEST['search_email']?>';
	var search_cust		= '<?php echo $_REQUEST['search_cust']?>';
	var qrystr		= 'search_cust='+search_cust+'&status='+status+'&search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&status='+status;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistPricepromise.elements.length;i++)
	{
		if (document.frmlistPricepromise.elements[i].type =='checkbox' && document.frmlistPricepromise.elements[i].name=='checkbox[]')
		{

			if (document.frmlistPricepromise.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistPricepromise.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select Price Promise Enquiry to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Price Promise Enquiry?'))
		{
			show_processing();
			Handlewith_Ajax('services/pricepromise_enquiries.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
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
function handle_var(imgobj,id)
{
	obj = eval("document.getElementById('"+id+"')");
	if (obj)
	{
		if (obj.style.display=='none')
		{
			obj.style.display = '';
			imgobj.src = 'images/down_arr.gif';
		}
		else
		{
			obj.style.display = 'none';
			imgobj.src = 'images/right_arr.gif';
		}	
	}
}
</script>
<form name="frmlistPricepromise" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="price_promise" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span>List Price Promise Enquiries</span></div></td>
    </tr>
	<tr>
	  <td colspan="4" align="left" valign="middle" class="helpmsgtd_main">
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
	<?php
		  if($numcount)
		  {
	?>
	<tr>
		<td colspan="4" align="right" valign="middle" class="sorttd">
		<?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?>
		  </td>
	</tr>
	<?php
		  }
	?>
    <tr>
      <td height="48" class="sorttd" colspan="4" >
	  <div class="editarea_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
			 <tr>
				  <td width="10%" height="30" align="left" valign="middle">Product Name </td>
			   <td width="24%" height="30" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  />				  </td>
			      <td width="15%" height="30" align="left" valign="middle">Customer Name / Email Id </td>
		       <td width="26%" height="30" align="left" valign="middle"><input name="search_cust" type="text" class="textfeild" id="search_cust" value="<?=$_REQUEST['search_cust']?>" /></td>
			      <td width="4%" height="30" align="left" valign="middle">Status</td>
			      <td width="21%" height="30" align="left" valign="middle"><?= generateselectbox('status',array('0' => 'Any','New' => 'New','Read' => 'Read','Accept' => 'Accepted','Reject' => 'Rejected'),$_REQUEST['status']);?></td>
		    </tr>
				<tr> 
				  <td width="10%" height="30" align="left" valign="middle">Records Per Page </td>
			      <td width="24%" height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
			      <td width="15%" height="30" align="left" valign="middle">Sort By</td>
			      <td width="26%" height="30" align="left" valign="middle"><?=$sort_option_txt?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp; <?=$sort_by_txt?></td>
			      <td width="4%" height="30" align="left" valign="middle">&nbsp;</td>
			      <td width="21%" height="30" align="right" valign="middle"><input name="button5" type="submit" class="red" id="button5" value="Go" />
                    <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICE_PROMISE_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			 </tr>
      </table>
	  </div>
      </td>
    </tr>
     
    <tr>
      <td colspan="4" class="listingarea">
	   <div class="listingarea_div">
      <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">
		<tr>
			  <td align="left" valign="middle" class="listeditd"  colspan="4">
			   <?
			  if($numcount)
			  {
			  ?>
			   <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?php echo $_REQUEST['status']?>')" class="deletelist">Delete</a>
			  <?
			  }
			  ?>
			  </td>
			  <td align="right" valign="middle" class="listeditd"  colspan="3">
			  <?
			  if($numcount)
			  {
			  ?>
				
				Change Status
				  <select name="cbo_changestatus" class="dropdown" id="cbo_changestatus">
					<option value="NEW">NEW</option>
					<option value="READ">READ</option>
				  </select>&nbsp;<input name="change_status" type="button" class="red" id="change_status" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?php echo $_REQUEST['status']?>')" />
				  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEPROMISE_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?
				}
				?>
			  </td>
		</tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
		$sql_user = "SELECT a.prom_status,a.prom_id,a.products_product_id,b.product_name,date_format(prom_date,'%d-%b-%Y') as added_date, date_format(prom_approve_date,'%d-%b-%Y') prom_action_date, 
					 		prom_used,date_format(prom_used_on,'%d-%b-%Y') as used_date ,prom_approve_by,prom_max_usage,
							c.customer_title,c.customer_fname,c.customer_mname,c.customer_surname 
					 	FROM 
							$table_name 
							$where_conditions 
						ORDER BY 
							$sort_by $sort_order 
						LIMIT 
							$start,$records_per_page ";
	   
	   $res = $db->query($sql_user);
	   $srno = getStartOfPageno($records_per_page,$pg); 
	   while($row = $db->fetch_array($res))
	   {
			$count_no++;
			$array_values = array();
			if($count_no %2 == 0)
				$class_val="listingtablestyleA";
			else
				$class_val="listingtablestyleB";
				
				if($row['prom_status']=='NEW')
				{
				$stut = 'READ';
				}
				else
				{
				 $stut = $row['prom_status'];
				}
		
	   ?>
        <tr >
          <td align="left" valign="top" class="<?=$class_val;?>"  width="6%"><input name="checkbox[]" value="<? echo $row['prom_id']?>" type="checkbox"></td>
		  <td align="left" valign="top" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
		  <td align="left" valign="top" class="<?=$class_val;?>" width="10%"><a href="home.php?request=price_promise&fpurpose=edit&checkbox[0]=<?php echo $row['prom_id']?>&prom_status=<?php echo $stut?>&product_id=<?php echo $row['products_product_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&search_email=<?php echo $_REQUEST['search_email']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&status=<?php echo $_REQUEST['status']?>&sort_by=<?php echo $_REQUEST['sort_by']?>&sort_order=<?php echo $_REQUEST['sort_order']?>" title="<? echo $row['product_name']?>" class="edittextlink" onclick="show_processing()"><?php echo $row['added_date']; ?></a></td>
		  <td align="left" valign="top" class="<?=$class_val;?>"  width="30%" ><?php echo $row['product_name'];
			  // Check whether variables exists for current product in enquiry
			  $sql_var = "SELECT a.var_id,a.var_name,a.var_value_exists,b.var_value_id 
							FROM 
								product_variables a, pricepromise_variables b 
							WHERE 
								a.var_id = b.var_id 
								AND a.products_product_id = b.products_product_id 
								AND b.pricepromise_prom_id = ".$row['prom_id'];
			 $ret_var = $db->query($sql_var);
			 if($db->num_rows($ret_var))
			 {
			  ?>
			  <img src="images/right_arr.gif" align="Click to view the variables" onclick="handle_var(this,'varid_<?php echo $row['prom_id']?>')" style="cursor:pointer" />
			  <div id="varid_<?php echo $row['prom_id']?>" style="display:none">
			  <table width="60%" cellpadding="0" cellspacing="0" align="right">
			  <tr>
			  	<td class="listingtableheader">Variables
				</td>
			  <?php
			  	while ($row_var = $db->fetch_array($ret_var))
				{
				?>
					<tr>
						<td align="left" style="border-bottom: solid 1px #000000" class="<?=$class_val;?>"><strong><?php echo stripslashes($row_var['var_name'])?></strong> 
						<?php
						if($row_var['var_value_exists']==1)
						{
							$sql_val = "SELECT var_value 
											FROM 
												product_variable_data 
											WHERE 
												var_value_id = ".$row_var['var_value_id']." 
												AND product_variables_var_id = ".$row_var['var_id'].'  
											LIMIT 
												1';
							$ret_val = $db->query($sql_val);
							if($db->num_rows($ret_val))
							{
								$row_val = $db->fetch_array($ret_val);
								echo ': '. stripslashes($row_val['var_value']);
							}
						}
						?>
						</td>
					</tr>	
				<?php				
				}
			  ?>
			  </table>
			  </div>
			 <?php
			 }
			 ?> 
		  </td>
		   <td align="left" valign="top" class="<?=$class_val;?>" width="20%">
		   <?php echo stripslashes($row['customer_title']).stripslashes($row['customer_fname']).' '.stripslashes($row['customer_mname']).' '.stripslashes($row['customer_surname']);?>
		   </td>
		 <td align="center" valign="top" class="<?=$class_val;?>" >
		 <?php echo price_promise_status($row['prom_status']);
			$date_caption = $date_caption_by = '';
		 	if($row['prom_status']=='Accept')
			{
				$date_caption 		= '<strong>Accepted On: </strong> '.$row['prom_action_date'];
				$date_caption		.= '<br><strong>By: </strong> '.getConsoleUserName($row['prom_approve_by']);
			}
			elseif($row['prom_status']=='Reject')
			{
				$date_caption 		= '<strong>Rejected On: </strong> '.$row['prom_action_date'];
				$date_caption		.= '<br><strong>By: </strong> '.getConsoleUserName($row['prom_approve_by']);
			}
			if($date_caption != '')
			{
			?>
				<a href="#" onmouseover ="ddrivetip('<?=$date_caption?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;	
			<?php	
			}
		 ?>
		 </td>
		  <td align="center" valign="top" class="<?=$class_val;?>" >
		  <?php 
		   if($row['prom_used']>0)
		  	{
				echo $row['prom_used'];
			}
			else
				echo 0;
		   echo '/'.$row['prom_max_usage']
		   ?>
		  </td>
         </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext"  colspan="8">
				  	No Enquiries exists.
				  </td>
			</tr>
		<?
		}
		?>	
		<tr>
			  <td align="left" valign="middle" class="listeditd"  colspan="4">
			  <?
			  if($numcount)
			  {
			  ?>
			   <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?php echo $status?>')" class="deletelist">Delete</a>
			  <?
			  }
			  ?>
			  </td>
			  <td align="right" valign="middle" class="listeditd"  colspan="3">
			 
			  </td>
		</tr>
      </table>
	  </div></td>
    </tr>
	<tr>
			 
			  <td align="right" valign="middle" class="listing_bottom_paging"  colspan="2">
			  <?
			  if($numcount)
			  {
				paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
			  }
			  ?>
			  </td>
		</tr>
    </table>
</form>

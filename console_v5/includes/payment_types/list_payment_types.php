<?php
	/*#################################################################
	# Script Name 	: list_payment_types.php
	# Description 	: Page for listing the payment types enabled for the site
	# Coded by 		: ANU
	# Created on	: 27-June-2007
	# Modified by	: Sny
	# Modified On	: 26-Nov-2007
	#################################################################*/
	
	//Define constants for this page
	$table_name 		= 'payment_types as pt,payment_types_forsites as pts';
	$page_type			= 'Payment Types';
	$help_msg 			= get_help_messages('LIST_PAYMENT_TYPES_MESS1');
	//$table_headers 		= array('Slno.','Payment Types','<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_pymttypes,\'paytypes_id[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_pymttypes,\'paytypes_id[]\')"/>','Icons','');
	$table_headers 		= array('Slno.','Payment Types','Payment Type Caption','Enable / Disable','Icons','');
	$header_positions	= array('left','left','left','left','left','left');
	$colspan 			= count($table_headers);
	

		
	//#Sort
	$sort_by 			= (!$_REQUEST['sort_by'])?'paytype_name':$_REQUEST['sort_by'];
	$sort_order 		= (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
	$sort_options 		= array('paytype_name' => 'Payment Type Name');
	$sort_option_txt 	= generateselectbox('sort_by',$sort_options,$sort_by);
	$sort_by_txt		= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
	
	//#Search Options
	$where_conditions 	= "WHERE 1=1 AND pt.paytype_id = pts.paytype_id AND pts.paytype_forsites_active = 1 AND sites_site_id=$ecom_siteid ";
	

	//#Select condition for getting total count
	$sql_count 			= "SELECT count(*) as cnt FROM $table_name  $where_conditions";
	$res_count 			= $db->query($sql_count);
	list($numcount) 	= $db->fetch_array($res_count);//#Getting total count of records
	/////////////////////////////////For paging///////////////////////////////////////////
	$records_per_page 	= (is_numeric($_REQUEST['records_per_page']) and $_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:10;//#Total records shown in a page
	$pg = ($_REQUEST['search_click'])?1:$_REQUEST['pg'];
	
	if (!($pg > 0) || $pg == 0) { $pg = 1; }
	$pages = ceil($numcount / $records_per_page);//#Getting the total pages
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
	
 $sql_payment_type = "SELECT pt.paytype_id as orgpayid,pt.paytype_name,pts.paytype_caption,pts.paytype_forsites_id,pts.paytype_id,pts.paytype_forsites_active,pts.paytype_forsites_userdisabled,images_image_id  FROM $table_name $where_conditions ORDER BY $sort_by $sort_order";
	$ret_qry = $db->query($sql_payment_type);
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
function call_ajax_select_paymenttype(sortby,sortorder)
{
	
	var new_default_id 			= '';
	var disabled_ids 		    = '';
	var payids					= '';
	var paycaptions				= '';
	pidtemp						= new Array();
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_pymttypes.elements.length;i++)
	{
		if (document.frm_pymttypes.elements[i].type =='checkbox' && document.frm_pymttypes.elements[i].name=='paytypes_id[]')
		{

			if (document.frm_pymttypes.elements[i].checked==false)
			{
				atleastone = 1;
				if (disabled_ids!='')
					disabled_ids += '~';
				 disabled_ids += document.frm_pymttypes.elements[i].value;
			}	
		}
		else if(document.frm_pymttypes.elements[i].name.substr(0,15)=='paytype_caption')
		{
			pidtemp = document.frm_pymttypes.elements[i].name.split('_');
			if (payids!='')
				payids += '~';
			payids += pidtemp[2];
			if (paycaptions!='')
				paycaptions += '~';
			if (paycaptions=='' && document.frm_pymttypes.elements[i].value=='')
				paycaptions = ' ';
			else	
				paycaptions += document.frm_pymttypes.elements[i].value;
		}
	}
	var qrystr = '&sort_by='+sortby+'&sort_order='+sortorder+'&pymt_ids='+disabled_ids+'&payids='+payids+'&paycaptions='+paycaptions;
	if(confirm('Are you sure you want to Save the details ?'))
		{
			show_processing();
			Handlewith_Ajax('services/payment_types.php','fpurpose=select_paymenttype'+qrystr);
		}
		
}

	function checkboxsel(chkbox,id)
	{
		var selval = '';
		var temp = '';
		if (document.frm_pymttypes.sel_cats.value=='')
		{
			sel_val = document.frm_pymttypes.sel_cats.value;			
			sel_arr = sel_val.split('~');
			if (chkbox.checked)//add the id to the string
			{
				document.frm_pymttypes.sel_cats.value += '~'+id;
			}
			else
			{
				for(i=0;i<sel_arr.length;i++)
				{
					if (sel_arr[i]!=id)
					{
						if (temp=='')
							temp = sel_arr[i];
						else
							temp +=  '~' + sel_arr[i];
					}
				}
				document.frm_pymttypes.sel_cats.value = temp;
			}
		}	
		
	}
	function validate_assign()
	{
		if (document.frm_pymttypes.sel_cats.value=='') 
		{
			alert('Please select the product category');
			return false;
		}
		else
		{
			if(confirm('Are you sure you want to assign the selected categories?'))
			{
					document.frm_pymttypes.fpurpose.value = 'assign_sel_save';
					document.frm_pymttypes.submit();
			}	
		}
	}

	</script>
	<form method="post" name="frm_pymttypes" class="frmcls" action="home.php">
	<input type="hidden" name="request" value="payment_types" />
	<input type="hidden" name="fpurpose" value="" />
	<input type="hidden" name="search_click" value="" />
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
		<tr>
		  <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> Payment types</span></div></td>
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
		  <td  colspan="3" class="sorttd">
		  <div class="sorttd_div">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" >
			<tr>
			  <td width="6%" align="left" valign="middle">Sort By</td>
			  <td width="24%" align="left" valign="middle"><?php echo $sort_option_txt;?>&nbsp;&nbsp;in&nbsp;&nbsp;<?php echo $sort_by_txt?></td>
			  <td width="70%"  align="left" valign="middle"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_prodcat.search_click.value=1" />
			  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PAYMENT_TYPE_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			</tr>
		  </table>
		  </div>
	  	  </td>
		</tr>
		
		<tr>
		  <td colspan="3" class="listingarea">
		  <div class="editarea_div">
		  <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">
		  <tr>
		  <td align="right" valign="middle" colspan="5" class="listeditd"></td>
	    </tr>
		 <?php  
		 	// Get the payment type id for credit card
		 	$sql_pay = "SELECT  paytype_id 
		 					FROM 
		 						payment_types 
		 					WHERE 
		 						 paytype_code = 'credit_card' 
		 					LIMIT 
		 						1";
		 	$ret_pay = $db->query($sql_pay);
		 	if ($db->num_rows($ret_pay))
		 		$row_pay = $db->fetch_array($ret_pay);
			echo table_header($table_headers,$header_positions); 
			if ($db->num_rows($ret_qry))
			{ 
				$srno = 1;
				while ($row_qry = $db->fetch_array($ret_qry))
				{
					$cls = ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
					$sql_img = "SELECT image_id,image_gallerythumbpath,images_directory_directory_id FROM images WHERE 
								sites_site_id = $ecom_siteid AND image_id = ".$row_qry['images_image_id'];	
					$ret_img = $db->query($sql_img);
					
					
		 ?>
					<tr>
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="11%"><?php echo $srno++?></td>
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="20%" ><div style="float:left" >
					  <?php
						  if($row_qry['paytype_id'] == $row_pay['paytype_id'] && !$row_qry['paytype_forsites_userdisabled'] )
						  {
						 ?> 
					  	<a class="edittextlink" href="home.php?request=payment_types&fpurpose=view_methods&pass_sort_by=<? echo $_REQUEST['sort_by']?>&pass_sort_order=<? echo $_REQUEST['sort_order']?>">
					  <?php
					  	}
					  ?>
					   <?php echo stripslashes($row_qry['paytype_name']);
						  if($row_qry['paytype_id'] == $row_pay['paytype_id'] && !$row_qry['paytype_forsites_userdisabled'] )
						  {
						 ?> 
					    </a>
					   <?php
					   	}
					   ?>
					   </div> <?php /*?><?php if($row_qry['paytype_id'] == $row_pay['paytype_id'] && !$row_qry['paytype_forsites_userdisabled'] ){?> <div style="text-align:center;float:right"><a href="home.php?request=payment_types&fpurpose=view_cards&pass_sort_by=<? echo $_REQUEST['sort_by']?>&pass_sort_order=<? echo $_REQUEST['sort_order']?>" title="select credit cards"> <img src="images/select_card_types.gif" border="0"></a><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PAYMENT_TYPE_CREDITCARD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></div>  <?php */?>
					  <? //}?></td>	
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="20%" ><input type="text" name="paytype_caption_<?php echo $row_qry['orgpayid']?>" id="paytype_caption_<?php echo $row_qry['orgpayid']?>" value="<?php echo stripslashes($row_qry['paytype_caption'])?>" />
					  </td>	  
						<td width="35%" align="left" valign="middle" class="<?php echo $cls?>">
					  <input type="checkbox" name="paytypes_id[]" id="paytypes_id[]" value="<?php echo $row_qry['paytype_forsites_id']?>"  <?php echo(!$row_qry['paytype_forsites_userdisabled'])?'checked="checked"':''?> /></td>	
						<td width="10%" align="left" valign="middle" class="<?php echo $cls?>">
						<?php
						if($db->num_rows($ret_img))
						{
							$row_img = $db->fetch_array($ret_img);
							$but_caption = 'Change Image';
							$rem_caption = 'Unassign Image';
						}
						else
						{
							$but_caption = 'Assign Image';	
							$rem_caption = '';
						}	
						?>
						<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PAYMENT_TYPES_ASSIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/assign_image.gif" alt="" border="0"  onclick="document.frm_pymttypes.src_id.value='<?php echo $row_qry['paytype_forsites_id']?>';document.frm_pymttypes.fpurpose.value='add_paytype_img';document.frm_pymttypes.submit();"/></a>
						<?php
							if($rem_caption!='')
							{
						?>
					   <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PAYMENT_TYPES_UNASSIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/unassign_image.gif" alt="" border="0" onclick="document.frm_pymttypes.src_id.value='<?php echo $row_qry['paytype_forsites_id']?>';document.frm_pymttypes.fpurpose.value='rem_paytype_img';if(confirm('Are you sure you want unassaign image?')){document.frm_pymttypes.submit();}"/></a>
						<?php		
							}
						?>						</td>
					    <td width="21%" align="left" valign="middle" class="<?php echo $cls?>">
						<?php
						if($db->num_rows($ret_img))
						{
						?>	
							<img src="http://<?php echo $ecom_hostname."/images/$ecom_hostname/".$row_img['image_gallerythumbpath']?>" border="0" />
						<?php
						}
						?>						</td>
					</tr>
		<?php
				}
			}
			else
			{
		?>	
				<tr>
					  <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>">
						No Payment Types found.					</td>
				</tr>	  
		<?php
			}
		?>
		  </table>
		  </div>
		  </td>
		</tr>
		<tr>
		<td colspan="6" align="right">
		 <div class="editarea_div">
		 <input name="select_paymenttype" type="button" class="red" id="select_paymenttype" value="Save Changes" onclick="call_ajax_select_paymenttype('<?=$sort_by?>','<?=$sort_order?>')"/ />&nbsp;&nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PAYMENT_TYPES_SAVE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		 </div>
		</td>
		</tr>
	  </table>
		<input type="hidden" name="src_page" id="src_page" value="pay_type" />
		<input type="hidden" name="src_id" id="src_id" value="" />
	</form>

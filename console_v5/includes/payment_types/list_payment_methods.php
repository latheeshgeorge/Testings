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
	$table_name 		= 'payment_methods as pt,payment_methods_forsites as pts';
	$page_type			= 'Payment Types';
	$help_msg 			= get_help_messages('LIST_PAYMENT_TYPES_MESS1');
	//$table_headers 		= array('Slno.','Payment Types','<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_pymtmethods,\'paymethod_id[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_pymtmethods,\'paymethod_id[]\')"/>','Icons','');
	$table_headers 		= array('Slno.','Payment Methods','Payment Methods Caption','Enable this Method','Preview Required','Settings');
	$header_positions	= array('left','left','left','left','left','left');
	$colspan 			= count($table_headers);
	

		
	//#Sort
	$sort_by 			= (!$_REQUEST['sort_by'])?'paymethod_name':$_REQUEST['sort_by'];
	$sort_order 		= (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
	$sort_options 		= array('paymethod_name' => 'Payment Method Name');
	$sort_option_txt 	= generateselectbox('sort_by',$sort_options,$sort_by);
	$sort_by_txt		= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
	
	//#Search Options
	$where_conditions 	= "WHERE pt.paymethod_id = pts.payment_methods_paymethod_id AND sites_site_id=$ecom_siteid ";
	

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
	
 	$sql_payment_type 	= "SELECT pt.paymethod_id,pt.paymethod_key,pt.paymethod_takecarddetails,pt.paymethod_name,pts.payment_method_sites_caption,pts.payment_methods_forsites_id,pts.payment_methods_paymethod_id,pts.payment_method_sites_active,payment_method_preview_req  FROM $table_name $where_conditions ORDER BY $sort_by $sort_order";
	$ret_qry 			= $db->query($sql_payment_type);
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
	var prevreq_payids			= '';
	
	var new_defaultcard_id 		= '';
	var enabled_ids 		    = '';
	var sort_str	 		    = '';
	var card_modify					= 0;
	if(document.getElementById('carddet_exists'))
	{
		card_modify = 1;
	}
	
	pidtemp						= new Array();
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_pymtmethods.elements.length;i++)
	{
		if (document.frm_pymtmethods.elements[i].type =='checkbox' && document.frm_pymtmethods.elements[i].name=='paymethod_id[]')
		{
			if (document.frm_pymtmethods.elements[i].checked==true)
			{
				atleastone = 1;
				if (disabled_ids!='')
					disabled_ids += '~';
				 disabled_ids += document.frm_pymtmethods.elements[i].value;
			}	
		}
		else if(document.frm_pymtmethods.elements[i].name.substr(0,17)=='paymethod_caption')
		{
			pidtemp = document.frm_pymtmethods.elements[i].name.split('_');
			if (payids!='')
				payids += '~';
			payids += pidtemp[2];
			if (paycaptions!='')
				paycaptions += '~';
			if(paycaptions=='' && document.frm_pymtmethods.elements[i].value=='')		
				paycaptions = ' ';
			else	
				paycaptions += document.frm_pymtmethods.elements[i].value;
			
		}
		else if (document.frm_pymtmethods.elements[i].type =='checkbox' && document.frm_pymtmethods.elements[i].name.substr(0,11)=='preview_req')
		{
			if (document.frm_pymtmethods.elements[i].checked==true)
			{
				pidtemp = document.frm_pymtmethods.elements[i].name.split('_');
				if (prevreq_payids !='')
					prevreq_payids += '~';
				prevreq_payids += pidtemp[2];
			}			
		}
		else if (document.frm_pymtmethods.elements[i].type =='checkbox' && document.frm_pymtmethods.elements[i].name=='cardtype_id[]')
		{

			if (document.frm_pymtmethods.elements[i].checked==true)
			{
				var temp 		= 'cardtype_order_'+document.frm_pymtmethods.elements[i].value;
				elem			= eval(document.getElementById(temp));
				atleastone = 1;
				if (enabled_ids!='')
				{
				 	enabled_ids += '~';
			 		sort_str += '~';
				}
				enabled_ids 	+= document.frm_pymtmethods.elements[i].value; 
				sort_str 	+= elem.value;
			}	
		}
	}
	var qrystr = '&sort_by='+sortby+'&sort_order='+sortorder+'&pymt_ids='+disabled_ids+'&payids='+payids+'&paycaptions='+paycaptions+'&prevreq_payids='+prevreq_payids+'&card_ids='+enabled_ids+'&sort_str='+sort_str+'&card_modify='+card_modify;
	if(confirm('Are you sure you want to Save the details ?'))
		{
			show_processing();
			Handlewith_Ajax('services/payment_types.php','fpurpose=select_paymentmethod'+qrystr);
		}
		
}

	function checkboxsel(chkbox,id)
	{
		var selval = '';
		var temp = '';
		if (document.frm_pymtmethods.sel_cats.value=='')
		{
			sel_val = document.frm_pymtmethods.sel_cats.value;			
			sel_arr = sel_val.split('~');
			if (chkbox.checked)//add the id to the string
			{
				document.frm_pymtmethods.sel_cats.value += '~'+id;
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
				document.frm_pymtmethods.sel_cats.value = temp;
			}
		}	
		
	}
	function validate_assign()
	{
		if (document.frm_pymtmethods.sel_cats.value=='') 
		{
			alert('Please select the product category');
			return false;
		}
		else
		{
			if(confirm('Are you sure you want to assign the selected categories?'))
			{
					document.frm_pymtmethods.fpurpose.value = 'assign_sel_save';
					document.frm_pymtmethods.submit();
			}	
		}
	}

	</script>
	<form method="post" name="frm_pymtmethods" class="frmcls" action="home.php">
	<input type="hidden" name="request" value="payment_types" />
	<input type="hidden" name="fpurpose" value="" />
	<input type="hidden" name="search_click" value="" />
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
		<tr>
		  <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=payment_types&sort_order=<? echo $_REQUEST['pass_sort_order']?>&sort_by=<? echo $_REQUEST['pass_sort_by']?>">List Payment types</a><span> Payment Methods</span></div></td>
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
		<?php /*?><tr>
		  <td  colspan="3" class="sorttd">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" >
			<tr>
			  <td width="9%" align="left">Sort By</td>
			  <td   align="left" width="35%"><?php echo $sort_option_txt;?>
				in
				<?php echo $sort_by_txt?></td>
			  <td width="56%"  align="left"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_prodcat.search_click.value=1" />
			  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PAYMENT_TYPE_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			</tr>
		  </table>
		  	  </td>
		</tr><?php */?>
		<tr>
		  <td colspan="3" class="listingarea">
		  <div class="editarea_div">
		  <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">
		  <tr>
		  <td align="right" valign="middle" colspan="6" class="listeditd"></td>
	    </tr>
		 <?php  
			echo table_header($table_headers,$header_positions); 
			if ($db->num_rows($ret_qry))
			{ 
				$srno = 1;
				while ($row_qry = $db->fetch_array($ret_qry))
				{
					$cls = ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
					// check whether details required for current payment method
					$sql_chk = "SELECT payment_method_details_id FROM payment_methods_details 
										WHERE payment_methods_paymethod_id=".$row_qry['paymethod_id'];
					$ret_chk = $db->query($sql_chk);
					$chk_cnt = $db->num_rows($ret_chk);
					
		 ?>
					<tr>
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="7%"><?php echo $srno++?></td>
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="25%" ><div style="float:left" >
					   <?php echo stripslashes($row_qry['paymethod_name']);?></div></td>	
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="25%" ><input type="text" name="paymethod_caption_<?php echo $row_qry['paymethod_id']?>" id="paytype_caption_<?php echo $row_qry['paymethod_id']?>" value="<?php echo stripslashes($row_qry['payment_method_sites_caption'])?>" />
					  </td>	  
						<td align="left" valign="middle" class="<?php echo $cls?>">
					  <input type="checkbox" name="paymethod_id[]" id="paymethod_id[]" value="<?php echo $row_qry['paymethod_id']?>"  <?php echo($row_qry['payment_method_sites_active'])?'checked="checked"':''?> /></td>	
					<td align="left" valign="middle" class="<?php echo $cls?>">
					<?php
						if($row_qry['paymethod_takecarddetails']==0 or $row_qry['paymethod_key']=='GOOGLE_CHECKOUT')
						{
					?>
							<input type="checkbox" name="preview_req_<?php echo $row_qry['paymethod_id']?>" id="preview_req_<?php echo $row_qry['paymethod_id']?>" value="<?php echo $row_qry['paymethod_id']?>" <?php echo($row_qry['payment_method_preview_req'])?'checked="checked"':''?>/>
					<?php
						}
						else
							echo '<span class="redtext"><strong>N/A</strong></span>';
					?>		
					  </td>
					<td align="left" valign="middle" class="<?php echo $cls?>">
					<?php 
					if($chk_cnt>0)
					{
					?>
						<a href="home.php?request=payment_types&fpurpose=view_paytype_entry&paymethod_id=<?php echo $row_qry['paymethod_id']?>"><img src="images/layout.gif" border="0" /></a>
					<?php
					}
					else
						echo '<span class="redtext"><strong>N/A</strong></span>';
					?>
					</td>	
					</tr>
		<?php
				}
			}
			else
			{
		?>	
				<tr>
					  <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>">
						No Payment Methods found.</td>
				</tr>	  
		<?php
			}
		?>
		  </table>
		  </div></td>
		</tr>
		
	  </table>
		<input type="hidden" name="src_page" id="src_page" value="pay_type" />
		<input type="hidden" name="src_id" id="src_id" value="" />
		<?
			//Check whether there exists atleast one payment method for this site which required to take card details in the website
			$sql_sel = "SELECT paymethod_id 
							FROM
							 payment_methods a,payment_methods_forsites b 
							WHERE 
								a.paymethod_id = b.payment_methods_paymethod_id 
								AND b.sites_site_id=$ecom_siteid 
								AND a.paymethod_takecarddetails =1 
								AND b.payment_method_sites_active=1 
							LIMIT 1";
			$ret_sel = $db->query($sql_sel);
			if ($db->num_rows($ret_sel))
			{
		?>
			<div class="editarea_div">
			<input type="hidden" name="carddet_exits" id="carddet_exists" value="1" />
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
			<tr>
			  <td align="left" valign="middle" class="helpmsgtd" colspan="3">
			  <div class="helpmsg_divcls">
			  <?php 
				$where_conditions = '';
				$table_name = 'payment_methods_supported_cards';
				$page_type='Credit Cards';
				$help_msg = get_help_messages('LIST_PAYMENT_CARDS_MESS1');
				echo $help_msg;
			  ?>
			  </div>
			 </td>
			</tr>
			<tr>
			  <td align="left" valign="middle" colspan="3">
			<?php
			
			$table_headers = array('Slno.','Credit Card Name','<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_pymtmethods,\'cardtype_id[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_pymtmethods,\'cardtype_id[]\')"/>','Display Order','');
			$header_positions=array('left','left','left','center','center');
			$colspan = count($table_headers);
			
			// checking for already enabled credit cards
			// $sql_enabled_cc = "SELECT pmssc.supportcard_id,pmssc.payment_methods_supported_cards_cardtype_id,pmsc.cardtype_id  FROM payment_methods_sites_supported_cards pmssc,payment_methods_supported_cards pmsc WHERE pmssc.payment_methods_supported_cards_cardtype_id=pmsc.cardtype_id AND pmssc.sites_site_id = $ecom_siteid ";
			$sql_enabled_cc = "SELECT payment_methods_supported_cards_cardtype_id,supportcard_order FROM payment_methods_sites_supported_cards WHERE sites_site_id=".$ecom_siteid."";
			$ret_enabled_cc = $db->query($sql_enabled_cc);
			$arr_enabled_card_ids = array();
			$arr_enabled_card_order = array();
			while($enabled_cc = $db->fetch_array($ret_enabled_cc)){
			$arr_enabled_card_ids[] = $enabled_cc['payment_methods_supported_cards_cardtype_id'];
			$arr_enabled_card_order[$enabled_cc['payment_methods_supported_cards_cardtype_id']] = $enabled_cc['supportcard_order'];
			}
			
			$sql_credit_cards = "SELECT cardtype_id,cardtype_caption  FROM $table_name $where_conditions ORDER BY cardtype_caption ASC ";
			$ret_credit_cards = $db->query($sql_credit_cards);
			?>
			<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">
			 <?php  
				echo table_header($table_headers,$header_positions); 
				if ($db->num_rows($ret_credit_cards))
				{ 
					$srno = 1;
					$ii = 0;
					while ($row_qry = $db->fetch_array($ret_credit_cards))
					{
						$cls = ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
			 ?>
						<tr>
						  <td align="left" valign="middle" class="<?php echo $cls?>" width="7%"><?php echo $srno++?></td>
						  <td align="left" valign="middle" class="<?php echo $cls?>" width="25%"><div style="float:left" > <?php echo stripslashes($row_qry['cardtype_caption'])?></div> <?php if($row_qry['paytype_id'] == 1 && !$row_qry['paytype_forsites_userdisabled'] ){?> <div style="text-align:center;float:right"><a href="home.php?request=payment_types&fpurpose=card_types"> view cards</a></div>  <? }?>
						</td>		  
						<td align="left" valign="middle" class="<?php echo $cls?>" width="5%">
						  <input type="checkbox" name="cardtype_id[]" id="cardtype_id[]" value="<?php echo $row_qry['cardtype_id']?>"  <?php if(is_array($arr_enabled_card_ids)) echo(in_array($row_qry['cardtype_id'],$arr_enabled_card_ids))?'checked="checked"':''?> /></td>
						 <td align="left" valign="middle" class="<?php echo $cls?>" width="10%">
						 <input type="text" name="cardtype_order_<?php echo $row_qry['cardtype_id']?>" id="cardtype_order_<?php echo $row_qry['cardtype_id']?>" value="<?php echo $arr_enabled_card_order[$row_qry['cardtype_id']]?>" size="4" />
						 </td>
						 <td align="left" valign="middle" class="<?php echo $cls?>">&nbsp;</td>
						</tr>
			<?php
						$ii++;
					}
				}
				else
				{
			?>	
					<tr>
						  <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>">
							No Card Types found.					</td>
					</tr>	  
			<?php
				}
			?>
			  </table>
			  </td>
			  </tr>
	  </table></div>
		<?php
			}
		?>  
		 <div class="editarea_div">
		 <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">
		 <tr>
		 <td align="right"><input name="select_paymenttype" type="button" class="red" id="select_paymenttype" value="Save Changes" onclick="call_ajax_select_paymenttype('<?=$sort_by?>','<?=$sort_order?>')"/ />&nbsp;&nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PAYMENT_TYPES_SAVE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		 </table>
		 </div>
	</form>
	

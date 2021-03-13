<?php
	/*#################################################################
	# Script Name 	: list_payment_types.php
	# Description 	: Page for listing the credit acrds available for the site
	# Coded by 		: ANU
	# Created on	: 27-June-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
	
	//Define constants for this page
	$table_name = 'payment_methods_supported_cards';
	$page_type='Credit Cards';
	$help_msg = 'This section lists the Credit Cards enabled for the site.Here there is provision for disabling or enabling the Credit Cards for this site by unchecking or checking the check box';
	$table_headers = array('Slno.','Credit Card Name','<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_creditcards,\'cardtype_id[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_creditcards,\'cardtype_id[]\')"/>');
	$header_positions=array('left','left','left');
	$colspan = count($table_headers);
	

		
	//#Sort
	$sort_by = (!$_REQUEST['sort_by'])?'cardtype_caption':$_REQUEST['sort_by'];
	$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
	$sort_options = array('cardtype_caption' => 'Credit Card Name');
	$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
	$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
	
	//#Search Options
	$where_conditions = "WHERE 1=1";
	

	//#Select condition for getting total count
	$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
	$res_count = $db->query($sql_count);
	list($numcount) = $db->fetch_array($res_count);//#Getting total count of records
	/////////////////////////////////For paging///////////////////////////////////////////
	$records_per_page = (is_numeric($_REQUEST['records_per_page']) and $_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:10;//#Total records shown in a page
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
	
	// checking for already enabled credit cards
	// $sql_enabled_cc = "SELECT pmssc.supportcard_id,pmssc.payment_methods_supported_cards_cardtype_id,pmsc.cardtype_id  FROM payment_methods_sites_supported_cards pmssc,payment_methods_supported_cards pmsc WHERE pmssc.payment_methods_supported_cards_cardtype_id=pmsc.cardtype_id AND pmssc.sites_site_id = $ecom_siteid ";
	$sql_enabled_cc = "SELECT payment_methods_supported_cards_cardtype_id FROM payment_methods_sites_supported_cards WHERE sites_site_id=".$ecom_siteid."";
	$ret_enabled_cc = $db->query($sql_enabled_cc);
	$arr_enabled_card_ids = array();
	while($enabled_cc = $db->fetch_array($ret_enabled_cc)){
	$arr_enabled_card_ids[] = $enabled_cc['payment_methods_supported_cards_cardtype_id'];
	//echo $enabled_cc['cardtype_id'];
	}
	
    $sql_credit_cards = "SELECT cardtype_id,cardtype_caption  FROM $table_name $where_conditions ORDER BY $sort_by $sort_order";
	$ret_credit_cards = $db->query($sql_credit_cards);
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
function call_ajax_select_paymentcards(sortby,sortorder)
{
	
	var new_default_id 			= '';
	var enabled_ids 		    = '';
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_creditcards.elements.length;i++)
	{
		if (document.frm_creditcards.elements[i].type =='checkbox' && document.frm_creditcards.elements[i].name=='cardtype_id[]')
		{

			if (document.frm_creditcards.elements[i].checked==true)
			{
				atleastone = 1;
				if (enabled_ids!='')
				 enabled_ids += '~';
				 enabled_ids += document.frm_creditcards.elements[i].value;
			}	
		}
	}
	var qrystr = '&sort_by='+sortby+'&sort_order='+sortorder+'&card_ids='+enabled_ids;
	if(confirm('Are you sure you want to Save the Selected Credit Cards?'))
		{
			show_processing();
			Handlewith_Ajax('services/payment_types.php','fpurpose=select_creditcards&'+qrystr);
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
	<form method="post" name="frm_creditcards" class="frmcls" action="home.php">
	<input type="hidden" name="request" value="payment_types" />
	<input type="hidden" name="fpurpose" value="view_cards" />
	<input type="hidden" name="search_click" value="" />
		<table border="0" cellpadding="0" cellspacing="0" class="maintable">
		<tr>
		  <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=payment_types">List Payment types</a> <span> Select Credit Card Types</span></div></td>
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
		  <td height="48" colspan="3" class="sorttd">
		
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttableleft">
			<tr>
			  <td align="left">Sort By</td>
			  <td   align="left" width="60%"><?php echo $sort_option_txt;?>
				in
				<?php echo $sort_by_txt?></td>
			  <td  align="left"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_creditcards.search_click.value=1" />
				<a href="#" onmouseover ="ddrivetip('Use \'Go\' button to order credit cards.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			</tr>
		  </table>
		  	  </td>
		</tr>
		<tr>
		  <td width="270" class="listeditd">&nbsp;</td>
		  <td width="421" align="center" class="listeditd">
		  	</td>
		  <td width="267" align="right" class="listeditd"><input name="select_cardtype" type="button" class="red" id="select_cardtype" value="Save Changes" onclick="call_ajax_select_paymentcards('<?=$sort_by?>','<?=$sort_order?>')"/ />&nbsp;&nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('Use this button to Enable or Disable a Credit Card Type .')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	    </tr>
		<tr>
		  <td colspan="3" class="listingarea">
		  <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
		 <?php  
			echo table_header($table_headers,$header_positions); 
			if ($db->num_rows($ret_credit_cards))
			{ 
				$srno = 1;
				while ($row_qry = $db->fetch_array($ret_credit_cards))
				{
					$cls = ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
		 ?>
					<tr>
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="5%"><?php echo $srno++?></td>
					  <td align="left" valign="middle" class="<?php echo $cls?>" width="30%"><div style="float:left" > <?php echo stripslashes($row_qry['cardtype_caption'])?></div> <?php if($row_qry['paytype_id'] == 1 && !$row_qry['paytype_forsites_userdisabled'] ){?> <div style="text-align:center;float:right"><a href="home.php?request=payment_types&fpurpose=card_types"> view cards</a></div>  <? }?>
					</td>		  
					<td align="left" valign="middle" class="<?php echo $cls?>">
					  <input type="checkbox" name="cardtype_id[]" id="cardtype_id[]" value="<?php echo $row_qry['cardtype_id']?>"  <?php if(is_array($arr_enabled_card_ids)) echo(in_array($row_qry['cardtype_id'],$arr_enabled_card_ids))?'checked="checked"':''?> /></td>
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
		  </table></td>
		</tr>
		<tr>
		  <td class="listeditd">&nbsp;</td>
		  <td align="center" class="listeditd"></td>
		  <td class="listeditd">&nbsp;</td>
		</tr>
		</table>
	</form>

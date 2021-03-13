<?php
	/*#################################################################
	# Script Name 	:apply_settings_many.php
	# Description 	: Page for appliying settings like discount,tax,show cart link,show enquiry link for multiple products in single step
	# Coded by 		: Anu
	# Created on	: 18-Apr-2008
	# Modified by	: Anu
	# Modified On	: 18-Mar-2008

	#################################################################*/

	//Define constants for this page
	$page_type = 'Products';
	$help_msg = get_help_messages('SETTINGS_TOMANY');
	
	$gen_arr 	= get_general_settings('product_maintainstock,epos_available','general_settings_sites_common');
	$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';
	global $ecom_site_mobile_api;
?>	
<script language="javascript" type="text/javascript">

	/* preloading the image to be shown on loading*/
	pic1= new Image(); 
	pic1.src="images/loading.gif";
	 
function ajax_return_contents() 
{
	var ret_val = '';
	var disp 	= 'no';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val 	= req.responseText;
			targetdiv 	= document.getElementById('retdiv_id').value;
			norecdiv 	= document.getElementById('retdiv_more').value;
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			/* Decide the display of action buttons*/
		}
		else
		{
			 show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}
function display_product_selector(checked_val){
	if(checked_val=='All'){
		document.getElementById("categoryselector_id").style.display="none";
		document.getElementById("productselector_id").style.display="none";
		
	}else if(checked_val=='Bycat'){
			document.getElementById("categoryselector_id").style.display="";
			document.getElementById("productselector_id").style.display="";
	}
}
function display_select(frm){
for(i=0;i<document.frm_apply_settingstomany.elements.length;i++)
	{
	if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='dicount_check')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("product_discount_id").style.display='';
				/*if(document.frm_apply_settingstomany.product_discount.value=='')
				{
				  alert('Enter the Value For Discount ');
				  return false;
				}*/
				
			}
			else
			{
			   document.getElementById("product_discount_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='applytax_check')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("tax_check_id").style.display='';
			}
			else
			{
			   document.getElementById("tax_check_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='cartlink_check')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("cartlink_check_id").style.display='';
			}
			else
			{
			   document.getElementById("cartlink_check_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='enquirylink_check')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("enquirylink_check_id").style.display='';
			}
			else
			{
			   document.getElementById("enquirylink_check_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='product_stock_notification_required_check')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("product_stock_notification_id").style.display='';
			}
			else
			{
			   document.getElementById("product_stock_notification_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='product_alloworder_notinstock_check')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("product_alloworder_notinstock_id").style.display='';
			}
			else
			{
			   document.getElementById("product_alloworder_notinstock_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='price_display_type')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("price_display_type_id").style.display='';
			}
			else
			{
			   document.getElementById("price_display_type_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='variables_new_row')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("variables_new_row_id").style.display='';
			}
			else
			{
			   document.getElementById("variables_new_row_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='allow_freedelivery')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("allow_freedelivery_id").style.display='';
			}
			else
			{
			   document.getElementById("allow_freedelivery_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='show_pricepromise')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("show_pricepromise_id").style.display='';
			}
			else
			{
			   document.getElementById("show_pricepromise_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='allow_pricecaption')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("pricecaption_id").style.display='';
			}
			else
			{
			   document.getElementById("pricecaption_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='allow_qtycaption')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("qtycaption_id").style.display='';
			}
			else
			{
			   document.getElementById("qtycaption_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='allow_qtytype')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("qtytype_id").style.display='';
			}
			else
			{
			   document.getElementById("qtytype_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='show_newicon_saleicon')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("show_newicon_saleicon_id").style.display='';
			}
			else
			{
			   document.getElementById("show_newicon_saleicon_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='show_commonprod_spec')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("show_commonprod_id").style.display='';
			}
			else
			{
			   document.getElementById("show_commonprod_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='show_bulkdisc')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("show_bulkdisc_id").style.display='';
			}
			else
			{
			   document.getElementById("show_bulkdisc_id").style.display='none'; 
			}	
		}
		<?php
		if($ecom_site_mobile_api==1)
		{
		?> 
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='show_in_mobile_api_sites')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("show_in_mobile_api_sites_id").style.display='';
			}
			else
			{
			   document.getElementById("show_in_mobile_api_sites_id").style.display='none'; 
			}	
		}
		<?php
		}
		?>
		/*if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='prod_image_check')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("prod_image_check_id").style.display='';
			}
			else
			{
			   document.getElementById("prod_image_check_id").style.display='none'; 
			}	
		}*/
		
		
	}	
}
function handle_product_sale_icon(obj)
{
	if(obj.checked)
	{
		document.getElementById('product_saleicon_text_id').style.display='';
		document.frm_apply_settingstomany.product_newicon_show.checked = false;
		document.getElementById('product_newicon_text_id').style.display='none';
	}	
	else
	{
		document.getElementById('product_saleicon_text_id').style.display='none';
	}	
}
function handle_product_new_icon(obj)
{
	if(obj.checked)
	{
		document.getElementById('product_newicon_text_id').style.display='';
		document.frm_apply_settingstomany.product_saleicon_show.checked = false;
		document.getElementById('product_saleicon_text_id').style.display='none';
	}	
	else
	{
		document.getElementById('product_newicon_text_id').style.display='none';
	}	
}
function call_ajax_display_products(cat_id){
	retdivid   	= 'listprod_div';
	//moredivid	= 'varunassign_div';
	fpurpose	= 'list_products_settingstomany';
	document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */	
	//document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
	retobj 										= eval("document.getElementById('"+retdivid+"')");
	retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	var qrystr									= '';
	/* Calling the ajax function */
	Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&'+qrystr+'cur_catid='+cat_id);
}
function handle_qty_more_options(obj)
{
	if (obj.value=='NOR')
		document.getElementById('qty_more_box').style.display = 'none';
	else
		document.getElementById('qty_more_box').style.display = '';
}
function valforms(frm){

	if(frm.dicount_check.checked==true ) {
		if(frm.product_discount.value=="")
		{
			alert("Enter the discount value for the product.");
			return false
		}
		if(frm.product_discount.value <0){
			alert("Discount value should be a positive");
			return false
		}
		if(isNaN(frm.product_discount.value) ){
	         alert("Discount Should be a numeric value");
		 return false
	    }	
	}
	if(frm.product_discount.value >100 && frm.product_discount_enteredasval.value==0 ){
	alert("Discount % should be less than 100");
		return false
	}
	if(frm.tax_check[0].checked == false && frm.tax_check[1].checked == false && frm.applytax_check.checked==true) {
		alert("Select At least One Button to tax Settings");
		return false
	}
	if(frm.cartlink_check_radio[0].checked == false && frm.cartlink_check_radio[1].checked == false && frm.cartlink_check.checked==true) {
		alert("Select At least One Button Cart Link");
		return false
	}
	if(frm.enquirylink_check_radio[0].checked == false && frm.enquirylink_check_radio[1].checked == false && frm.enquirylink_check.checked==true) {
		alert("Select At least One Button Enquiry Link");
		return false
	}
	if(frm.product_stock_notification_check_radio[0].checked == false && frm.product_stock_notification_check_radio[1].checked == false && frm.product_stock_notification_required_check.checked==true) {
		alert("Select At least One Button Product Stock Notification");
		return false
	}
	if(frm.product_alloworder_notinstock_check_radio[0].checked == false && frm.product_alloworder_notinstock_check_radio[1].checked == false && frm.product_alloworder_notinstock_check.checked==true) {
		alert("Select At least One Button for Allow ordering even if out of stock");
		return false
	}
	if(frm.price_display_type_radio[0].checked == false && frm.price_display_type_radio[1].checked == false && frm.price_display_type.checked==true) {
		alert("Select At least One Button For Price Display type");
		return false
	}
	if(frm.enable_bulkdiscount_radio[0].checked == false && frm.enable_bulkdiscount_radio[1].checked == false && frm.show_bulkdisc.checked==true) {
		alert("Select Yes or No for Bulk Discount Settings");
		return false
	}
	<?php
	if($ecom_site_mobile_api==1)
	{
	?> 
	if(frm.enable_in_mobile_api_sites[0].checked == false && frm.enable_in_mobile_api_sites[1].checked == false && frm.show_in_mobile_api_sites.checked==true) {
		alert("Select Yes or No for Show in Mobile Application Settings");
		return false
	}
	
	if(frm.allow_qtycaption.checked==false && frm.allow_qtytype.checked==false && frm.allow_pricecaption.checked==false && frm.dicount_check.checked==false && frm.applytax_check.checked==false && frm.cartlink_check.checked==false && frm.enquirylink_check.checked==false && frm.price_display_type.checked==false && frm.variables_new_row.checked==false && frm.product_stock_notification_required_check.checked==false && frm.product_alloworder_notinstock_check.checked==false && frm.allow_freedelivery.checked==false && frm.show_pricepromise.checked==false && frm.show_newicon_saleicon.checked==false && frm.show_commonprod_spec.checked==false && frm.show_bulkdisc.checked==false && frm.show_in_mobile_api_sites.checked==false) {
		alert("Select At least One Checkbox");
		return false
	}
	else if(frm.select_products[0].checked== false && frm.select_products[1].checked == false){
		alert("Select the Product");
		return false;
	}
	
	
	<?php
	}
	else
	{
	?>
	
	if(frm.allow_qtycaption.checked==false && frm.allow_qtytype.checked==false && frm.allow_pricecaption.checked==false && frm.dicount_check.checked==false && frm.applytax_check.checked==false && frm.cartlink_check.checked==false && frm.enquirylink_check.checked==false && frm.price_display_type.checked==false && frm.variables_new_row.checked==false && frm.product_stock_notification_required_check.checked==false && frm.product_alloworder_notinstock_check.checked==false && frm.allow_freedelivery.checked==false && frm.show_pricepromise.checked==false && frm.show_newicon_saleicon.checked==false && frm.show_commonprod_spec.checked==false && frm.show_bulkdisc.checked==false) {
		alert("Select At least One Checkbox");
		return false
	}
	else if(frm.select_products[0].checked== false && frm.select_products[1].checked == false){
		alert("Select the Product");
		return false;
	}
	<?php
	}
	?>
	if(frm.select_products[0].checked==true){
		if(confirm("Are you sure You want Set the Selected changes for ALL the Products")){
			return true;
		}else{
			return false;
		}
	}else if(frm.select_products[1].checked==true){
	if(frm.settings_categoryid.value==0){
		alert("Select a category to apply the Selected settings");
		frm.settings_categoryid.focus();
		return false;
	}
	if (frm.allow_qtytype.checked==true)
	{
		if(frm.product_det_qty_drop_values.value=='')
		{
			alert ('Please specify the values to be displayed in Quantity dropdown box');
			return false;
		}
	}
		if(confirm("Are you sure You want Set the Selected changes for the Selected Products")){
			return true;
		}else{
			return false;
		}
	} 
}
</script>
<form name='frm_apply_settingstomany' action='home.php?request=products' method="post" onsubmit="return valforms(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=products&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&in_mobile_api_sites=<?php echo $_REQUEST['in_mobile_api_sites']?>&start=<?php echo $_REQUEST['start']?>&pg=<?php echo $_REQUEST['pg']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>">List Products</a><span>Set Options for multiple products</span></div></td>
        </tr>
       <tr>
		  <td colspan="3" align="left" valign="middle" class="helpmsgtd_main">
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
         <tr>
			<td colspan="4" align="center" valign="middle" class="tdcolorgray">
			<div class="editarea_div">
			<table width="100%" class="fieldtable">
			<tr>
                <td width="100%" align="left" class="tdcolorgray">
				
				<table width="40%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="11%"><input type="checkbox" name="dicount_check" value="1" onclick="display_select(this)" <? if($_REQUEST['dicount_check_1']) echo "checked"; else echo '';?> /></td>
                    <td width="89%">Discount&nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_DISC_CHECK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</td>
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="applytax_check" value="1" onclick="display_select(this)"  <? if($_REQUEST['applytax_check_1']) echo "checked"; else echo '';?>/></td>
                    <td>Apply Tax &nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_TAX_CHECK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</td>
                  </tr>
				  <tr>
                    <td><input type="checkbox" name="cartlink_check" value="1" onclick="display_select(this)" <? if($_REQUEST['cartlink_check_1']) echo "checked"; else echo '';?> /></td>
                    <td>Cart Link &nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_CART_CHECK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="enquirylink_check" value="1" onclick="display_select(this)" <? if($_REQUEST['enquirylink_check_1']) echo "checked"; else echo '';?>/></td>
                    <td>Enquiry Link &nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_ENQUIRY_CHECK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                  </tr>
				  <tr>
                    <td><input type="checkbox" name="product_stock_notification_required_check" value="1" onclick="display_select(this)" <? if($_REQUEST['product_stock_notification_required_check_1']) echo "checked"; else echo '';?>/></td>
                    <td>Product Stock Notification &nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_PRODUCT_STOCK_NOTIFICATION')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                  </tr>
				  <tr>
                    <td><input type="checkbox" name="product_alloworder_notinstock_check" value="1" onclick="display_select(this)" <? if($_REQUEST['product_alloworder_notinstock_check_1']) echo "checked"; else echo '';?>/></td>
                    <td>Allow ordering even if out of stock &nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_ALLOW_ORDERING_OUTSTOCK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                  </tr>
                   <tr>
                    <td><input type="checkbox" name="price_display_type" value="1" onclick="display_select(this)" <? if($_REQUEST['price_display_type_1']) echo "checked"; else echo '';?>/></td>
                    <td>Variable Price Display Type &nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_PRICE_DISPLAY')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                  </tr>
				   <tr>
                    <td><input type="checkbox" name="variables_new_row" value="1" onclick="display_select(this)" <? if($_REQUEST['variables_new_row_1']) echo "checked"; else echo '';?>/></td>
                    <td>Show variables in a new row &nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_NEW_ROW')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                  </tr>
				  <tr>
                    <td><input type="checkbox" name="allow_freedelivery" value="1" onclick="display_select(this)" <? if($_REQUEST['allow_freedelivery_1']) echo "checked"; else echo '';?>/></td>
                    <td>Allow Free delivery &nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_FREEDELIVERY')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                  </tr>
				   <tr>
                    <td><input type="checkbox" name="show_pricepromise" value="1" onclick="display_select(this)" <? if($_REQUEST['show_pricepromise_1']) echo "checked"; else echo '';?>/></td>
                    <td>Show Price Promise &nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SHOW_PROD_PRICEPROMISE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                  </tr>
				   <tr>
                    <td><input type="checkbox" name="allow_qtycaption" value="1" onclick="display_select(this)" /></td>
                    <td>Quantity Box Caption &nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_DET_QTY_CAPTION')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                  </tr>
				   <tr>
                    <td><input type="checkbox" name="allow_qtytype" value="1" onclick="display_select(this)" /></td>
                    <td>Quantity Box Type &nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_DET_QTY_TYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                  </tr>
				  <tr>
                    <td><input type="checkbox" name="allow_pricecaption" value="1" onclick="display_select(this)" /></td>
                    <td>Price Captions &nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_PRICE_CAP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                  </tr>
				  <tr>
                    <td><input type="checkbox" name="show_newicon_saleicon" value="1" onclick="display_select(this)" /></td>
                    <td>Allow new icon or Sale icon &nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_PRICE_CAP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                  </tr>
				 <tr>
                    <td><input type="checkbox" name="show_commonprod_spec" value="1" onclick="display_select(this)" /></td>
                    <td>Common Specification Settings &nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_COMM_SPEC')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                  </tr>
				 <tr>
				   <td><input type="checkbox" name="show_bulkdisc" value="1" onclick="display_select(this)" />
                   </td>
				   <td>Bulk Discount
                   <a href="#" onmouseover ="ddrivetip('<? echo "Allows to decide whether bulk discount option is to be enabled or disabled for products";?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
                   
                   </td>
			      </tr>
			       <?php
					if($ecom_site_mobile_api==1)
					{
					?> 
			     <tr>
				   <td><input type="checkbox" name="show_in_mobile_api_sites" value="1" onclick="display_select(this)" />
                   </td>
				   <td>Show in Mobile Application
                   <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_MOB_API')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
                   
                   </td>
			      </tr>
			      <?php
					}
			      ?>
				 <!-- <tr>
                    <td><input type="checkbox" name="prod_image_check" value="1" onclick="display_select(this)" <? if($_REQUEST['prod_image_check_1']) echo "checked"; else echo '';?>/></td>
                    <td>Product Details Image &nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_NEW_ROW')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                  </tr>-->
                </table></div>
				</td>
              </tr>
              <tr>
		</tr>
              <tr style=" display:none;" id="product_discount_id"><td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
               		<td  align="left" class="tdcolorgray">Discount
                      <input name="product_discount" type="text" size="8" value="" />
                  Calculate by
                  <?php
					$disc_type = array(0=>'%',1=>'Value',2=>'Exact Discount Price');
					echo generateselectbox('product_discount_enteredasval',$disc_type,'');
				?>                  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_DISC_VALUES')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
               </tr>
			  </table></td></tr>
              <tr style=" display:none" id="tax_check_id">
			  <td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
               		<td width="24%"  align="left" class="tdcolorgray">
			  <label>Apply Tax?</label>&nbsp;&nbsp;			  </td>

			  <td width="76%" colspan="2" align="left" class="tdcolorgray"><span class="">             
                    
                  <input type="radio" name="tax_check" value="Y" />
                    <label>Yes </label>
                <input type="radio" name="tax_check" value="N" />
                <label>No </label>
              </span>       <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_TAX_VALUES')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
              </tr>
			  </table>
			  </td></tr>
              <tr style="display:none" id="cartlink_check_id">
			  <td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
               		<td width="24%"  align="left" class="tdcolorgray">
			 <label>Show Cart Link?</label>&nbsp;&nbsp;			  </td>

			   <td width="76%" colspan="2" align="left" class="tdcolorgray"><span class="">
                
                 <input type="radio" name="cartlink_check_radio" value="1" />
				    <label>Yes </label>
                 
                 <input type="radio" name="cartlink_check_radio" value="0" />
				   <label>No </label>
               </span><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_CART_VALUES')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
              </tr>
			  </table></td></tr>
              <tr style="display:none" id="enquirylink_check_id">
			   <td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
               		<td width="24%"  align="left" class="tdcolorgray">
			<label>Show Enquiry Link?</label>&nbsp;&nbsp;			  </td>

			   <td width="76%" colspan="2" align="left" class="tdcolorgray"><span class="">
               <input type="radio" name="enquirylink_check_radio" value="1" />
 <label>Yes </label>
                
                 <input type="radio" name="enquirylink_check_radio" value="0" />
  <label>No </label>
               </span><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_ENQUIRY_VALUES')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
              </tr>
			  </table></td></tr>
			  <tr style="display:none" id="product_stock_notification_id">
			   <td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
               		<td width="24%"  align="left" class="tdcolorgray">
			<label>Product Notification Required?</label>&nbsp;&nbsp;			  </td>

			   <td width="76%" colspan="2" align="left" class="tdcolorgray"><span class="">
               <input type="radio" name="product_stock_notification_check_radio" value="Y" />
 <label>Yes </label>
                
                 <input type="radio" name="product_stock_notification_check_radio" value="N" />
  <label>No </label>
               </span><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_RADIO_PRODUCT_STOCK_NOTIFICATION')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
              </tr>
			  </table></td></tr>
			  <tr style="display:none" id="product_alloworder_notinstock_id">
			   <td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
               		<td width="24%"  align="left" class="tdcolorgray">
			<label>Allow ordering even if out of stock?</label>&nbsp;&nbsp;			  </td>

			   <td width="76%" colspan="2" align="left" class="tdcolorgray"><span class="">
               <input type="radio" name="product_alloworder_notinstock_check_radio" value="Y" />
 <label>Yes </label>
                
                 <input type="radio" name="product_alloworder_notinstock_check_radio" value="N" />
  <label>No </label>
               </span><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_RADIO_ALLOW_ORDERING_OUTSTOCK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
              </tr>
			  </table></td></tr>
			  <tr style="display:none" id="price_display_type_id">
			   <td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
               		<td width="24%"  align="left" class="tdcolorgray">
			<label>Variable Price Display Type</label>&nbsp;&nbsp;			  </td>

			   <td width="76%" colspan="2" align="left" class="tdcolorgray"><span class="">
               <input type="radio" name="price_display_type_radio" value="ADD" />
 <label>Add / Less Price </label>
                
                 <input type="radio" name="price_display_type_radio" value="FULL" />
  <label>Full Price </label>
               </span><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_PDISPLAY_TYPE_CHECK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
              </tr>
			  </table></td></tr>
			  <tr style="display:none" id="variables_new_row_id">
			   <td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
               		<td width="24%"  align="left" class="tdcolorgray">
			<label>Variables in a new row?</label>&nbsp;&nbsp;			  </td>

			   <td width="76%" colspan="2" align="left" class="tdcolorgray"><input type="checkbox" name="variables_new_row_select" value="1"   />
              <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_NEW_ROW_DECIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
              </tr>
			  </table></td></tr>
			  <tr style="display:none" id="allow_freedelivery_id">
			   <td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
               		<td width="24%"  align="left" class="tdcolorgray">
			<label>Allow free delivery?</label>&nbsp;&nbsp;			  </td>

			   <td width="76%" colspan="2" align="left" class="tdcolorgray"><input type="checkbox" name="allowfreedelivery_select" value="1"   />
              <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_FREEDELIVERY')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
              </tr>
			  </table></td></tr>
			  <tr style="display:none" id="show_pricepromise_id">
			   <td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
               		<td width="24%"  align="left" class="tdcolorgray">
			<label>Show Price Promise?</label>&nbsp;&nbsp;			  </td>

			   <td width="76%" colspan="2" align="left" class="tdcolorgray"><input type="checkbox" name="showpricepromise_select" value="1"   />
              <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SHOW_PROD_PRICEPROMISE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
              </tr>
			  </table></td></tr>
			   <tr style="display:none" id="qtycaption_id">
			   <td colspan="3" align="left" class="tdcolorgray">
			   <table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
               	<td width="24%"  align="left" class="tdcolorgray"><label>Quantity box Caption</label>&nbsp;&nbsp;</td>
			   <td width="76%" colspan="2" align="left" class="tdcolorgray"><input type="text" name="qtybox_select" value=""   />
              <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_QTY_CAP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
              </tr>
			  </table> 
			   </td>
			   </tr>
			   <tr style="display:none" id="qtytype_id">
			   <td colspan="3" align="left" class="tdcolorgray">
			   <table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
               	<td width="24%"  align="left" class="tdcolorgray"><label>Quantity box Type</label>&nbsp;&nbsp;</td>
			   <td width="76%" colspan="2" align="left" class="tdcolorgray">
				<select name="product_det_qty_type" id="product_det_qty_type" onchange="handle_qty_more_options(this)">
					<option value="NOR">Textbox</option>
					<option value="DROP">Drop Down Box</option>
                </select>
              <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_DET_QTY_TYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
              </tr>
			  <tr>
                   <td colspan="2" align="left">
				   <table width="100%" border="0" cellpadding="1" cellspacing="1" id="qty_more_box" style="display:none">
                     <tr>
                       <td colspan="2">Please specify the values to be displayed in drop down box in this box seperated by comma (,) <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_DET_QTY_DROP_VAL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                     </tr>
                     <tr>
                       <td>&nbsp;</td>
                       <td><textarea name="product_det_qty_drop_values" id="product_det_qty_drop_values" cols="30" rows="2"></textarea></td>
                     </tr>
                     <tr>
                       <td>Prefix to be used with values in drop down box<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_DET_QTY_DROP_PREFIX')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                       <td><input name="product_det_qty_drop_prefix" type="text" id="product_det_qty_drop_prefix" value="" size="26" /></td>
                     </tr>
                     <tr>
                       <td width="40%" align="left">Suffix to be used with values in drop down box <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_DET_QTY_DROP_SUFFFIX')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                       <td width="60%" align="left"><input name="product_det_qty_drop_suffix" type="text" id="product_det_qty_drop_suffix" value="" size="26" /></td>
                     </tr>
                   </table></td>
                 </tr>
			  </table> 
			   </td>
			   </tr>
			  <tr style="display:none" id="pricecaption_id">
			   <td colspan="3" align="right" class="tdcolorgray">
			   <table width="100%" cellpadding="1" cellspacing="1" border="0">
				 <tr>
				   <td colspan="4" align="left" class="listingtableheader">Price Display Captions</td>
				   <td align="center" class="listingtableheader">&nbsp;</td>
			     </tr>
				 <tr>
				   <td width="5%" align="left">&nbsp;</td>
					 <td width="15%" align="left">&nbsp;</td>
					 <td width="17%" align="center"><strong>Prefix</strong></td>
					 <td width="15%" align="center"><strong>Suffix</strong></td>
				     <td width="48%" align="center" >&nbsp;</td>
				 </tr>
				 <tr>
				   <td align="left" class="listingtablestyleA">&nbsp;</td>
					 <td align="left" class="listingtablestyleA"><strong>'Normal' Price</strong></td>
					 <td align="center" class="listingtablestyleA"><input class="input" name="price_normalprefix" value="" type="text"></td>
					 <td align="center" class="listingtablestyleA"><input class="input" name="price_normalsuffix" value="" type="text"></td>
				     <td align="center" class="listingtablestyleA">&nbsp;</td>
				 </tr>
				 <tr>
				   <td align="left" class="listingtablestyleA">&nbsp;</td>
					 <td align="left" class="listingtablestyleA"><strong>'From' Price</strong></td>
					 <td align="center" class="listingtablestyleA"><input class="input" name="price_fromprefix" value="" type="text"></td>
					 <td align="center" class="listingtablestyleA"><input class="input" name="price_fromsuffix" value="" type="text"></td>
				     <td align="center" class="listingtablestyleA">&nbsp;</td>
				 </tr>
				 <tr>
				   <td align="left" class="listingtablestyleA">&nbsp;</td>
					 <td align="left" class="listingtablestyleA"><strong>'Special Offer' Price</strong></td>
					 <td align="center" class="listingtablestyleA"><input class="input" name="price_specialofferprefix" value="" type="text"></td>
					 <td align="center" class="listingtablestyleA"><input class="input" name="price_specialoffersuffix" value="" type="text"></td>
				     <td align="center" class="listingtablestyleA">&nbsp;</td>
				 </tr>
				  <tr>
				    <td align="left" class="listingtablestyleA">&nbsp;</td>
					 <td align="left" class="listingtablestyleA"><strong>'Discount'</strong></td>
					 <td align="center" class="listingtablestyleA"><input class="input" name="price_discountprefix" value="" type="text"></td>
					 <td align="center" class="listingtablestyleA"><input class="input" name="price_discountsuffix" value="" type="text"></td>
				     <td align="center" class="listingtablestyleA">&nbsp;</td>
			     </tr>
				 <tr>
				   <td align="left" class="listingtablestyleA">&nbsp;</td>
					 <td align="left" class="listingtablestyleA"><strong>'You Save' Price</strong></td>
					 <td align="center" class="listingtablestyleA"><input class="input" name="price_yousaveprefix" value="" type="text"></td>
					 <td align="center" class="listingtablestyleA"><input class="input" name="price_yousavesuffix" value="" type="text"></td>
				     <td align="center" class="listingtablestyleA">&nbsp;</td>
				 </tr>
				 
				  <tr>
				    <td align="left" class="listingtablestyleA">&nbsp;</td>
					 <td align="left" class="listingtablestyleA"><strong>'No' Price</strong></td>
					 <td align="center" class="listingtablestyleA"><input class="input" name="price_noprice" value="<?php echo $_REQUEST['price_noprice']?>" type="text"></td>
				 <td align="center" class="listingtablestyleA">				                  
				 <td align="center" class="listingtablestyleA">                 
			     </tr>
				 </table>
			   </td>
			   </tr>
			  <!--<tr style="display:none" id="prod_image_check_id">
			   <td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
               		<td width="22%"  align="left" class="tdcolorgray"><strong>Product Details Image</strong><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_NEW_ROW_DECIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>

			   <td width="19%"  align="left" class="tdcolorgray">
			<label>Product Details Image TYPE</label>&nbsp;&nbsp;
			  </td>
                <td width="59%" colspan="2" align="left" class="tdcolorgray"><select name="productdetail_moreimages_showimagetype" id="productdetail_moreimages_showimagetype">
			  		<option value="Default">Default</option>
			 		<option value="Icon">Icon</option>
			  		<option value="Thumb">Small Image</option>
			</select>
              </td>
              </tr>
			  </table></td></tr>-->
			  <tr style="display:none" id="show_newicon_saleicon_id">
			   <td colspan="3" align="left" class="tdcolorgray">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
				<td colspan="3" align="left" class="listingtableheader">Product New Icon Or Sale Icon Options</td>
				</tr>
				<tr>
				<td align="left" width="20%" ><input type="checkbox" name="product_saleicon_show" id="product_saleicon_show" value="1"  onclick="return handle_product_sale_icon(this)"/> Show Product Sale Icon <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SHOW_PROD_PRICEPROMISE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
				<td align="left"><input type="checkbox" name="product_newicon_show" id="product_newicon_show" value="1"  onclick="return handle_product_new_icon(this)"/> Show product New Icon <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SHOW_PROD_PRICEPROMISE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				</tr>
				<tr>
				<td align="right" valign="top" colspan="3"  >&nbsp; </td>
				</tr>
				<tr id="product_saleicon_text_id"  style="display:none">
				<td align="right" valign="middle">Product Sale Icon Text &nbsp;</td><td align="left" valign="top"><textarea name="product_saleicon_text" id="product_saleicon_text" rows="3" cols="40"></textarea><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SHOW_PROD_PRICEPROMISE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				<td align="left" valign="top">&nbsp;</td>
				</tr>
				<tr id="product_newicon_text_id" style="display:none" >
				<td align="right"  valign="middle"> Product New Icon Text &nbsp;</td><td align="left" valign="top"><textarea name="product_newicon_text" rows="3" cols="40"></textarea><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SHOW_PROD_PRICEPROMISE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
				<td align="right" valign="top"  >&nbsp; </td>
				</tr>
				</table></td>
			  </tr>
			  <tr style="display:none" id="show_commonprod_id">
			  <td colspan="3" align="left" class="tdcolorgray">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
				<td colspan="3" align="left" class="listingtableheader">Common Product Specification Settings</td>
				</tr>
				<tr>
				<td width="25%" align="right" class="tdcolorgray">Product Specification Page Full Path</td>
				<td width="40%" align="left" class="tdcolorgray"><input type="text" name="product_commonsizechart_link" id="product_commonsizechart_link" value="" size="80" /></td>
				<td width="35%" align="left">&nbsp;&nbsp;<strong>(e.g. http://<?php echo $ecom_hostname?>/[pagename].html)</strong></td>
				</tr>
				<tr>
				<td width="25%" align="right" class="tdcolorgray">Link Target</td>
				<td width="40%" align="left" class="tdcolorgray"><select name="produt_common_sizechart_target" id="produt_common_sizechart_target">
				<option value="_blank">New Window</option>
				<option value="_self">Same Window</option>
				</select></td>
				<td width="35%" align="left">&nbsp;</td>
				</tr>
				</table>
			  </td>
			  </tr>
              <tr style="display:none" id="show_bulkdisc_id">
			  <td colspan="3" align="left" class="tdcolorgray">
              <table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
               		<td width="24%"  align="left" class="tdcolorgray">
			<label>Enable Bulk Discount?</label>&nbsp;&nbsp;			  </td>

			   <td width="76%" colspan="2" align="left" class="tdcolorgray"><span class="">
			     <input type="radio" name="enable_bulkdiscount_radio" value="1" />
                 <label>Yes </label>
                 <input type="radio" name="enable_bulkdiscount_radio" value="0" />
                 <label>No </label>
               </span>                   <a href="#" onmouseover ="ddrivetip('<? echo "Allows to decide whether bulk discount option is to be enabled or disabled for products";?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
              </tr>
			  </table>
              </td>
              </tr>
                          
               <tr style="display:none" id="show_in_mobile_api_sites_id">
			  <td colspan="3" align="left" class="tdcolorgray">
              <table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
               		<td width="24%"  align="left" class="tdcolorgray">
			<label>Show in Mobile Application?</label>&nbsp;&nbsp;			  </td>

			   <td width="76%" colspan="2" align="left" class="tdcolorgray"><span class="">
			     <input type="radio" name="enable_in_mobile_api_sites" value="1" />
                 <label>Yes </label>
                 <input type="radio" name="enable_in_mobile_api_sites" value="0" />
                 <label>No </label>
               </span>                   </td>
              </tr>
			  </table>
              </td>
              </tr>
              
              
              <tr>
		<td colspan="3" align="left" class="seperationtd">Select products for which  you want to set the above settings <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_PRODUCTS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		</tr>
              <tr>
                <td colspan="3" align="left" class="tdcolorgray"><input type="radio" name="select_products" value="All" onclick="display_product_selector(this.value)"/>
Apply to all products
  <input type="radio" name="select_products" value="Bycat"  onclick="display_product_selector(this.value)"/> 
                Select products by category</td>
              </tr>
            
			  
           
		      <tr id="categoryselector_id" style="display:none">
		        <td align="left" valign="middle" class="tdcolorgraynormal">&nbsp;</td>
                <td colspan="2" align="left" valign="middle" class="tdcolorgraynormal"><span class="tdcolorgray">Select Category
                    <?php
					
			  	$cat_arr = generate_category_tree(0,0,false,false,true);
				if(is_array($cat_arr))
				{	
					echo generateselectbox('settings_categoryid',$cat_arr,'','','call_ajax_display_products(this.value)',0);
				}
			  ?>
                </span>
				&nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_CATEGORY')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    </tr>
              <tr>
                <td colspan="3" align="left" valign="middle" class="tdcolorgraynormal">&nbsp;</td>
              </tr>
        <tr id="productselector_id" style="display:" >
          <td colspan="3" align="left" valign="middle" class="tdcolorgraynormal"><div id="listprod_div"></div></td>
        </tr>
			</table>
			</div>
			</td>
		</tr>  
		
		<tr>
      <td height="48" colspan="4" class="sorttd">
		<div class="editarea_div">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
         <tr>
           <td width="100%" align="right" valign="top" class="tdcolorgraynormal" ><input name="Submit" type="submit" class="red" value="Set values" /></td>
         </tr>
		</table>
		</div>
		</td>
		</tr>
		
        <tr>
          <td width="16%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray">

		  	<input type="hidden" name="productname" id="productname" value="<?=$_REQUEST['productname']?>" />
		  	<input type="hidden" name="manufactureid" id="manufactureid" value="<?=$_REQUEST['manufactureid']?>" />
		  	<input type="hidden" name="vendorid" id="vendorid" value="<?=$_REQUEST['vendorid']?>" />
			<input type="hidden" name="rprice_from" id="rprice_from" value="<?=$_REQUEST['rprice_from']?>" />
			<input type="hidden" name="rprice_to" id="rprice_to" value="<?=$_REQUEST['rprice_to']?>" />
			<input type="hidden" name="cprice_from" id="cprice_from" value="<?=$_REQUEST['cprice_from']?>" />
			<input type="hidden" name="cprice_to" id="cprice_to" value="<?=$_REQUEST['cprice_to']?>" />
			<input type="hidden" name="discount" id="discount" value="<?=$_REQUEST['discount']?>" />
			<input type="hidden" name="discountas" id="discountas" value="<?=$_REQUEST['discountas']?>" />
			<input type="hidden" name="bulkdiscount" id="bulkdiscount" value="<?=$_REQUEST['bulkdiscount']?>" />
			<input type="hidden" name="stockatleast" id="stockatleast" value="<?=$_REQUEST['stockatleast']?>" />
			<input type="hidden" name="preorder" id="preorder" value="<?=$_REQUEST['preorder']?>" />
			<input type="hidden" name="prodhidden" id="prodhidden" value="<?=$_REQUEST['prodhidden']?>" />
			<input type="hidden" name="in_mobile_api_sites" id="in_mobile_api_sites" value="<?=$_REQUEST['in_mobile_api_sites']?>" />
			<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
			<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
			<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
			<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
			<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
			<input type="hidden" name="retdiv_id" id="retdiv_id" value="maincontent" />
			<input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
		
			
		  <input type="hidden" name="fpurpose" id="fpurpose" value="save_settingstomany" /></td>
        </tr>
        <tr>
          <td colspan="5" align="left" valign="bottom" class="tdcolorgray">&nbsp;</td>
        </tr>
      </table>
 	
</form>	 

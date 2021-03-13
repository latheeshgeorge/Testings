<?php
	/*#################################################################
	# Script Name 	: edit_product_variable.php
	# Description 	: Page for editing Product variables
	# Coded by 		: Sny
	# Created on	: 28-Jun-2007
	# Modified by	: Sny
	# Modified On	: 23-Jul-2007
	#################################################################*/
//Define constants for this page
$page_type = 'Products';
$help_msg = 'This section helps in editing Product Variables';
$show_popupmsg = 0;
// ============================================================================================
// Get the name of current product
// ============================================================================================
$sql_prod = "SELECT product_name,product_variablestock_allowed,product_variablecomboprice_allowed, 
					product_variablecombocommon_image_allowed 
				FROM 
					products 
				WHERE 
					product_id=".$_REQUEST['checkbox'][0]." 
					AND sites_site_id = $ecom_siteid 
				LIMIT 
					1";
$ret_prod = $db->query($sql_prod);
if ($db->num_rows($ret_prod))
{
	$row_prod = $db->fetch_array($ret_prod);
	$showprodname = stripslashes($row_prod['product_name']);
	if($row_prod['product_variablestock_allowed']=='Y' or $row_prod['product_variablecomboprice_allowed']=='Y' or $row_prod['product_variablecombocommon_image_allowed'] == 'Y')
	{
		$show_popupmsg = 1;
	}
}
else
	exit;

// ============================================================================================	
// Get the list of shops existsing in current site
// ============================================================================================
$sql_shops = "SELECT shop_id,shop_title FROM sites_shops WHERE sites_site_id=$ecom_siteid ORDER BY shop_order";
$ret_shops = $db->query($sql_shops);
$shop_arr = array();
if ($db->num_rows($ret_shops))
{
	while ($row_shops = $db->fetch_array($ret_shops))
	{
		$shopid_arr[] = $row_shops['shop_id'];
	}
}

// ============================================================================================
// Get the details of variable being editing
// ============================================================================================
$sql_var = "SELECT * FROM product_variables WHERE var_id=$edit_id";
$ret_var = $db->query($sql_var);
if ($db->num_rows($ret_var))
{
    $row_var = $db->fetch_array($ret_var);
    //$clr_arr = array('color','colour','colors','colours');
    //if(in_array(strtolower($row_var['var_name']),$clr_arr))
        $showdropdownoption = 1;
    //else
    //   $showdropdownoption = 0;
}

// ============================================================================================
// Check whether dropdown type options is to be displayed 
// ============================================================================================
$sql_theme = "SELECT theme_var_onlyasdropdown 
				FROM 
					themes 
				WHERE 
					theme_id=$ecom_themeid 
				LIMIT 
					1";
$ret_theme = $db->query($sql_theme);
if($db->num_rows($ret_theme))
{
	$row_theme = $db->fetch_array($ret_theme);
	$show_dropdown_style_option = $row_theme['theme_var_onlyasdropdown'];
}
?>	
<script charset="UTF-8" src="js/jquery.js" type="text/javascript"></script>
<script type="text/javascript" src="js/mColorPicker.js"></script>
<script language="javascript" type="text/javascript">
	var pname 			= '<?=$_REQUEST['productname']?>';
	var manid 			= '<?=$_REQUEST['manufactureid']?>';
	var catid 			= '<?=$_REQUEST['categoryid']?>';
	var vendorid 		= '<?=$_REQUEST['vendorid']?>';
	var rprice_from 	= '<?=$_REQUEST['rprice_from']?>';
	var rprice_to 		= '<?=$_REQUEST['rprice_to']?>';
	var cpricefrom 		= '<?=$_REQUEST['cprice_from']?>';
	var cpriceto 		= '<?=$_REQUEST['cprice_to']?>';
	var discount 		= '<?=$_REQUEST['discount']?>';
	var discountas 		= '<?=$_REQUEST['discountas']?>';
	var bulkdiscount 	= '<?=$_REQUEST['bulkdiscount']?>';
	var stockatleast	= '<?=$_REQUEST['stockatleast']?>';
	var preorder 		= '<?=$_REQUEST['preorder']?>';
	var prodhidden 		= '<?=$_REQUEST['prodhidden']?>';
	var sortby 			= '<?php echo $sort_by?>';
	var sortorder 		= '<?php echo $sort_order?>';
	var recs 			= '<?php echo $records_per_page?>';
	var start			= '<?php echo $start?>';
	var pg 				= '<?php echo $pg?>';
	var maintainstock	= '<?php echo $gen_arr['product_maintainstock']?>';
	/* preloading the image to be shown on loading*/
	pic1= new Image(); 
	pic1.src="images/loading.gif";
function valforms(frm)
{
	var atleastone = false;
	var cur_neg =0;
	var cur_negord =0;
	var cur_num =0;
	fieldRequired = Array('var_name');
	fieldDescription = Array('Variable Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	if(document.frmEditProductVariable.var_value_exists[0].checked==false)
		fieldNumeric = Array('var_order','var_price');
	else
		fieldNumeric = Array('var_order');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
	{
		if(document.frmEditProductVariable.var_value_exists[0].checked)
		{   
			for(i=0;i<document.frmEditProductVariable.elements.length;i++)
			{  
				if (document.frmEditProductVariable.elements[i].name.substr(0,11)=='extvar_val_')
				{ 
					if(document.frmEditProductVariable.elements[i].value!='')
					{
						atleastone = true;
					}	
				}
				else if (document.frmEditProductVariable.elements[i].name=='var_val[]')
				{
					if(document.frmEditProductVariable.elements[i].value!='')
					{
					   
						atleastone = true;
					}	
				}
				 
				if (document.frmEditProductVariable.elements[i].name.substr(0,16)=='extvar_valprice_')
				{ 
				curval = document.frmEditProductVariable.elements[i].value;
					if(curval<0)
					{
					 cur_neg =1;
					 document.frmEditProductVariable.elements[i].focus();
					}
					if(isNaN(curval))
					{
					  	cur_num =1;
						document.frmEditProductVariable.elements[i].focus();
					}
					var re = /[!,@,#,$,<,>,",',%,&,*,;,(,)]/i;	    
				var result = curval.search(re); // checks invalid characters 
				if( result != -1 )  	{
					result++;
					alert('Invalid Charater at Position '+result+' of price'); 
					document.frmEditProductVariable.elements[i].focus();
					document.frmEditProductVariable.elements[i].select();
				   	return false;
					}
				}
				if (document.frmEditProductVariable.elements[i].name.substr(0,16)=='extvar_valorder_')
				{ 
				curval = document.frmEditProductVariable.elements[i].value;
				if(curval<0)
					{
					 cur_negord =1;
					 document.frmEditProductVariable.elements[i].focus();
					}
					var re = /[!,@,#,$,<,>,",',%,&,*,;,(,)]/i;	    
				var result = curval.search(re); // checks invalid characters 
				if( result != -1 )  	
				{
					result++;
					alert('Invalid Charater at Position '+result+' of order'); 
					document.frmEditProductVariable.elements[i].focus();
					document.frmEditProductVariable.elements[i].select();
				   	return false;
					}
				}
				
			}
			if(cur_negord ==1)
			{
			  alert('Sort order should be positive one.'); 
			  return false;
			}
			if(cur_num ==1)
			{
			  alert('Additional price should be numeric one.'); 
			  return false;
			}
		}
		else
			atleastone = true;	
		if (atleastone==true)
		{
			$(this).serialize();
			show_processing();
			return true;
		}
		else
		{
			alert('Please specify atleast one value for the variable');
			return false;
		}
		$(this).serialize();
		return true;	
	}	
	else
	{
		return false;
	}
}
function ajax_return_contents() 
{
	var ret_val = '';
	var disp 	= 'no';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val 	= req.responseText;
			document.getElementById('prodvar_div').innerHTML = ret_val; /* Setting the output to required div */
			hide_processing();
		}
		else
		{
			 show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}
function assign_color_value_image(valueid)
{
	obj = document.getElementById('var_value_display_dropdown');
	if(obj)
	{
		if(obj.checked==false)
		{
			document.frmEditProductVariable.src_id.value = valueid;
			document.frmEditProductVariable.fpurpose.value = 'add_prodvarimg';
			document.frmEditProductVariable.submit();
		}	
	}	
}
function delete_color_value_image(valueid)
{
	obj = document.getElementById('var_value_display_dropdown');
	if(obj)
	{
		if(obj.checked==false)
		{
			if(confirm('Are you sure you want to unassign the image?'))
			{
				document.frmEditProductVariable.fpurpose.value = 'rem_prodvarimg';
				document.frmEditProductVariable.src_id.value = valueid;
				document.frmEditProductVariable.saveandaddmore.value=1;
				document.frmEditProductVariable.remvarvalueimg.value=1;
				document.frmEditProductVariable.submit()
			}
		}		
	}
}
function handle_color_boxes(obj)
{
	for(i=0;i<document.frmEditProductVariable.elements.length;i++)
	{
		if ( document.frmEditProductVariable.elements[i].name.substr(0,16)=='var_valcolorcode' || document.frmEditProductVariable.elements[i].name.substr(0,20)=='extvar_valcolorcode_')
		{
			if(obj.value==0)
			{
				document.frmEditProductVariable.elements[i].className='normal_class';
				document.frmEditProductVariable.elements[i].readOnly  = false;
			}
			else
			{
				document.frmEditProductVariable.elements[i].className='disabled_class';
				document.frmEditProductVariable.elements[i].readOnly  = true;
			}	
	
		}
	}
}
function call_ajax_changevariablelist()
{
	var atleastone 								= 0;
	var prodid									= '<?php echo $_REQUEST['checkbox'][0]?>';
	var editid									= '<?php echo $_REQUEST['edit_id']?>';
	var ch_ids 									= '';
	var ch_variable								= '';
	var qrystr									= '';
	var atleastmsg 								= '';
	var confirmmsg 								= '';
	var fpurpose								= 'prodvar_onchange';
    /*var color_arr                                                           = new Array('color','colour','colors','colours');*/
	show_sppopup();
	if(document.frmEditProductVariable.var_value_exists[0].checked)
	{
            var var_value_exists = 1;
            document.getElementById('add_more_div').style.display='inline';
            /*if(in_array(document.getElementById('var_name').value.toLowerCase(),color_arr))
            {*/
			if(document.getElementById('var_value_dropdown_tr'))
	        	document.getElementById('var_value_dropdown_tr').style.display='';
           /* } 
            else
               document.getElementById('var_value_dropdown_tr').style.display='none';*/ 
	}
	else
	{
            var var_value_exists = 0;
            document.getElementById('add_more_div').style.display='none';
			if(document.getElementById('var_value_dropdown_tr'))
	            document.getElementById('var_value_dropdown_tr').style.display='none';
	}
        document.getElementById('prodvar_div').innerHTML = '<center><img src="images/loading.gif" alt="Loading"></center>';
	Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&cur_prodid='+prodid+'&edit_id='+editid+'&'+qrystr+'&var_value_pass='+var_value_exists);
}
function in_array( what, where )
{
    var a=false;
    for(var i=0;i<where.length;i++)
    {
        if(what == where[i])
        {
            a=true;
            break;
        }
    }
    return a;
}
function do_operation(mod)
{
	switch(mod)
	{
		case 'setprice':
			var setval 		= document.getElementById('setprice').value;
			if(setval=='')
			{
				alert('Please specify the price to be set to all price fields');
				return false;
			}
			if (confirm('Are you sure you want to set the price of all values for the specified price?'))
			{
				for (i=0;i<document.frmEditProductVariable.elements.length;i++)
				{
					if (document.frmEditProductVariable.elements[i].name.substr(0,16)=='extvar_valprice_' || document.frmEditProductVariable.elements[i].name.substr(0,13)=='var_valprice_')
					{
						document.frmEditProductVariable.elements[i].value = setval;
					}
				}
			}
		break;
	};
}
function copy_from_prev(token)
{
	shop_arr = new Array();
	<?php
		if (count(shopid_arr))
		{
			for($i=0;$i<count($shopid_arr);$i++)
			{
				echo "shop_arr[$i]=".$shopid_arr[$i].";";
			}
		}
	?>
	/* splitting the token */
	tok_arr = token.split('~');
	var src		= tok_arr[0];
	var dest 	= tok_arr[1];
	var cursrc	= 'extvar_valprice_'+src;
	srclen  = cursrc.length;
	var curdest= 'extvar_valprice_'+dest;
	for(i=0;i<document.frmEditProductVariable.elements.length;i++)
	{
		if (document.frmEditProductVariable.elements[i].name.substr(0,srclen)==cursrc)
		{
			split_arr =  document.frmEditProductVariable.elements[i].name.split('_');
			varid = split_arr[3];
			obj	=  eval("document.frmEditProductVariable."+curdest+"_"+varid);
			obj.value = document.frmEditProductVariable.elements[i].value;
		}
	}
	var cursrc	= 'var_valprice_'+src;
	srclen  = cursrc.length;
	var curdest= 'var_valprice_'+dest;
	for(i=0;i<document.frmEditProductVariable.elements.length;i++)
	{
		if (document.frmEditProductVariable.elements[i].name.substr(0,srclen)==cursrc)
		{
			split_arr =  document.frmEditProductVariable.elements[i].name.split('_');
			varid = split_arr[3];
			obj	=  eval("document.frmEditProductVariable."+curdest+"_"+varid);
			obj.value = document.frmEditProductVariable.elements[i].value;
		}
	}
}
function hide_sppopup()
{
	if(document.getElementById('popup_msg_div'))
	{
		document.getElementById('popup_msg_div').style.display = 'none';
	}
}
function show_sppopup()
{
	var showme = <?php echo $show_popupmsg?>;
	if(showme==1)
	{
		if(document.getElementById('popup_msg_div'))
		{
			document.getElementById('popup_msg_div').style.display = '';
		}
	}
}
function delete_var_value(valid,varid,prodid)
{
	if (confirm('Are you sure you want to Delete the value ?'))
	{
		var qrystr																		= 'val_id='+valid+'&var_id='+varid+'&prod_id='+prodid;
		var fpurpose																	= 'del_variable_val';
		document.getElementById('retdiv_id').value 						= 'prodvar_div';
		obj																				= eval("document.getElementById('prodvar_div')");
		obj.innerHTML 																= '<center><img src="images/loading.gif" alt="Loading"></center>';
		/* Calling the ajax function */
		Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&'+qrystr);	
	}	
}
</script>
<style>
.floatmsg_divcls{
	background-color:#FEEAA4;
	color:#E60000;
	position:absolute;
	top:51%;
	left:33%;
	width:500px;
	height:90px;
	font-family: Arial, Helvetica, sans-serif;
	font-size:12px;
	font-weight:normal;
	border:2px solid #000000;
	text-align:left;
}
</style>
<div id="popup_msg_div" class="floatmsg_divcls" style="display:none">
<div style="float:right; padding:6px 3px">
<a href="javascript:hide_sppopup()"><image src="images/close.gif" border="0"></a>
</div>
<div style="float:left; padding:5px 5px"><img src="images/alert.gif" /></div>
<div style="padding:40px 6px">
If the option set for the field "Values requred for this variable?" is changed for this product, the combination details (i.e. details under "Stock Tab" in product edit page) will get reset after the variable details is saved.
</div>
</div>
<form name='frmEditProductVariable' action='home.php?request=products' method="post" onsubmit="return valforms(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=products&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&start=<?php echo $_REQUEST['start']?>&pg=<?php echo $_REQUEST['pg']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>">List Products</a><a href="home.php?request=products&amp;fpurpose=edit&amp;checkbox[0]=<?php echo $_REQUEST['checkbox'][0]?>&amp;productname=<?php echo $_REQUEST['productname']?>&amp;manufactureid=<?php echo $_REQUEST['manufactureid']?>&amp;categoryid=<?php echo $_REQUEST['categoryid']?>&amp;vendorid=<?php echo $_REQUEST['vendorid']?>&amp;rprice_from=<?php echo $_REQUEST['rprice_from']?>&amp;rprice_to=<?php echo $_REQUEST['rprice_to']?>&amp;cprice_from=<?php echo $_REQUEST['cprice_from']?>&amp;cprice_to=<?php echo $_REQUEST['cprice_to']?>&amp;discount=<?php echo $_REQUEST['discount']?>&amp;discountas=<?php echo $_REQUEST['discountas']?>&amp;bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&amp;stockatleast=<?php echo $_REQUEST['stockatleast']?>&amp;preorder=<?php echo $_REQUEST['preorder']?>&amp;prodhidden=<?php echo $_REQUEST['prodhidden']?>&amp;start=<?php echo $_REQUEST['start']?>&amp;pg=<?php echo $_REQUEST['pg']?>&amp;records_per_page=<?php echo $_REQUEST['records_per_page']?>&amp;sort_by=<?php echo $sort_by?>&amp;sort_order=<?php echo $sort_order?>&curtab=<?=$_REQUEST['curtab']?>">Edit Product</a> <span> Edit Product Variable for &quot;<?php echo $showprodname?>&quot; </span></div></td>
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
          <td align="center" valign="middle" class="errormsg" ><?=$alert?></td>
          </tr>
		 <?php
		 	}
		 ?>
         <tr>
           <td align="left" valign="top" class="tdcolorgraynormal" >
		   <div class="editarea_div">
		   <table width="100%" border="0" cellspacing="1" cellpadding="1">
             <tr>
               <td width="19%" align="left">Variable Name<span class="redtext"> *</span></td>
               <td width="41%" align="left"><input name="var_name" type="text" id="var_name" value="<?php echo stripslashes($row_var['var_name'])?>" size="30" maxlength="100" /></td>
               <td width="11%" align="left">Hide</td>
               <td width="29%" align="left"><input type="radio" name="var_hide" value="1" <?php echo ($row_var['var_hide']==1)?'checked="checked"':''?> />Yes
               <input name="var_hide" type="radio" value="0" <?php echo ($row_var['var_hide']==0)?'checked="checked"':''?> />No
               <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_VAR_EDIT_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
             </tr>
             <tr>
               <td align="left">Values requred for this variable? </td>
               <td align="left"><input name="var_value_exists" id="var_value_exists" type="radio" value="1" <?php echo ($row_var['var_value_exists']==1)?'checked="checked"':''?> onclick="call_ajax_changevariablelist()"/>Yes
               <input name="var_value_exists" id="var_value_exists" type="radio" value="0" <?php echo ($row_var['var_value_exists']==0)?'checked="checked"':''?> onclick="call_ajax_changevariablelist()" />No
               <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_VAR_ADDVAL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
               <td align="left">Order</td>
               <td align="left"><input name="var_order" type="text" size="5" value="<?php echo $row_var['var_order']?>"/></td>
             </tr>
			 <?php
			 if($show_dropdown_style_option==0)
			 {
			 ?>
              <tr id='var_value_dropdown_tr' style="display:<?php echo ($row_var['var_value_exists']==1 and $showdropdownoption)?'':'none'?>">
               <td align="left">Display Values in Dropdown box? </td>
               <td align="left"><input name="var_value_display_dropdown" id="var_value_display_dropdown" type="radio" value="1" <?php echo ($row_var['var_value_display_dropdown']==1)?'checked="checked"':''?> onclick="handle_color_boxes(this)"/>Yes
  <input name="var_value_display_dropdown" id="var_value_display_dropdown" type="radio" value="0" <?php echo ($row_var['var_value_display_dropdown']==0)?'checked="checked"':''?> onclick="handle_color_boxes(this)"/>
No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_VAR_EDITVAL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
               <td align="left">&nbsp;</td>
               <td align="left">&nbsp;</td>
             </tr>
			 <?php
			 }
			 ?> 
             <tr>
               <td colspan="4" align="left">&nbsp;</td>
             </tr>
           <tr>
               <td colspan="4" align="left">
			   <div id="prodvar_div" style="text-align:center">
			   <?php
			   	showvariablevalue_list($_REQUEST['checkbox'][0],$_REQUEST['edit_id'],$row_var['var_value_exists'],0);
			  	?> 
			   </div>
			   </td>
			</tr>     
         
         <tr>
           <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
         </tr>
        <tr>
          <td width="19%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td colspan="3" align="left" valign="middle" class="tdcolorgray">

		  	<input type="hidden" name="productname" id="productname" value="<?=$_REQUEST['productname']?>" />
		  	<input type="hidden" name="manufactureid" id="manufactureid" value="<?=$_REQUEST['manufactureid']?>" />
		  	<input type="hidden" name="categoryid" id="categoryid" value="<?=$_REQUEST['categoryid']?>" />
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
			<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
			<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
			<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
			<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
			<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
			<input type="hidden" name="parent_id" id="parent_id" value="<?=$_REQUEST['parent_id']?>" />
			<input type="hidden" name="edit_id" id="edit_id" value="<?=$_REQUEST['edit_id']?>" />
			<input type="hidden" name="checkbox[0]" id="checkbox[0]" value="<? echo $_REQUEST['checkbox'][0];?>" />
			<input type="hidden" name="curtab" id="curtab" value="<?=$_REQUEST['curtab']?>" />
			<input type="hidden" name="fpurpose" id="fpurpose" value="save_editprodvar" />
			<input type="hidden" name="saveandaddmore" id="saveandaddmore" value="0" />
			<input type="hidden" name="src_page" id="src_page" value="prodvarimg" />
			<input type="hidden" name="srcvar_id" id="srcvar_id" value="<?=$_REQUEST['edit_id']?>" />
			<input type="hidden" name="remvarvalueimg" id="remvarvalueimg" value="" />
			<input type="hidden" name="src_id" id="src_id" value="" />
				<input type="hidden" name="retdiv_id" id="retdiv_id" value="" />

			</td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td colspan="3" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
      </table>
	  </div>
	  	</td>
		</tr>
		<?php 
			 $edit_id = $_REQUEST['checkbox'][0];
		     $grid_proceed = grid_enablecheck($edit_id);
             if($grid_proceed==false)
             {
			?>
		<tr>
           <td align="left" valign="top" class="tdcolorgraynormal" >
		   <div class="editarea_div">
		   <table width="100%" border="0" cellspacing="1" cellpadding="1">
             <tr>
               <td width="100%" align="right" valign="middle">
			   <div style="display:inline; width:20px">
			<input name="prodvar_Submit" type="submit" class="red" value="Save" /> 
			</div>
			<div id="add_more_div" style="display:<?php echo ($row_var['var_value_exists']==1)?'inline':'none'?>; width:40px; padding-left:10px">
			<input name="prodvarmore_Submit" type="button" class="red" value="Save & Add more value" onclick="document.frmEditProductVariable.saveandaddmore.value=1;document.frmEditProductVariable.submit()" />
			</div>
			   </td>
			 </tr>
		  </table>
		  </div>
		  </td>
		</tr>
		<?php
	}
		?>
		</table>
</form>	  


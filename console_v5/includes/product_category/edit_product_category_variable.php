<?php
	/*#################################################################
	# Script Name 	: add_product_category.php
	# Description 	: Page for adding Product Category
	# Coded by 		: Sny
	# Created on	: 22-June-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
	//Define constants for this page
	$page_type = 'Product Category Variable';
	$help_msg = get_help_messages('EDIT_PROD_CAT_VAR');
	
	$sql_catvar	= "SELECT * FROM product_category_searchrefine_keyword 
					WHERE refine_id = ".$_REQUEST['varID']." AND product_categories_category_id = ".$_REQUEST['cur_catid'];
	$ret_catvar = $db->fetch_one_row($sql_catvar);
	
	$refine_display_style = $ret_catvar['refine_display_style'];
	if($refine_display_style == 'CHECKBOX')
	{
		$checkDisp = "display:block;"; $boxDisp = "display:none;"; $rangeDisp = "display:none;";
		$sql_chkvalues = "SELECT * FROM product_category_searchrefine_keyword_values 
						  WHERE refine_id = ".$_REQUEST['varID']." AND product_categories_category_id = ".$_REQUEST['cur_catid'];
		$ret_chkvalues = $db->query($sql_chkvalues);
	}
	if($refine_display_style == 'BOX')
	{
		$checkDisp = "display:none;"; $boxDisp = "display:block;"; $rangeDisp = "display:none;";
		$sql_boxvalues = "SELECT * FROM product_category_searchrefine_keyword_values 
						  WHERE refine_id = ".$_REQUEST['varID']." AND product_categories_category_id = ".$_REQUEST['cur_catid'];
		$ret_boxvalues = $db->query($sql_boxvalues);
	}
	if($refine_display_style == 'RANGE')
	{
		$checkDisp = "display:none;"; $boxDisp = "display:none;"; $rangeDisp = "display:block;";
	}
?>	
<script language="javascript" type="text/javascript">
function valforms(frm)
{
	var atleastone = false;
	fieldRequired = Array('var_name');
	fieldDescription = Array('Search Refine Variable Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		
			/* Check whether dispay location is selected*/
			obj = document.getElementById('group_id[]');
				var cnt = 0; 
				for(i=0;i<obj.options.length;i++)
				{
					if(obj.options[i].selected)
					{ 
					    cnt++;
						atleastone = true;
					}
				}
						
			show_processing();
			return true;
		}	
		else
		{
			return false;
		}
	
}


function change_vartype(var_type)
{
	//alert(var_type);
	if(var_type == 'CHECKBOX')
	{
		document.getElementById('BOX_TYPE').style.display = 'none';
		document.getElementById('RANGE_TYPE').style.display = 'none';
		document.getElementById('CHECK_TYPE').style.display = 'block';
	}	
	else if(var_type == 'BOX')
	{
		document.getElementById('RANGE_TYPE').style.display = 'none';		
		document.getElementById('CHECK_TYPE').style.display = 'none';		
		document.getElementById('BOX_TYPE').style.display = 'block';
	}
	else if(var_type == 'RANGE')
	{
		document.getElementById('BOX_TYPE').style.display = 'none';
		document.getElementById('CHECK_TYPE').style.display = 'none';
		document.getElementById('RANGE_TYPE').style.display = 'block';
	}
}
function add_check_item()
{
	var el = document.getElementById("CHECK_TYPE_ITEMS");
	var li = document.createElement("li");
	li.setAttribute('style', 'width:100%; height:30px;');
	li.innerHTML = 'Value &nbsp; <input type="text" name="check_item[]" value="" />&nbsp; Order &nbsp; <input type="text" name="check_item_order[]" value="" size="2"/>';
	
	el.appendChild(li);
}
function add_box_item()
{
	var el = document.getElementById("BOX_TYPE_ITEMS");
	var li = document.createElement("li");
	li.setAttribute('style', 'width:100%; height:30px;');
	li.innerHTML = 'Value &nbsp; <input type="text" name="box_item[]" value="" />&nbsp; Hash Code &nbsp; <input type="text" name="box_item_code[]" value="" size="6" class="picker"/>&nbsp; Order &nbsp; <input name="box_item_order[]" type="text" value="" size="2"  />';
	
	el.appendChild(li);
}
function deleteVar(varid)
{
	var elem, delval, new_delval;
	elem = document.getElementById(varid);
	elem.parentNode.removeChild(elem);
	
	delval = document.getElementById('delete_var_val_id').value;
	
	if(delval == "")
	{
		new_delval = varid;
	}
	else
	{
		new_delval = delval+','+varid;
	}
	document.getElementById('delete_var_val_id').value = new_delval;
}
</script>
 <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
 <script type="text/javascript" src="js/colorpicker.js"></script>
<form name='frmEditProductCategoryVariable' action='home.php' method="post" onsubmit="return valforms(this);">
<input type="hidden" name="request" value="prod_cat" />
<input type="hidden" name="fpurpose" value="edit_variables_save" />
<input type="hidden" name="search_click" value="" />
<input type="hidden" name="pass_catgroupname" id="pass_catgroupname" value="<?=$_REQUEST['pass_catgroupname']?>" />
<input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
<input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
<input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
<input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['pass_start']?>" />
<input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
<input type="hidden" name="pass_catgroupid" id="pass_catgroupid" value="<?php echo $_REQUEST['pass_catgroupid'];?>" />
<input type="hidden" name="pass_cat_id" id="pass_cat_id" value="<?php echo $_REQUEST['cur_catid'];?>" />
<input type="hidden" name="cur_catid" id="cur_catid" value="<?php echo $_REQUEST['cur_catid'];?>" />
<input type="hidden" name="pass_parentid" id="pass_parentid" value="<?php echo $_REQUEST['pass_parentid'];?>" />
<input type="hidden" name="var_id" id="var_id" value="<?php echo $_REQUEST['varID'];?>" />
  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="maintable">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=prod_cat&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&start=<?php echo $_REQUEST['start']?>&pg=<?php echo $_REQUEST['pg']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>">List Product Categories</a><a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['cur_catid']?>&cur_catid=<?php echo $_REQUEST['cur_catid']?>&parent_id=<?php $_REQUEST['parent_id']?>&catname=<?=$_REQUEST['pass_catname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=variables_tab_td">Edit Product Categories</a><span>Edit Search Refine Variable</span></div></td>
        </tr>
       <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="4">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>		 </td>
		</tr>
		<?php
			if($alert)
			{
		?>
        <tr>
          <td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
          </tr>
		 <?php
		 	}
		 ?> 
         <tr>
         <td height="48" class="sorttd" colspan="3" >
	  <div class="sorttd_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttableleftN">
        <tr>
          <td width="20%" align="left" valign="middle" class="tdcolorgray" >Variable Name <span class="redtext">*</span> </td>
          <td width="35%" align="left" valign="middle" class="tdcolorgray"><input name="var_name" id="var_name" type="text" class="input" size="25" value="<?php echo $ret_catvar['refine_caption']?>" maxlength="150" /></td>
          <td width="25%" align="left" valign="middle" class="tdcolorgray">Hide Variable</td>
          <td width="40%" align="left" valign="middle" class="tdcolorgray"><input type="radio" name="var_hide" value="1" <?php if($ret_catvar['refine_hidden']==1) echo "checked"?> />
            Yes
              <input name="var_hide" type="radio" value="0" <?php if($ret_catvar['refine_hidden']==0) echo "checked"?> />
          No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_CAT_VAR_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a></td>
        </tr>
         <tr>
           <td align="left" valign="top" class="tdcolorgray" >Variable Type</td>
           <td align="left" valign="top" class="tdcolorgray"><select name="var_type" id="var_type" onchange="change_vartype(this.value);">
             <option value="CHECKBOX" <?php if($refine_display_style == 'CHECKBOX') echo ' selected="selected"';?>>CHECKBOX</option>
             <option value="BOX" <?php if($refine_display_style == 'BOX') echo ' selected="selected"';?>>COLOUR</option>
             <option value="RANGE" <?php if($refine_display_style == 'RANGE') echo ' selected="selected"';?>>RANGE</option>
           </select> 
           </td>
           <td align="left" valign="top" class="tdcolorgray"><!--Show any product Image -->Variable Order</td>
           <td align="left" valign="top" class="tdcolorgray"><input name="var_order" type="text" id="var_order" value="<?php echo $ret_catvar['refine_order'];?>" size="2" /></td>
         </tr>
         <tr>
           <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
           <td colspan="3" align="left" valign="top" class="tdcolorgray" >
           <div id="CHECK_TYPE" style=" <?php echo $checkDisp; ?> width:100%;">
           	<ul id="CHECK_TYPE_ITEMS" style="list-style-type: none; padding:0px; margin:0px; width:100%;">
            	<li style="width:100%; height:25px;"><a href="javascript: add_check_item();" class="edittextlink"><img src="images/add.gif" style="float:left; padding-right:5px;" />Add Item</a></li>
                <?php
					if($db->num_rows($ret_chkvalues) > 0)
					{
						//$chk_exts	=	array();
						while ($row_chkvalues = $db->fetch_array($ret_chkvalues))
						{
				?><li style="width:100%; height:30px;" id="<?php echo $row_chkvalues['refineval_id'];?>">Value &nbsp; <input type="text" id="check_item_<?php echo $row_chkvalues['refineval_id'];?>" name="check_item_<?php echo $row_chkvalues['refineval_id'];?>" value="<?php echo $row_chkvalues['refineval_value'];?>" />&nbsp; Order &nbsp; <input type="text" id="check_item_order_<?php echo $row_chkvalues['refineval_id'];?>" name="check_item_order_<?php echo $row_chkvalues['refineval_id'];?>" value="<?php echo $row_chkvalues['refineval_order'];?>" size="2"  /> <a href="javascript: deleteVar('<?php echo $row_chkvalues['refineval_id'];?>');" title="Delete Variable"><img src="images/cart_delete.gif" border="0" alt="Delete"/></a></li>
				<?php	//$chk_exts[]	=	$row_chkvalues['refineval_id'];
				?>		<input type="hidden" name="chk_exts[]" value="<?php echo $row_chkvalues['refineval_id'];?>" />
                <?php	
						}
					}
					else
					{
				?><li style="width:100%; height:25px;">Value &nbsp; <input type="text" name="check_item[]" value="" />&nbsp; Order &nbsp; <input name="check_item_order[]" type="text" value="" size="2"  /></li>
                <?php
					}
				?>
            </ul>
           </div>
           
           <div id="BOX_TYPE" style=" <?php echo $boxDisp; ?> ">
           <ul id="BOX_TYPE_ITEMS" style="list-style-type: none; padding:0px; margin:0px; width:100%;">
            	<li style="width:100%; height:25px;"><a href="javascript: add_box_item();" class="edittextlink"><img src="images/add.gif" style="float:left; padding-right:5px;" />Add Item</a></li>
                <?php
					if($db->num_rows($ret_boxvalues) > 0)
					{
						$box_exts	=	array();
						while ($row_boxvalues = $db->fetch_array($ret_boxvalues))
						{
				?><li style="width:100%; height:30px;" id="<?php echo $row_boxvalues['refineval_id'];?>">Value &nbsp; <input type="text" id="box_item_<?php echo $row_boxvalues['refineval_id'];?>" name="box_item_<?php echo $row_boxvalues['refineval_id'];?>" value="<?php echo $row_boxvalues['refineval_value'];?>" />&nbsp; Hash Code &nbsp; <input type="text" id="box_item_code_<?php echo $row_boxvalues['refineval_id'];?>" name="box_item_code_<?php echo $row_boxvalues['refineval_id'];?>" value="<?php echo $row_boxvalues['refineval_color_code'];?>" size="6" class="picker" style="background-color:<?php echo $row_boxvalues['refineval_color_code'];?>" />&nbsp; Order &nbsp; <input type="text" id="box_item_order_<?php echo $row_boxvalues['refineval_id'];?>" name="box_item_order_<?php echo $row_boxvalues['refineval_id'];?>" value="<?php echo $row_boxvalues['refineval_order'];?>" size="2"  /> <a href="javascript: deleteVar('<?php echo $row_boxvalues['refineval_id'];?>');" title="Delete Variable"><img src="images/cart_delete.gif" border="0" alt="Delete"/></a></li>
				<?php	//$box_exts[]	=	$row_boxvalues['refineval_id'];
				?>		<input type="hidden" name="box_exts[]" value="<?php echo $row_boxvalues['refineval_id'];?>" />
                <?php	}
					}
					else
					{
				?><li style="width:100%; height:25px;">Value &nbsp; <input type="text" name="box_item[]" value="" />&nbsp; Hash Code &nbsp; <input type="text" name="box_item_code[]" value="" size="6" class="picker" />&nbsp; Order &nbsp; <input name="box_item_order[]" type="text" value="" size="2"  /></li>
                <?php
					}
				?>
            </ul>
           </div>
           <script type="text/javascript" >
			jQuery.noConflict();
			jQuery(function() {  
			jQuery("body").on("click",".picker",function(e){
		    e.preventDefault();
			colorPicker(e);
			})
			});
			
			jQuery(document).keyup(function(e) {
			if (e.keyCode == 27) { jQuery('.cPSkin').hide()}   // esc
});
			</script>
			<style>
			.picker{
				background-color: white;
				margin-top: 0px;
				margin-right: 0px;
				text-align:center;
				border-width:1px;
			}

			</style>
           <div id="RANGE_TYPE" style=" <?php echo $rangeDisp; ?> ">
           <table width="352" border="0" cellpadding="0" cellspacing="0">
             <tr>
               <td height="30" align="left" valign="middle" class="tdcolorgray">Minimum</td>
               <td height="30" align="left" valign="middle" class="tdcolorgray"><input type="text" name="var_lowval" id="var_lowval" value="<?php echo $ret_catvar['refine_lowval'];?>" /></td>
             </tr>
             <tr>
               <td height="30" align="left" valign="middle" class="tdcolorgray">Maximum</td>
               <td height="30" align="left" valign="middle" class="tdcolorgray"><input type="text" name="var_highval" id="var_highval" value="<?php echo $ret_catvar['refine_highval'];?>" /></td>
             </tr>
             <tr>
               <td height="30" align="left" valign="middle" class="tdcolorgray">Interval</td>
               <td height="30" align="left" valign="middle" class="tdcolorgray"><input type="text" name="var_interval" id="var_interval" value="<?php echo $ret_catvar['refine_interval'];?>" /></td>
             </tr>
             <tr>
               <td height="30" align="left" valign="middle" class="tdcolorgray">Prefix</td>
               <td height="30" align="left" valign="middle" class="tdcolorgray"><input type="text" name="var_prefix" id="var_prefix" value="<?php echo $ret_catvar['refine_prefix'];?>" /></td>
             </tr>
             <tr>
               <td height="30" align="left" valign="middle" class="tdcolorgray">Suffix</td>
               <td height="30" align="left" valign="middle" class="tdcolorgray"><input type="text" name="var_suffix" id="var_suffix" value="<?php echo $ret_catvar['refine_suffix'];?>" /></td>
             </tr>
           </table>
           </div>
           </td>
           <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
         </tr>
         <tr>
           <td colspan="4" align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
         </tr>
         
        <tr>
          <td colspan="4" align="center" valign="middle" class="tdcolorgray" >
          <input type="hidden" name="delete_var_val_id" id="delete_var_val_id" value="" />
		   <input name="var_Submit" type="submit" class="red" value="Save" />
           </td>
        </tr>
        <tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
        </tr>
        </table>
        </div>
        </td>
        </tr>
      </table>
</form>	  


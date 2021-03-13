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
	$help_msg = get_help_messages('ADD_PROD_CAT_VAR');
	
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
	li.innerHTML = 'Value &nbsp; <input type="text" name="box_item[]" value="" />&nbsp; Hash Code &nbsp; <input type="text" name="box_item_code[]" value="" size="6" class="picker" />&nbsp; Order &nbsp; <input name="box_item_order[]" type="text" value="" size="2"  />';
	
	el.appendChild(li);
}
</script>
 <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
 <script type="text/javascript" src="js/colorpicker.js"></script>
<form name='frmAddProductCategoryVariable' action='home.php' method="post" onsubmit="return valforms(this);">
<input type="hidden" name="request" value="prod_cat" />
<input type="hidden" name="fpurpose" value="add_variables" />
<input type="hidden" name="search_click" value="" />
<input type="hidden" name="pass_catgroupname" id="pass_catgroupname" value="<?=$_REQUEST['pass_catgroupname']?>" />
<input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
<input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
<input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
<input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['pass_start']?>" />
<input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
<input type="hidden" name="pass_catgroupid" id="pass_catgroupid" value="<?php echo $_REQUEST['pass_catgroupid']?>" />
<input type="hidden" name="pass_cat_id" id="pass_cat_id" value="<?php echo $_REQUEST['pass_cat_id']?>" />
<input type="hidden" name="cur_catid" id="cur_catid" value="<?php echo $_REQUEST['pass_cat_id']?>" />
<input type="hidden" name="pass_parentid" id="pass_parentid" value="<?php echo $_REQUEST['pass_parentid']?>" />
  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="maintable">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=prod_cat&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&start=<?php echo $_REQUEST['start']?>&pg=<?php echo $_REQUEST['pg']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>">List Product Categories</a><a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_cat_id']?>&cur_catid=<?php echo $_REQUEST['pass_cat_id']?>&parent_id=<?php $_REQUEST['parent_id']?>&catname=<?=$_REQUEST['pass_catname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=variables_tab_td">Edit Product Categories</a><span> Add Search Refine Variable</span></div></td>
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
         <td height="48" class="sorttd" colspan="4" >
	  <div class="sorttd_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttableleftN">
        <tr>
          <td width="20%" align="left" valign="middle" class="tdcolorgray" >Variable Name <span class="redtext">*</span> </td>
          <td width="35%" align="left" valign="middle" class="tdcolorgray"><input name="var_name" id="var_name" type="text" class="input" size="25" value="<?php echo $_REQUEST['var_name']?>" maxlength="150" /></td>
          <td width="25%" align="left" valign="middle" class="tdcolorgray">Hide Variable</td>
          <td width="40%" align="left" valign="middle" class="tdcolorgray"><input type="radio" name="var_hide" value="1" <?php if($_REQUEST['var_hide']==1) echo "checked"?> />
            Yes
              <input name="var_hide" type="radio" value="0" <?php if($_REQUEST['var_hide']==0) echo "checked"?> />
          No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_CAT_VAR_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a></td>
        </tr>
         <tr>
           <td align="left" valign="top" class="tdcolorgray" >Variable Type</td>
           <td align="left" valign="top" class="tdcolorgray"><select name="var_type" id="var_type" onchange="change_vartype(this.value);">
             <option value="CHECKBOX">CHECKBOX</option>
             <option value="BOX">COLOUR</option>
             <option value="RANGE">RANGE</option>
           </select>             <!--<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_PARENT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a>--></td>
           <td align="left" valign="top" class="tdcolorgray"><!--Show any product Image -->Variable Order</td>
           <td align="left" valign="top" class="tdcolorgray"><input name="var_order" type="text" id="var_order" value="0" size="2" /></td>
         </tr>
         <tr>
           <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
           <td colspan="3" align="left" valign="top" class="tdcolorgray" >
           <div id="CHECK_TYPE" style="display:block; width:100%;">
           	<ul id="CHECK_TYPE_ITEMS" style="list-style-type: none; padding:0px; margin:0px; width:100%;">
            	<li style="width:100%; height:25px;"><a href="javascript: add_check_item();" class="edittextlink"><img src="images/add.gif" style="float:left; padding-right:5px;" />Add Item</a></li>
                <li style="width:100%; height:30px;">Value &nbsp; <input type="text" name="check_item[]" value="" />&nbsp; Order &nbsp; <input name="check_item_order[]" type="text" value="" size="2"  /></li>
            </ul>
           </div>
           
           <div id="BOX_TYPE" style="display:none;">
           <ul id="BOX_TYPE_ITEMS" style="list-style-type: none; padding:0px; margin:0px; width:100%;">
            	<li style="width:100%; height:25px;"><a href="javascript: add_box_item();" class="edittextlink"><img src="images/add.gif" style="float:left; padding-right:5px;" />Add Item</a></li>
                <li style="width:100%; height:30px;">Value &nbsp; <input type="text" name="box_item[]" value="" />&nbsp; Hash Code &nbsp; <input type="text" name="box_item_code[]" value="" size="6" class="picker" />&nbsp; Order &nbsp; <input name="box_item_order[]" type="text" value="" size="2"  /></li>
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
           <div id="RANGE_TYPE" style="display:none;">
           <table width="352" border="0" cellpadding="0" cellspacing="0">
             <tr>
               <td height="30" align="left" valign="middle" class="tdcolorgray">Minimum</td>
               <td height="30" align="left" valign="middle" class="tdcolorgray"><input type="text" name="var_lowval" id="var_lowval" value="" /></td>
             </tr>
             <tr>
               <td height="30" align="left" valign="middle" class="tdcolorgray">Maximum</td>
               <td height="30" align="left" valign="middle" class="tdcolorgray"><input type="text" name="var_highval" id="var_highval" value="" /></td>
             </tr>
             <tr>
               <td height="30" align="left" valign="middle" class="tdcolorgray">Interval</td>
               <td height="30" align="left" valign="middle" class="tdcolorgray"><input type="text" name="var_interval" id="var_interval" value="" /></td>
             </tr>
             <tr>
               <td height="30" align="left" valign="middle" class="tdcolorgray">Prefix</td>
               <td height="30" align="left" valign="middle" class="tdcolorgray"><input type="text" name="var_prefix" id="var_prefix" value="" /></td>
             </tr>
             <tr>
               <td height="30" align="left" valign="middle" class="tdcolorgray">Suffix</td>
               <td height="30" align="left" valign="middle" class="tdcolorgray"><input type="text" name="var_suffix" id="var_suffix" value="" /></td>
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
		  
		  <!--<input type="hidden" name="catname" id="catname" value="<?=$_REQUEST['catname']?>" />
		  <input type="hidden" name="parentid" id="parentid" value="<?=$_REQUEST['parentid']?>" />
		  <input type="hidden" name="catgroupid" id="catgroupid" value="<?=$_REQUEST['catgroupid']?>" />
		  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
		  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
		  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		  <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
		   <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
		   <input type="hidden" name="fpurpose" id="fpurpose" value="save_add" />-->
		   <input name="var_Submit" type="submit" class="red" value="Save" />
		   <!--<input name="var_Submit" type="submit" class="red" value="Save & Return to Edit" />--></td>
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


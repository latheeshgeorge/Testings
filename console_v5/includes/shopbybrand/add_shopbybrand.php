<?php
	/*#################################################################
	# Script Name 	: add_shopbybrand.php
	# Description 	: Page for adding Product Shop by brand
	# Coded by 		: Sny
	# Created on	: 21-Nov-2007
	# Modified by	: LG
	# Modified On	: 25-Jan-2008
	#################################################################*/
#Define constants for this page
$page_type 	= 'Product Shops';
$help_msg 	= get_help_messages('ADD_PROD_SHOP_SHORT');

?>	
<script language="javascript" type="text/javascript">
function valforms(frm)
{
	var atleastone = false;
	fieldRequired = Array('shopbrand_name');
	fieldDescription = Array('Product Shop Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
	{
			/* Check whether dispay location is selected*/
		<?php /*	obj = document.getElementById('group_id[]');
			
			var cnt = 0;
		for(i=0;i<obj.options.length;i++)
			{
				if(obj.options[i].selected)
				{ 
				    cnt++;
					atleastone = true;
				}
			}
			
				if(cnt>1)
				{	
					var def_ok = false;
					for(i=0;i<obj.options.length;i++)
					{
						if(obj.options[i].selected)
						{
							if(obj.options[i].value==document.getElementById('default_shopgroup_id').value)
								def_ok = true;
						}
					}
					if (def_ok==false)
					{
						alert('Default Product Shop Group not in selected Product Shop Group list');
						return false;
					}
				 }*/
			 ?> 	       
		if(frm.shopbrand_product_showimage.checked == false && 
				   frm.shopbrand_product_showtitle.checked == false &&
				   frm.shopbrand_product_showshortdescription.checked == false &&
				   frm.shopbrand_product_showprice.checked == false &&
				   frm.shopbrand_product_showrating.checked == false &&
				   frm.shopbrand_product_showbonuspoints.checked == false) 
	    		{
					  alert('Please Check any of Product Items to Display ')	   
					  return false;
				}		
			show_processing();
			return true;
	}
	else
	{
		return false;
	}
}
</script>
<form name='frmAddShopByBrand' action='home.php?request=shopbybrand' method="post" onSubmit="return valforms(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=shopbybrand&shopname=<?php echo $_REQUEST['shopname']?>&show_shopgroup=<?php echo $_REQUEST['show_shopgroup']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>">List Product Shops </a> <span> Add Product Shops </span></div></td>
        </tr>
       <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="4">
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
          <td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
    </tr>
		 <?php
		 	}
		 ?> 
		  <tr>
      <td colspan="4" class="tdcolorgray">
		  <div class="editarea_div">

      <table  border="0" cellpadding="0" cellspacing="0"  width="100%">
        <tr>
          <td width="20%" align="left" valign="middle" class="tdcolorgray" >Product Shop Name <span class="redtext">*</span> </td>
          <td width="34%" align="left" valign="middle" class="tdcolorgray"><input name="shopbrand_name" type="text" class="input" size="25" value="<?php echo $_REQUEST['shopbrand_name']?>" maxlength="250" /></td>
          <td width="13%" align="left" valign="middle" class="tdcolorgray" nowrap="nowrap">Hide Product Shop </td>
          <td width="33%" align="left" valign="middle" class="tdcolorgray"><input type="radio" name="shopbrand_hide" value="1" <? if($_REQUEST['shopbrand_hide']==1) echo "checked";?> />
            Yes
              <input name="shopbrand_hide" type="radio" value="0" <? if($_REQUEST['shopbrand_hide']==0) echo "checked";?> />
          No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SHOP_BRAND_HIDE')?>')"; onMouseOut="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
         <tr>
           <td align="left" valign="top" class="tdcolorgray" >Parent Shop  </td>
           <td align="left" valign="top" class="tdcolorgray">
		   <?php 
				$shop_parent = generate_shop_tree(0);
				echo generateselectbox('shopbrand_parent_id',$shop_parent,$_REQUEST['shopbrand_parent_id']);
			?>		   </td>
           <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
           <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
         </tr>
         <tr>
           <td align="left" valign="top" class="tdcolorgray" >Shop Menu   </td>
           <td align="left" valign="top" class="tdcolorgray"><?php
		   if ($_REQUEST['group_id'])
			{
				for($i=0;$i<count($_REQUEST['group_id']);$i++)
				{
					$ext_grp_arr[] = $_REQUEST['group_id'][$i];
				}
			}
		  	$group_arr 		= array();
			// Get the list of all category groups for this site
			$sql_groups = "SELECT shopbrandgroup_id,shopbrandgroup_name FROM product_shopbybrand_group WHERE sites_site_id=$ecom_siteid ORDER BY shopbrandgroup_name";
			$ret_groups = $db->query($sql_groups);
			if ($db->num_rows($ret_groups))
			{
				while ($row_groups = $db->fetch_array($ret_groups))
				{
					$grpid	= $row_groups['shopbrandgroup_id'];
					$group_arr[$grpid] = stripslashes($row_groups['shopbrandgroup_name']);
				}	
			}
			echo generateselectbox('group_id[]',$group_arr,$ext_grp_arr,'','',5);
		  ?>
             <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_SHOP_CG')?>')"; onMouseOut="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
           <td align="left" valign="top" class="tdcolorgray" nowrap="nowrap"><?php /*?>Default Shop Group   <?php */?></td>
           <td align="left" valign="top" class="tdcolorgray">
		  	<?php /*?>$default_array 		= array();
			// Get the list of all shop groups for this site
			$sql_groups = "SELECT shopbrandgroup_id,shopbrandgroup_name FROM product_shopbybrand_group WHERE sites_site_id=$ecom_siteid ORDER BY shopbrandgroup_name";
			$ret_groups = $db->query($sql_groups);
			if ($db->num_rows($ret_groups))
			{
				while ($row_groups = $db->fetch_array($ret_groups))
				{
					$grpid	= $row_groups['shopbrandgroup_id'];
					$default_array[$grpid] = stripslashes($row_groups['shopbrandgroup_name']);
				}	
			}
			echo generateselectbox('default_shopgroup_id',$default_array,$_REQUEST['default_shopgroup_id']);<?php 
             <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_SHOP_DCG')?>')"; onMouseOut="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>*/?></td>
         </tr>
		  <tr>
		    <td align="left" valign="top" class="tdcolorgray" >Shop Main Description </td>
		    <td align="left" valign="middle" class="tdcolorgray" colspan="3">
			<?php
				$editor_elements = "shopbrand_description,shopbrand_bottomdescription";
				include_once(ORG_DOCROOT."/console/js/tinymce.php");
				/*$editor 			= new FCKeditor('shopbrand_description') ;
				$editor->BasePath 	= '/console/js/FCKeditor/';
				$editor->Width 		= '650';
				$editor->Height 	= '300';
				$editor->ToolbarSet = 'BshopWithImages';
				$editor->Value 		= stripslashes($_REQUEST['shopbrand_description']);
				$editor->Create() ;*/
		       ?>
			   <textarea style="height:300px; width:500px" id="shopbrand_description" name="shopbrand_description"><?=stripslashes($_REQUEST['shopbrand_description'])?></textarea>
			</td>
    		</tr>
    		<tr>
		    <td align="left" valign="top" class="tdcolorgray" >Bottom Description (SEO Purpose) </td>
		    <td align="left" valign="middle" class="tdcolorgray" colspan="3">
			   <textarea style="height:300px; width:500px" id="shopbrand_bottomdescription" name="shopbrand_bottomdescription"><?=stripslashes($_REQUEST['shopbrand_bottomdescription'])?></textarea>
			</td>
    		</tr>
		  <tr>
		    <td align="left" valign="middle" class="tdcolorgray" >Product Display Method </td>
		    <td align="left" valign="middle" class="tdcolorgray"><?php 
			 $arr_prod_style 	= $grp_type = array();
			  	$sql_style_prod	= "SELECT product_listingstyles FROM themes WHERE theme_id=".$ecom_themeid;
				$ret_style_prod 	= $db->query($sql_style_prod);
				if ($db->num_rows($ret_style_prod))
				{
					$row_style_prod	= $db->fetch_array($ret_style_prod);
				 	$arr_prod_style	= explode(',',$row_style_prod['product_listingstyles']);
					$grp_type[0]==0;
					if (count($arr_prod_style))
					{
						foreach($arr_prod_style as $v)
						{
							$temp_arr = explode("=>",$v);
							$grp_type[$temp_arr[0]]=trim($temp_arr[1]);	 // this array will be used for all the following image type drop down boxes
						}
					}				
				 }	
					//	$grp_type = array('OneinRow'=>'One in a Row','ThreeinRow'=>'Three in a Row');
						echo generateselectbox('shopbrand_product_displaytype',$grp_type,$_REQUEST['shopbrand_product_displaytype']);
			?>
              <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SHOP_BRAND_GROUP_DISP')?>')"; onMouseOut="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		    <td align="left" valign="middle" class="tdcolorgray" nowrap="nowrap">Sub Shop List Type</td>
		    <td align="left" valign="middle" class="tdcolorgray"><?php 
				$subcat_list = array('Middle'=>'Show in Middle Area','List'=>'Show below Selected Shops');
				echo generateselectbox('shopbrand_subshoplisttype',$subcat_list,$row_shops['shopbrand_subshoplisttype']);
		  ?>
              <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHOP_BRAND_GROUP_SUBTYPE')?>')"; onMouseOut="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    </tr>
	<tr>
	<td colspan="4">
	<table width="100%" border="0">
	<tr>
    <td align="right" valign="middle"  ><input class="input" type="checkbox" name="shop_showimageofproduct"  value="1" <? if($_REQUEST['shop_showimageofproduct']==1) echo "checked";?> onClick="display_shop_image();">
    </td>
	<td width="41%" align="left" valign="middle" nowrap="nowrap" class="tdcolorgray" >Show any product Image
	<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_SHOP_SHOW_PROD_IMG')?>')"; onMouseOut="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	</tr>
	<tr>
	<td width="59%" align="right" valign="middle"><input name="shopbrand_turnoff_mainimage" type="checkbox" id="shopbrand_turnoff_mainimage" value="1" <?php echo ($row_shops['shopbrand_turnoff_mainimage']==1)?'checked="checked"':''?>/></td>
    <td  align="left" valign="middle" class="tdcolorgray">Turn Off &quot;Main Image&quot; in Shop Details page<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_CAT_SHOW_NO_MAIN_IMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    </tr>
	<tr>   
	  <td width="59%" align="right"> <input name="shopbrand_turnoff_moreimages" type="checkbox" id="shopbrand_turnoff_moreimages" value="1" <?php echo ($row_shops['shopbrand_turnoff_moreimages']==1)?'checked="checked"':''?>/>           </td>
	  <td  align="left" class="tdcolorgray">Turn Off &quot;More Images&quot; in Shop Details page<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_CAT_SHOW_NO_MORE_IMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	</tr>
	</table>
	</td>
	</tr>
		  <tr>
		    <td colspan="4" align="left" valign="middle" class="tdcolorgray" ><b>Fields to be displayed for Products</b> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SHOP_BRAND_GROUP_DISPPROD')?>')"; onMouseOut="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		    
        </tr>
		  <tr>
		    <td colspan="2" align="left" valign="middle" class="tdcolorgray" ><table width="100%" border="0">
              <tr>
                <td width="43%">&nbsp;</td>
                <td width="57%"><input name="shopbrand_product_showimage" type="checkbox" value="1" checked="checked" />
Product Image </td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
                <td><input name="shopbrand_product_showtitle" type="checkbox" value="1" checked="checked" />
Product Title </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><input name="shopbrand_product_showshortdescription" type="checkbox" value="1" checked="checked" />
Product Description </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><input name="shopbrand_product_showprice" type="checkbox" value="1" checked="checked" />
Product Price </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><input name="shopbrand_product_showrating" type="checkbox" value="1" checked="checked" />
Product Rating</td>
              </tr>
               <tr>
                <td>&nbsp;</td>
                <td><input name="shopbrand_product_showbonuspoints" type="checkbox" value="1" checked="checked" />
Product Bonus Points</td>
              </tr>
            </table></td>
		    <td colspan="2" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
    </tr>
		 
    </table>
    </div>
     </td>
       </tr> 
        <tr>
      <td colspan="4" class="tdcolorgray">
	<div class="editarea_div">
      <table  border="0" cellpadding="0" cellspacing="0"  width="100%">
        <tr>		 
          <td align="right" valign="middle" >
		  <input type="hidden" name="shopname" id="shopname" value="<?=$_REQUEST['shopname']?>" />
		  <input type="hidden" name="show_shopgroup" id="show_shopgroup" value="<?=$_REQUEST['show_shopgroup']?>" />
		  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
		  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
		  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		  <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
		   <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="save_add" />
			  <input name="shopbrand_Submit" type="submit" class="red" value="Save" />&nbsp;&nbsp;</td>
        </tr>       
       </table>
       </div>
       </td>
       </tr> 
  </table>
</form>

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
	$page_type = 'Product Category';
	$help_msg = get_help_messages('ADD_PROD_CAT1');
	// Get the default display settings for subcat and products for current site
	$sql_set = "SELECT category_subcatlisttype,product_displaytype,product_displaywhere,product_showimage,product_showtitle,
						product_showshortdescription,product_showprice,product_showrating,product_showbonuspoints,category_subcatlistmethod,category_showname,category_showimage,
						category_showshortdesc,category_turnoff_moreimages,category_turnoff_noproducts,product_orderfield,product_orderby,
						category_turnoff_treemenu,category_turnoff_pdf,subcategory_showimagetype,category_turnoff_mainimage       
				FROM 
					general_settings_sites_common 
				WHERE 
					sites_site_id = $ecom_siteid 
				LIMIT 
					1";
	$ret_set = $db->query($sql_set);
	if ($db->num_rows($ret_set))
	{
		$row_set = $db->fetch_array($ret_set);
	}
	$arr_prod_style 	= $grp_type = $subcatlstng_arr = $subcatlst_arr = array();
	$sql_style_prod	= "SELECT product_listingstyles,subcategory_listingstyles FROM themes WHERE theme_id=".$ecom_themeid;
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
		$subcatlstng_arr	= explode(',',$row_style_prod['subcategory_listingstyles']);
		$subcatlst_arr[0]==0;
		if (count($subcatlstng_arr))
		{
			foreach($subcatlstng_arr as $v)
			{
				$temp_arr = explode("=>",$v);
				$subcatlst_arr[$temp_arr[0]]=trim($temp_arr[1]);	 // this array will be used for all the following image type drop down boxes
			}
		}
	 }
?>	
<script language="javascript" type="text/javascript">
function valforms(frm)
{
	var atleastone = false;
	fieldRequired = Array('cat_name');
	fieldDescription = Array('Category Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('catgroup_order');
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
				
				/*if(cnt>1)
				{
					var def_ok = false;
					for(i=0;i<obj.options.length;i++)
					{
						if(obj.options[i].selected)
						{
							if(obj.options[i].value==document.getElementById('default_catgroup_id').value)
								def_ok = true;
						}
					}
					if (def_ok==false)
					{
						alert('Default Product Category Group not in selected Product Category Group list');
						return false;
					}
				}*/
				if(frm.category_showimage.checked == false && 
				   frm.category_showname.checked == false &&
				   frm.category_showshortdesc.checked == false ) 
					{
						  alert('Please select atleast one field for Subcategories to Display ')	   
						  return false;
					}
			if(frm.product_showimage.checked == false && 
				   frm.product_showtitle.checked == false &&
				   frm.product_showshortdescription.checked == false &&
				   frm.product_showprice.checked == false &&
				   frm.product_showrating.checked == false &&
				   frm.product_showrating.checked == false &&
				   frm.product_showbonuspoints.checked == false)
	    		{
					  alert('Please select atleast one field for Products to Display ')	   
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

/* Google base category list change starts here */
function call_ajax_setgooglebase()
{
	var fpurpose = 'list_googlebase_cate';
	document.getElementById('set_googlebase').style.display	=	'none';
	document.getElementById('googlebase_cat_id').innerHTML	=	'<img src="/console/images/loading.gif" width="31" height="31" alt="Loading" align="middle" />';
	Handlewith_Ajax('services/product_category.php','fpurpose='+fpurpose);
}
function ajax_return_contents() 
{
	var ret_val = '';
	var disp 	= 'no';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			//alert('status 200');
			ret_val 	= req.responseText;
			targetobj 	= eval("document.getElementById('googlebase_cat_id')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			/* Decide the display of action buttons*/
		}	
	}
}
/* Google base category list change starts here */
<?php
	if($ecom_gridenable > 0)
	{
?>
function showGroupList()
{
	if(document.getElementById('enable_grid_display').checked == true)
	{
		document.getElementById('show_group_list').style.display = 'block';
	}
	else
	{
		document.getElementById('show_group_list').style.display = 'none';
	}
}
<?php
	}
?>
</script>

<form name='frmAddProductCategory' action='home.php?request=prod_cat' method="post" onsubmit="return valforms(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=prod_cat&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&start=<?php echo $_REQUEST['start']?>&pg=<?php echo $_REQUEST['pg']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>">List Product Categories</a><span> Add Product Category</span></div></td>
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
          <td colspan="4" align="center" valign="middle">
		  <div class="editarea_div">
		  <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td width="16%" align="left" valign="middle" class="tdcolorgray" >Category name <span class="redtext">*</span> </td>
          <td width="33%" align="left" valign="middle" class="tdcolorgray"><input name="cat_name" type="text" class="input" size="25" value="<?php echo $_REQUEST['cat_name']?>" maxlength="150" /></td>
          <td width="17%" align="left" valign="middle" class="tdcolorgray">Hide Category </td>
          <td width="34%" align="left" valign="middle" class="tdcolorgray"><input type="radio" name="cat_hide" value="1" <?php if($_REQUEST['cat_hide']==1) echo "checked"?> />
            Yes
              <input name="cat_hide" type="radio" value="0" <?php if($_REQUEST['cat_hide']==0) echo "checked"?> />
          No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_CAT_PROD_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
         <tr>
           <td align="left" valign="top" class="tdcolorgray" >Parent Category</td>
           <td align="left" valign="top" class="tdcolorgray">
		   <?php
		   	/*$parent_array = array(0=>'Root Level');
			$sql_parent = "SELECT category_id,category_name FROM product_categories WHERE sites_site_id=$ecom_siteid ORDER BY category_name";
			$ret_parent = $db->query($sql_parent);
			if ($db->num_rows($ret_parent))
			{
				while ($row_parent = $db->fetch_array($ret_parent))
				{
					$grpid					= $row_parent['category_id'];
					$parent_array[$grpid] 	= stripslashes($row_parent['category_name']);
				}	
			}*/
			$parent_array = generate_category_tree(0,0,false,false);
			echo generateselectbox('parent_id',$parent_array,$_REQUEST['parent_id']);
		  ?>
             <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_PARENT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
           <td colspan="2" align="left" valign="top" class="tdcolorgray"><!--Show any product Image -->
           <input name="chk_category_turnoff_treemenu" type="checkbox" id="chk_category_turnoff_treemenu" value="1" <?php echo ($row_set['category_turnoff_treemenu']==1)?'checked="checked"':''?> />
           Turn Off Tree menu in Category Details page </td>
         </tr>
		 <?php
		 $sql_them = "SELECT allow_special_category_details 
									FROM 
										themes 
									WHERE 
										theme_id = $ecom_themeid  
									LIMIT 
										1";
			$ret_them = $db->query($sql_them);
			if ($db->num_rows($ret_them))
			{
				$row_them = $db->fetch_array($ret_them);
				if ($row_them['allow_special_category_details']==1)
					$spcat = true;
				else
					$spcat = false;
			}				

  ?>
         <tr>
           <td align="left" valign="top" class="tdcolorgray" ><?php 
		if($spcat==true)
		{
	?>
Category Details Display Type
  <?php
		}
	?></td>
           <td align="left" valign="top" class="tdcolorgray"><?php 
		if($spcat==true)
		{
	?>
             <select name="special_detailspage_required">
               <option value="0">Normal</option>
               <option value="1">Special</option>
             </select>
             <?php
		}
	?></td>
           <td colspan="2" align="left" valign="top" class="tdcolorgray">
             <input name="chk_category_turnoff_pdf" type="checkbox" id="chk_category_turnoff_pdf" value="1" <?php echo ($row_set['category_turnoff_pdf']==1)?'checked="checked"':''?>/>
Turn Off PDF in Category Details page </td>
         </tr>
         <tr>
			<?php
			if($ecom_site_mobile_api==1)
			{
			?>
           <td align="left" valign="top" class="tdcolorgray" >Show In Mobile Application</td>
           <td align="left" valign="top" class="tdcolorgray"><input name="in_mobile_api_sites" type="checkbox" id="in_mobile_api_sites" value="1" <?php echo ($_REQUEST['in_mobile_api_sites']==1)?'checked="checked"':''?>/>
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_CAT_MOB_API')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>           </td>
           <?php 
           }
           else
           {
			?>
			<td align="left" class="tdcolorgray">&nbsp;</td>
			<td align="left" class="tdcolorgray">&nbsp;</td> 
			<?php   
		   }
           ?>
           <td colspan="2" align="left" valign="top" class="tdcolorgray"><input name="category_turnoff_mainimage" type="checkbox" id="category_turnoff_mainimage" value="1" <?php echo ($row_set['category_turnoff_mainimage']==1)?'checked="checked"':''?>/>
Turn Off &quot;Main Image &quot; in Category Details page </td>
         </tr>
           <?php
			if($ecom_site_mobile_api==1)
			{
			?>
          <tr>
			 <td align="left" valign="top" class="tdcolorgray">Parent For Mobile Application</td>
    <td align="left" valign="top" class="tdcolorgray"><?php
    $mobile_parent_array = generate_mobile_api_category_tree(0,0,false,false);
			echo generateselectbox('mobile_api_parent_id',$mobile_parent_array,$row_category['parent_id']);
		  ?>
    <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('MOB_EDIT_PROD_CAT_PARENT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
   
    <td colspan="2" align="left" valign="top" class="tdcolorgray">&nbsp; </td>
  </tr>
   <?php
			}
			
    ?>
         <tr>
           <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
           <td align="left" valign="top" class="tdcolorgray"><input name="category_turnoff_noproducts" type="checkbox" id="category_turnoff_noproducts" value="1" <?php echo ($row_set['category_turnoff_noproducts']==1)?'checked="checked"':''?>/>
Hide &quot;No Products&quot; message in Category Details Page </td>
           <td colspan="2" align="left" valign="top" class="tdcolorgray"><input name="category_turnoff_moreimages" type="checkbox" id="category_turnoff_moreimages" value="1" <?php echo ($row_set['category_turnoff_moreimages']==1)?'checked="checked"':''?>/>
Turn Off &quot;More Images&quot; in Category Details page </td>
         </tr>
         
		 <tr>
           <td colspan="4" align="left" valign="top" class="seperationtd"><strong>Category Menu Selection</strong></td>
         </tr>
         <tr>
          <td align="left" valign="top" class="tdcolorgray" >Category Menu  </td>
          <td colspan="3" align="left" valign="top" class="tdcolorgray">
		  <?php
		  	$group_arr 		= array();
			// Get the list of all category groups for this site
			$sql_groups = "SELECT catgroup_id,catgroup_name FROM product_categorygroup WHERE sites_site_id=$ecom_siteid ORDER BY catgroup_name";
			$ret_groups = $db->query($sql_groups);
			if ($db->num_rows($ret_groups))
			{
				while ($row_groups = $db->fetch_array($ret_groups))
				{
					$grpid	= $row_groups['catgroup_id'];
					$group_arr[$grpid] = stripslashes($row_groups['catgroup_name']);
				}	
			}
			echo generateselectbox('group_id[]',$group_arr,$_REQUEST['group_id'],'','',5);
		  ?>		  
           <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_CG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>		 </td>
        </tr>
        		 
		 <tr>
           <td colspan="4" align="left" valign="top" class="seperationtd"><strong>Descriptions</strong></td>
         </tr>
         <tr>
           <td align="left" valign="top" class="tdcolorgray" >Short Description </td>
           <td colspan="3" align="left" valign="top" class="tdcolorgray"><input name="short_desc" type="text" size="100" value="<?php echo $_REQUEST['short_desc']?>" maxlength="400" /></td>
         </tr>
         <tr>
		    <td align="left" valign="top" class="tdcolorgray" >Long Description </td>
		    <td colspan="3" align="left" valign="top" class="tdcolorgray">
			<?php
				$editor_elements = "long_desc,bottom_desc";
				include_once("js/tinymce.php");				
				/*$editor 			= new FCKeditor('long_desc') ;
				$editor->BasePath 	= '/console/js/FCKeditor/';
				$editor->Width 		= '650';
				$editor->Height 	= '300';
				$editor->ToolbarSet = 'BshopWithImages';
				$editor->Value 		= stripslashes($_REQUEST['long_desc']);
				$editor->Create() ;*/
		       
			   ?>			
			   <textarea style="height:300px; width:500px" id="long_desc" name="long_desc"><?=stripslashes($_REQUEST['long_desc'])?></textarea>			   </td>
		  </tr>
         <tr>
           <td align="left" valign="top" class="tdcolorgray" >Bottom Description (SEO Purpose)</td>
           <td colspan="3" align="left" valign="top" class="tdcolorgray">
		    <textarea style="height:300px; width:500px" id="bottom_desc" name="bottom_desc"><?=stripslashes($_REQUEST['category_bottom_description'])?></textarea>		   </td>
         </tr>
         <tr>
           <td colspan="4" align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
         </tr>
         <tr>
           <td colspan="4" align="left" valign="top" class="seperationtd" ><strong>Display Settings for Subcategories & Products of this category</strong> </td>
         </tr>
         <tr>
           <td align="left" valign="top" class="tdcolorgray" >Subcategory List</td>
           <td align="left" valign="top" class="tdcolorgray" >
		   <?php 
				$subcat_list = array('Middle'=>'Show in Middle Area Only','List'=>'Show in Menu Only','Both'=>'Both in Middle and Menu');
				echo generateselectbox('category_subcatlisttype',$subcat_list,$row_set['category_subcatlisttype']);
		  ?>
             <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_SUBTYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
           <td align="left" valign="top" class="tdcolorgray" >Subcategory Display Method </td>
           <td align="left" valign="top" class="tdcolorgray" ><?php 
		    		
						//$grp_type = array('OneinRow'=>'One in a Row','ThreeinRow'=>'Three in a Row');
						echo generateselectbox('category_subcatlistmethod',$subcatlst_arr,$row_set['category_subcatlistmethod']);
			?>
             <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_DISPMETHOD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
         </tr>
         <tr>
           <td align="left" valign="top" class="tdcolorgray" >Subcategory Image Listing</td>
           <td align="left" valign="top" class="tdcolorgray" ><?PHP 
		$arr_style 	= $val_arr = array();
		$val_arr['None']  = 'None';	  
			  	$sql_style	= "SELECT image_listingstyles FROM themes WHERE theme_id=".$ecom_themeid;
				$ret_style 	= $db->query($sql_style);
				if ($db->num_rows($ret_style))
				{
					$row_style	= $db->fetch_array($ret_style);
				 	$arr_style	= explode(',',$row_style['image_listingstyles']);
					$val_arr[0]==0;
					if (count($arr_style))
					{
						foreach($arr_style as $v)
						{
							$temp_arr = explode("=>",$v);
							$val_arr[$temp_arr[0]]=trim($temp_arr[1]);	 // this array will be used for all the following image type drop down boxes
						}
					}				
				}	
				$subcateg_showimage = $row_set['subcategory_showimagetype'];
		if($subcateg_showimage=='') $subcateg_showimage = 'Medium';
		
		echo generateselectbox('subcategory_showimagetype',$val_arr,$subcateg_showimage); ?></td>
           <td colspan="2" align="left" valign="top" class="tdcolorgray">Fields to be displayed for Subcategories </td>
         </tr>
		 
         <tr>
           <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
           <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
           <td colspan="2" align="left" valign="top" class="tdcolorgray"><table width="100%" border="0" cellspacing="1" cellpadding="1">
             <tr>
               <td width="4%">&nbsp;</td>
               <td><input name="category_showname" type="checkbox" id="category_showname" value="1" <?php echo ($row_set['category_showname']==1)?'checked="checked"':''?>/>
                 Subcategory Name </td>
             </tr>
             <tr>
               <td>&nbsp;</td>
               <td><input name="category_showimage" type="checkbox" id="category_showimage" value="1" <?php echo ($row_set['category_showimage']==1)?'checked="checked"':''?>/>
Subcategory Image </td>
             </tr>
             <tr>
               <td>&nbsp;</td>
               <td><input name="category_showshortdesc" type="checkbox" id="category_showshortdesc" value="1" <?php echo ($row_set['category_showshortdesc']==1)?'checked="checked"':''?>/>
Subcategory Short description </td>
             </tr>
           </table></td>
         </tr>
         <tr>
           <td align="left" valign="top" class="tdcolorgray" >Product List</td>
           <td align="left" valign="top" class="tdcolorgray" ><?php 
				$product_list = array('menu'=>'Show in Menu','middle'=>'Show in Middle Area','both' => 'Both in Middle and Menu');
				echo generateselectbox('product_displaywhere',$product_list,$row_set['product_displaywhere']);
			?>
             <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_PTYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
           <td align="left" valign="top" class="tdcolorgray">Product Display Method </td>
           <td align="left" valign="top" class="tdcolorgray"><?php 
		    		
						//$grp_type = array('OneinRow'=>'One in a Row','ThreeinRow'=>'Three in a Row');
						echo generateselectbox('product_displaytype',$grp_type,$row_set['product_displaytype']);
			?>
             <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_DISP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
         </tr>
         <tr>
           <td align="left" valign="top" class="tdcolorgray" >Product Ordering </td>
           <td align="left" valign="top" class="tdcolorgray" ><?php
					$catgrsort_arr = array('custom'=>'Custom Ordering','product_name'=>'Product Name','price'=>'Price','product_id'=>'Date Added');
					echo generateselectbox('product_orderfield',$catgrsort_arr,$row_set['product_orderfield']); 
					echo "&nbsp;";
					$sort_ord = array('ASC'=>'Asc','DESC'=>'Desc');
					echo generateselectbox('product_orderby',$sort_ord,$row_set['product_orderby']); 
			?>
             <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_PORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
           <td align="left" valign="top" class="tdcolorgray"  colspan="2">Fields to be displayed for Products <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_DISPPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> <span class="redtext">*</span></td>
         </tr>
         <tr>
           <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
           <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
           <td align="left" valign="top" class="tdcolorgray" colspan="2" ><table width="100%" border="0" cellspacing="1" cellpadding="1">
             <tr>
               <td width="4%">&nbsp;</td>
               <td width="96%" align="left"><input name="product_showimage" type="checkbox" value="1" <?php echo ($row_set['product_showimage']==1)?'checked="checked"':''?> />
Product Image </td>
             </tr>
             <tr> 
               <td>&nbsp;</td> 
               <td align="left"><input name="product_showtitle" type="checkbox" value="1" <?php echo ($row_set['product_showtitle']==1)?'checked="checked"':''?> />
Product Title </td>
             </tr>
             <tr>
               <td>&nbsp;</td>
               <td align="left"><input name="product_showshortdescription" type="checkbox" value="1" <?php echo ($row_set['product_showshortdescription']==1)?'checked="checked"':''?> />
Product Description </td>
             </tr>
             <tr>
               <td>&nbsp;</td>
               <td align="left"><input name="product_showprice" type="checkbox" value="1" <?php echo ($row_set['product_showprice']==1)?'checked="checked"':''?> />
Product Price </td>
             </tr>
             <tr>
               <td>&nbsp;</td>
               <td align="left"><input name="product_showrating" type="checkbox" value="1" <?php echo ($row_set['product_showrating']==1)?'checked="checked"':''?> />
                 Product Rating </td>
             </tr>
              <tr>
               <td>&nbsp;</td>
               <td align="left"><input name="product_showbonuspoints" type="checkbox" value="1" <?php echo ($row_set['product_showbonuspoints']==1)?'checked="checked"':''?> />
                 Product Bonus Points </td>
             </tr>
             
             <tr>
               <td>&nbsp;</td>
               <td align="left">&nbsp;</td>
             </tr>
           </table></td>
         </tr>
         <?php
          // settings for guests in kqf
		   $sql_site = "SELECT site_custgroup_special_display_enable FROM sites WHERE site_id = $ecom_siteid LIMIT 1";
			$ret_site = $db->query($sql_site);
			if ($db->num_rows($ret_site))
			{
				$row_site = $db->fetch_array($ret_site);
			}
				if($row_site['site_custgroup_special_display_enable']==1)
			{
				?>
          <tr>
           <td align="left" valign="top" class="tdcolorgray" >Display to guests?</td>
           <td align="left" valign="top" class="tdcolorgray" ><input type="checkbox" name="display_to_guest" id="display_to_guest" value="1" checked="checked" /></td>
</tr>
         <?php
	     }
            if($ecom_gridenable > 0)
            {
                $sql_prdt_var_grp	=	"SELECT * FROM product_variables_group WHERE var_group_hide <= 0 AND sites_site_id = ".$ecom_siteid;
                $ret_prdt_var_grp	=	$db->query($sql_prdt_var_grp);
                
		  ?>
         
         <tr>
           <td align="left" valign="top" class="tdcolorgray" >Enable Grid Display?</td>
           <td align="left" valign="top" class="tdcolorgray" ><input type="checkbox" name="enable_grid_display" id="enable_grid_display" value="1" onclick="javascript: showGroupList();"  /></td>
           <td align="left" valign="top" class="tdcolorgray" colspan="2">
           <div id="show_group_list" style="display:none;">
           Select Product Variable Group &nbsp;
           <select name="product_variables_group_id" id="product_variables_group_id">
             <option value="0">--Select--</option>
         <?php
			if ($db->num_rows($ret_prdt_var_grp))
			{
				while($row_prdt_var_grp = $db->fetch_array($ret_prdt_var_grp))
				{
					echo '<option value="'.$row_prdt_var_grp['var_group_id'].'">'.$row_prdt_var_grp['var_group_name'].'</option>';
				}
			}
		 ?>
           </select>
           <br /><br />
           
           Number of columns &nbsp;
           <input type="text" name="grid_column_cnt" id="grid_column_cnt" value="12" />
           </div></td>
         </tr>
         
         <?php
			}
		 ?>
		 <tr>
			<td colspan="4" align="left" valign="top" class="seperationtd">
			Google Base Product Category Mapping <a href="#" onmouseover ="ddrivetip('<?=$showmsg_val?>')"; onMouseOut="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			</td>
		</tr>
        
        
		<tr>
			<td colspan="4" align="left" valign="top">	
				<table  width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr>
				  <td colspan="2" valign="top" class="tdcolorgray" align="center"><div id="googlebase_cat_id"></div></td>
				</tr>
				<tr>
					<td width="20%">  
						<?php
							$showmsg_val = "Google recommend to map our categories to the google product categories. This mapping will be used while generating the google base data. Use this option to do this mapping.<br>Currently the <strong>\'Google product category\'</strong> attribute only needs to be provided for products that belong to the following seven product categories in feeds that target the US, UK, Germany, and France:<br><br> <strong>1. Apparel & Accessories > Clothing<br>2. Apparel & Accessories > Shoes<br>3. Apparel & Accessories (Note that submitting this value for clothing and shoes is not acceptable.)<br>4. Media > Books<br> 5. Media > DVDs & Videos<br>6. Media > Music<br>7. Software > Video Game Software</strong><br>For products that belong to other product categories, providing this attribute is recommended. ";
						?>
					</td>
					<td width="72%">&nbsp;
					<!-- Google base category list change starts here -->
				  <input type="button" onClick="call_ajax_setgooglebase()" value="Set Google Base Product Category" id="set_googlebase" class="red" name="set_googlebase"></td>
				</tr>
				 
			  </table>			</td>
		</tr>
         <tr>
           <td colspan="4" align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
           </tr>
         </table>
		 </div>
		 </td>
		 </tr>

		<tr>
			<td colspan="4" align="center" valign="middle" class="tdcolorgray" >
				<div class="editarea_div">
					<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td align="right" valign="middle" class="tdcolorgraynormal" >
							<input type="hidden" name="catname" id="catname" value="<?=$_REQUEST['catname']?>" />
							<input type="hidden" name="parentid" id="parentid" value="<?=$_REQUEST['parentid']?>" />
							<input type="hidden" name="catgroupid" id="catgroupid" value="<?=$_REQUEST['catgroupid']?>" />
							<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
							<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
							<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
							<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
							<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
							<input type="hidden" name="fpurpose" id="fpurpose" value="save_add" />
							<input name="cat_Submit" type="submit" class="red" value="Save" />
							<input name="cat_Submit" type="submit" class="red" value="Save & Return to Edit" />
						</td>
					</tr>
					</table>
				</div>
			</td>
		</tr>
        
      </table>
</form>	  


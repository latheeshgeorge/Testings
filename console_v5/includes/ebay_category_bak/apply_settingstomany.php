<?php
	/*#################################################################
	# Script Name 	:apply_settings_many.php
	# Description 		: Page for appliying settings for multiple Categories in single step
	# Coded by 		: LH
	# Created on		: 21-Aug-2008
	# Modified by		: LH
	# Modified On		: 21-Aug-2008

	#################################################################*/

	//Define constants for this page
	$page_type = 'Categories';
	$help_msg = get_help_messages('SETTINGS_TOMANY_MAIN_MESS');
	$arr_style 	= $val_arr = array();
	$val_arr['None']  = 'None';	  
	$sql_style	= "SELECT image_listingstyles,subcategory_listingstyles FROM themes WHERE theme_id=".$ecom_themeid;
	$ret_style 	= $db->query($sql_style);
	$arr_prod_style 	= $grp_type = $subcatlstng_arr = $subcatlst_arr = array();
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
		$subcatlstng_arr	= explode(',',$row_style['subcategory_listingstyles']);
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
	$lblgrp_arr = array();	
	$sql_qry = "SELECT group_id,group_name,group_hide 
										FROM 
											product_labels_group  
										WHERE 
											sites_site_id = $ecom_siteid 
									ORDER BY 
										group_name";
	$ret_qry = $db->query($sql_qry);
	if($db->num_rows($ret_qry))
	{
		while ($row_qry = $db->fetch_array($ret_qry))
		{
			$lblgrp_arr[$row_qry['group_id']] = stripslashes($row_qry['group_name']);
		}
	}
	?>
	<script language="javascript" type="text/javascript">
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
		}
	}
}
function display_category_selector(checked_val){
	if(checked_val=='All'){
		document.getElementById("categoryselector_id").style.display="none";
		document.getElementById("categorieselector_id").style.display="none";
		
	}else if(checked_val=='Bycat'){
			document.getElementById("categoryselector_id").style.display="";
			document.getElementById("categorieselector_id").style.display="";
	}
}
function display_select(frm){
for(i=0;i<document.frm_apply_settingstomany.elements.length;i++)
	{
	if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='subcatlist_check')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("subcat_list_id").style.display='';
			}
			else
			{
			   document.getElementById("subcat_list_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='subcatmethod_check')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("subcat_meth_id").style.display='';
			}
			else
			{
			   document.getElementById("subcat_meth_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='subcatlist_imagecheck')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("subcat_imagelist_id").style.display='';
			}
			else
			{
			   document.getElementById("subcat_imagelist_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='prod_disp_method')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("prod_disp_method_id").style.display='';
			}
			else
			{
			   document.getElementById("prod_disp_method_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='prod_list')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("prod_list_id").style.display='';
			}
			else
			{
			   document.getElementById("prod_list_id").style.display='none'; 
			}	
		}
		
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='subcat_fields_check')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("subcat_fields_id").style.display='';
			}
			else
			{
			   document.getElementById("subcat_fields_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='field_disp_prod')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("field_disp_prod_id").style.display='';
			}
			else
			{
			   document.getElementById("field_disp_prod_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='cat_treemenu_check')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("treemenu_id").style.display='';
			}
			else
			{
			   document.getElementById("treemenu_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='cat_pdf_check')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("pdf_id").style.display='';
			}
			else
			{
			   document.getElementById("pdf_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='cat_moreimage_check')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("moreimg_id").style.display='';
			}
			else
			{
			   document.getElementById("moreimg_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='cat_mainimage_check')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("mainimg_id").style.display='';
			}
			else
			{
			   document.getElementById("mainimg_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='cat_anyprodimg_check')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("showprodimg_id").style.display='';
			}
			else
			{
			   document.getElementById("showprodimg_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='cat_noprod_check')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("noprod_id").style.display='';
			}
			else
			{
			   document.getElementById("noprod_id").style.display='none'; 
			}	
		}
		<?php
		if($ecom_site_mobile_api==1)
		{
		?> 
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='in_mobile_api_sites')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("in_mobile_api_sites_id").style.display='';
			}
			else
			{
			   document.getElementById("in_mobile_api_sites_id").style.display='none'; 
			}	
		}
		<?php
		}
		?>
		
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='prod_order')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("prod_order_id").style.display='';
			}
			else
			{
			   document.getElementById("prod_order_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='prodlabelgroup_check')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("prolabelgrp_id").style.display='';
			}
			else
			{
			   document.getElementById("prolabelgrp_id").style.display='none'; 
			}	
		}
	}	
}
function valforms(frm){
var atleastone 		= false;
	if(frm.field_disp_prod.checked==true )
	{
		 if(frm.product_showimage.checked == false && 
				   frm.product_showtitle.checked == false &&
				   frm.product_showshortdescription.checked == false &&
				   frm.product_showprice.checked == false &&
				   frm.product_showrating.checked == false &&
				   frm.product_showbonuspoints.checked == false) 
	    		{
					  alert('Please Check any of Product Items to Display ')	  ; 
					  return false;
				}
	}	
	if (frm.subcat_fields_check.checked==true)
	{
				 if(frm.category_showname.checked == false && 
				   frm.category_showimage.checked == false &&
				   frm.category_showshortdesc.checked == false ) 
	    		{
					  alert('Please select the fields to be displayed for subcategories');   
					  return false;
				}
	}
	if(frm.cat_treemenu_check.checked==true )
	{		
		if (frm.chk_category_turnoff_treemenu[0].checked==false  && frm.chk_category_turnoff_treemenu[1].checked==false)
		{
			alert('Please select option for Category Tree menu');
			return false;
		}
	}
	if(frm.cat_pdf_check.checked==true )
	{		
		if (frm.chk_category_turnoff_pdf[0].checked==false  && frm.chk_category_turnoff_pdf[1].checked==false)
		{
			alert('Please select option for Category PDF');
			return false;
		}
	}
	if(frm.cat_moreimage_check.checked==true )
	{		
		if (frm.category_turnoff_moreimages[0].checked==false  && frm.category_turnoff_moreimages[1].checked==false)
		{
			alert('Please select option for Category More Images');
			return false;
		}
	}
	if(frm.cat_mainimage_check.checked==true )
	{		
		if (frm.category_turnoff_mainimage[0].checked==false  && frm.category_turnoff_mainimage[1].checked==false)
		{
			alert('Please select option for Category Main Image');
			return false;
		}
	}
	if(frm.cat_anyprodimg_check.checked==true )
	{		
		if (frm.category_showimageofproduct[0].checked==false  && frm.category_showimageofproduct[1].checked==false)
		{
			alert('Please select option for any Product Image for Category');
			return false;
		}
	}
	if(frm.cat_noprod_check.checked==true )
	{		
		if (frm.category_turnoff_noproducts[0].checked==false  && frm.category_turnoff_noproducts[1].checked==false)
		{
			alert('Please select option for No Products message in Category details page');
			return false;
		}
	}
	
	<?php
	if($ecom_site_mobile_api==1)
	{
	?> 	
	if(frm.in_mobile_api_sites.checked==true )
	{		
		if (frm.enable_in_mobile_api_sites[0].checked==false  && frm.enable_in_mobile_api_sites[1].checked==false)
		{
			alert('Please select option for Show in Mobile Application');
			return false;
		}
	}
	
	if(frm.subcatlist_check.checked==false && frm.subcatmethod_check.checked==false && frm.subcat_fields_check.checked==false && frm.subcatlist_imagecheck.checked==false && frm.prod_disp_method.checked==false && frm.prod_list.checked==false && frm.field_disp_prod.checked==false && frm.cat_treemenu_check.checked==false && frm.cat_pdf_check.checked==false && frm.cat_moreimage_check.checked==false && frm.cat_mainimage_check.checked==false && frm.cat_anyprodimg_check.checked==false && frm.cat_noprod_check.checked==false  && frm.in_mobile_api_sites.checked==false && frm.prod_order.checked==false && frm.prodlabelgroup_check.checked==false)
	{
		alert("Please select at least One Checkbox");
		return false;
	}
	else if(frm.select_categories[0].checked== false && frm.select_categories[1].checked == false){
		alert("Please choose the categories to which the settings to be applied");
		return false;
	}
	
	<?php
	}
	else
	{
	?>
	
if(frm.subcatlist_check.checked==false && frm.subcatmethod_check.checked==false && frm.subcat_fields_check.checked==false && frm.subcatlist_imagecheck.checked==false && frm.prod_disp_method.checked==false && frm.prod_list.checked==false && frm.field_disp_prod.checked==false && frm.cat_treemenu_check.checked==false && frm.cat_pdf_check.checked==false && frm.cat_moreimage_check.checked==false && frm.cat_mainimage_check.checked==false && frm.cat_anyprodimg_check.checked==false && frm.cat_noprod_check.checked==false && frm.prod_order.checked==false && frm.prodlabelgroup_check.checked==false)
{
		alert("Please select at least One Checkbox");
		return false;
}
	else if(frm.select_categories[0].checked== false && frm.select_categories[1].checked == false){
		alert("Please choose the categories to which the settings to be applied");
		return false;
	}
	<?php
	}
	?>
	if(frm.select_categories[0].checked==true){
		if(confirm("Are you sure You want Set the Selected changes for ALL the categories")){
			return true;
		}else{
			return false;
		}
	}else if(frm.select_categories[1].checked==true){
	obj = document.getElementById('settings_categoryid[]');
			if(obj.options.length==0)
			{
				alert('Category is required');
				return false;
			}
			else
			{
				for(i=0;i<obj.options.length;i++)
				{
					if(obj.options[i].selected)
					{
						atleastone = true;
					}
				}
			}
		if (atleastone==false)
				{
					alert('Please select the category');
					return false;
				}		
		if(confirm("Are you sure You want Set the Selected changes for the Selected categories")){
			return true;
		}else{
			return false;
		}
	} 
}
	</script>	
	<form name='frm_apply_settingstomany' action='home.php?request=prod_cat' method="post" onsubmit="return valforms(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=prod_cat&catname=<?=$_REQUEST['catname']?>&amp;parentid=<?=$_REQUEST['parentid']?>&amp;catgroupid=<?=$_REQUEST['catgroupid']?>&amp;start=<?php echo $_REQUEST['start']?>&amp;pg=<?php echo $_REQUEST['pg']?>&amp;records_per_page=<?php echo $_REQUEST['records_per_page']?>&sort_by=<?php echo $sort_by?>&search_in_mobile_application=<?php echo $_REQUEST['search_in_mobile_application'];?>&sort_order=<?php echo $sort_order?>">List categories</a><span> Set Options for multiple categories</span></div></td>
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
          			<td colspan="4" align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
          		</tr>
		 <?php
		 	}
		 ?> 
          <tr>
			<td colspan="4" align="center" valign="middle"> 
				<div class="listingarea_div">
				<table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td colspan="3" align="left" class="seperationtd"><table width="40%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="11%"><input type="checkbox" name="subcatlist_check" value="1" onclick="display_select(this)" <? if($_REQUEST['subcatlist_check_11']) echo "checked"; else echo '';?> /></td>
                    <td width="89%">Subcategory List&nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_SUBCAT_DISPLIST_METH')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</td>
                  </tr>
				  <tr>
                    <td width="11%"><input type="checkbox" name="subcatmethod_check" value="1" onclick="display_select(this)" <? if($_REQUEST['subcatmethod_check_11']) echo "checked"; else echo '';?> /></td>
                    <td width="89%">Subcategory Display Method&nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_SUBCAT_DISP_METH')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</td>
                  </tr>
				  <tr>
				    <td><input type="checkbox" name="subcat_fields_check" value="1" onclick="display_select(this)" <? if($_REQUEST['subcat_fields_check_11']) echo "checked"; else echo '';?> /></td>
				    <td>Fields for Subcategories <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_SUBCAT_FIELDS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			      </tr>
				  <tr>
                    <td width="11%"><input type="checkbox" name="subcatlist_imagecheck" value="1" onclick="display_select(this)" <? if($_REQUEST['subcatlist_imagecheck_11']) echo "checked"; else echo '';?> /></td>
                    <td width="89%">Subcategory Image Listing&nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_SUBCAT_IMAGEDISP_METH')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</td>
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="prod_disp_method" value="1" onclick="display_select(this)"  <? if($_REQUEST['prod_disp_method_11']) echo "checked"; else echo '';?>/></td>
                    <td>  Display of Products in Category &nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_PROD_DISP_METH')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</td>
                  </tr>
				  <tr>
                    <td><input type="checkbox" name="prod_list" value="1" onclick="display_select(this)" <? if($_REQUEST['prod_list_11']) echo "checked"; else echo '';?> /></td>
                    <td>  Listing of Products in Category &nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_PROD_LIST_METH')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="prod_order" value="1" onclick="display_select(this)" <? if($_REQUEST['prod_order_11']) echo "checked"; else echo '';?> /></td>
                    <td>Ordering of Products in Category &nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_PROD_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="field_disp_prod" value="1" onclick="display_select(this)" <? if($_REQUEST['field_disp_prod_11']) echo "checked"; else echo '';?>/></td>
                    <td> Fields for Products in Category &nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_FIELDS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="cat_treemenu_check" value="1" onclick="display_select(this)" <? if($_REQUEST['cat_treemenu_check_11']) echo "checked"; else echo '';?>/></td>
                    <td>Tree menu in Category Details Page <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_TREE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="cat_pdf_check" value="1" onclick="display_select(this)" <? if($_REQUEST['cat_pdf_check_11']) echo "checked"; else echo '';?>/></td>
                    <td> PDF in Category Details Page <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_PDF')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="cat_mainimage_check" value="1" onclick="display_select(this)" <? if($_REQUEST['cat_moreimage_check_11']) echo "checked"; else echo '';?>/></td>
                    <td> Main Images in Category Details Page <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_CAT_SHOW_NO_MAIN_IMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                  </tr>
				  <tr>
                    <td><input type="checkbox" name="cat_moreimage_check" value="1" onclick="display_select(this)" <? if($_REQUEST['cat_moreimage_check_11']) echo "checked"; else echo '';?>/></td>
                    <td> More Images in Category Details Page <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_MOREIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="cat_anyprodimg_check" value="1" onclick="display_select(this)" <? if($_REQUEST['cat_anyprodimg_check_11']) echo "checked"; else echo '';?>/></td>
                    <td>Show any Product Image for Category <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_ANYPRODIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="cat_noprod_check" value="1" onclick="display_select(this)" <? if($_REQUEST['cat_noprod_check_11']) echo "checked"; else echo '';?>/></td>
                    <td>Hide &quot;No Products&quot; message in Category Details Page <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_NOPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                  </tr>
				  <?php
					if (count($lblgrp_arr))
					{
				  ?>
					  <tr>
						<td><input type="checkbox" name="prodlabelgroup_check" value="1" onclick="display_select(this)" <? if($_REQUEST['prodlabelgroup_check_11']) echo "checked"; else echo '';?>/></td>
						<td>Assign Product Label Groups to Categories <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('PROD_LBL_GROUP_ASSIGN')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
					  </tr>
				  <?php
				  	}
				  ?>
				  <?php
					if($ecom_site_mobile_api==1)
					{
				  ?>
					  <tr>
						<td><input type="checkbox" name="in_mobile_api_sites" value="1" onclick="display_select(this)" <? if($_REQUEST['in_mobile_api_sites_11']) echo "checked"; else echo '';?>/></td>
						<td>Show in Mobile Application  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_CAT_MOB_API')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
					  </tr>
				  <?php
				  	}
				  ?>
				  
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
              <tr>
		</tr>
              <tr style=" display:none;" id="subcat_list_id"><td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
               		<td width="27%"  align="left" class="tdcolorgray">Subcategory List                  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_SUBTYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                    <td width="73%"  align="left" class="tdcolorgray"><?php 
				$subcat_list = array('Middle'=>'Show in Middle Area','List'=>'Show below selected category','Both'=>'Both in Middle and Below Selected Category');
				echo generateselectbox('category_subcatlisttype',$subcat_list,$row_category['category_subcatlisttype']);
		  ?></td>
			  </tr>
			  </table></td></tr>
			  <tr style=" display:none;" id="subcat_meth_id"><td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0">
			  <tr>
			    <td width="27%" align="left" valign="top" class="tdcolorgray" >Subcategory Display Method <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_SUBTYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			    <td width="73%" align="left" valign="top" class="tdcolorgray" ><?PHP 
		echo generateselectbox('category_subcatlistmethod',$subcatlst_arr,$subcateg_showimage); ?></td>
			    </tr>
			  </table></td></tr>
			 <tr style=" display:none;" id="subcat_fields_id"><td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
               		<td  align="left" class="tdcolorgray"><strong> </strong> Fields to be displayed for Subcategories<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_DISPPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
                      <table width="100%" border="0" cellspacing="1" cellpadding="1">
                        <tr>
                          <td width="14%">&nbsp;</td>
          <td width="96%" align="left"><input name="category_showname" type="checkbox" value="1" <?php echo ($row_category['product_showimage']==1)?'checked="checked"':''?> />
            Subcategory Name </td>
        </tr>
                        <tr>
                          <td>&nbsp;</td>
          <td align="left"><input name="category_showimage" type="checkbox" value="1" <?php echo ($row_category['product_showtitle']==1)?'checked="checked"':''?> />
            Subcategory Image </td>
        </tr>
                        <tr>
                          <td>&nbsp;</td>
          <td align="left"><input name="category_showshortdesc" type="checkbox" value="1" <?php echo ($row_category['category_showshortdesc']==1)?'checked="checked"':''?> />
            Subcategory Short Description </td>
        </tr>
		</table></td>
         <td width="36%" align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
			  </tr>
			  </table></td></tr>
			  <tr style=" display:none;" id="subcat_imagelist_id"><td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0">
			  <tr>
		<td align="left" valign="top" class="tdcolorgray" width="27%" >Subcategory Image&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_SUBCATEGLISTING_TYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		<td align="left" valign="top" class="tdcolorgray" width="73%" ><?PHP 
		echo generateselectbox('subcategory_showimagetype',$val_arr,$subcateg_showimage); ?>		</td>
		</tr>
			  </table></td></tr>
               <tr style=" display:none;" id="prod_disp_method_id"><td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
               		<td width="27%"  align="left" class="tdcolorgray">Product Display Method                  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_DISP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
               <td width="73%"  align="left" class="tdcolorgray"><?php 
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
						//$grp_type = array('OneinRow'=>'One in a Row','ThreeinRow'=>'Three in a Row');
						echo generateselectbox('product_displaytype',$grp_type,$row_category['product_displaytype']);
			?></td>
			  </tr>
			  </table></td>
			  </tr>
			  <tr style=" display:none;" id="prod_list_id"><td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
               		<td width="27%"  align="left" class="tdcolorgray">Product Listing in categories                 <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_PTYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
               <td width="73%"  align="left" class="tdcolorgray"><?php 
				$product_list = array('menu'=>'Show in Menu','middle'=>'Show in Middle Area','both' => 'Both in Middle and Menu');
				echo generateselectbox('product_displaywhere',$product_list,$row_category['product_displaywhere']);
			?></td>
			  </tr>
			  </table></td></tr>
			  <tr style=" display:none;" id="prod_order_id"><td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
               		<td width="27%"  align="left" class="tdcolorgray">Product Ordering in categories                 <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_PORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
               <td width="73%"  align="left" class="tdcolorgray">
			   <?php
					$catgrsort_arr = array('custom'=>'Custom Ordering','product_name'=>'Product Name','price'=>'Price','product_id'=>'Date Added');
					echo generateselectbox('product_orderfield',$catgrsort_arr,$row_category['product_orderfield']); 
					echo "&nbsp;";
					$sort_ord = array('ASC'=>'Asc','DESC'=>'Desc');
					echo generateselectbox('product_orderby',$sort_ord,$row_category['product_orderby']); 
				?>
			</td>
			  </tr>
			  </table></td></tr>
              <tr style=" display:none;" id="field_disp_prod_id"><td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
               		<td  align="left" class="tdcolorgray"><strong> </strong> Fields to be displayed for Products<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_DISPPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
                      <table width="100%" border="0" cellspacing="1" cellpadding="1">
                        <tr>
                          <td width="14%">&nbsp;</td>
          <td width="96%" align="left"><input name="product_showimage" type="checkbox" value="1" <?php echo ($row_category['product_showimage']==1)?'checked="checked"':''?> />
            Product Image </td>
        </tr>
                        <tr>
                          <td>&nbsp;</td>
          <td align="left"><input name="product_showtitle" type="checkbox" value="1" <?php echo ($row_category['product_showtitle']==1)?'checked="checked"':''?> />
            Product Title </td>
        </tr>
                        <tr>
                          <td>&nbsp;</td>
          <td align="left"><input name="product_showshortdescription" type="checkbox" value="1" <?php echo ($row_category['product_showshortdescription']==1)?'checked="checked"':''?> />
            Product Description </td>
        </tr>
                        <tr>
                          <td>&nbsp;</td>
          <td align="left"><input name="product_showprice" type="checkbox" value="1" <?php echo ($row_category['product_showprice']==1)?'checked="checked"':''?> />
            Product Price </td>
        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td align="left"><input name="product_showrating" type="checkbox" value="1" <?php echo ($row_category['product_showrating']==1)?'checked="checked"':''?> />
Product Rating </td>
                        </tr>
                         <tr>
                          <td>&nbsp;</td>
                          <td align="left"><input name="product_showbonuspoints" type="checkbox" value="1" <?php echo ($row_category['product_showbonuspoints']==1)?'checked="checked"':''?> />
Product Bonus Points </td>
                        </tr>
                                      </table></td>
               
                 <td width="36%" align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
			  </tr>
			  </table></td></tr>
               <tr style=" display:none;" id="treemenu_id"><td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0">
			    <tr>
			      <td colspan="2"  align="left" class="tdcolorgray">Treemenu in Category Details Page <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_TREE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		        </tr>
			    <tr>
               		<td width="11%"  align="left" class="tdcolorgray"> <input name="chk_category_turnoff_treemenu"  type="radio" value="0"/> Turn On
           		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_PTYPE')?>')"; onmouseout="hideddrivetip()"></a></td>
                    <td width="89%"  align="left" class="tdcolorgray"><input name="chk_category_turnoff_treemenu" type="radio"  value="1"/> Turn Off
                      
                  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_PTYPE')?>')"; onmouseout="hideddrivetip()"></a></td>
			  </tr>
			  </table></td></tr>
			  <tr style=" display:none;" id="pdf_id"><td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0">
			    <tr>
			      <td colspan="2"  align="left" class="tdcolorgray">PDF in Category Details Page <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_PDF')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		        </tr>
			    <tr>
               		<td width="11%"  align="left" class="tdcolorgray"> <input name="chk_category_turnoff_pdf" type="radio" value="0"/> Turn On
           		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_PTYPE')?>')"; onmouseout="hideddrivetip()"></a></td>
                    <td width="89%"  align="left" class="tdcolorgray"><input name="chk_category_turnoff_pdf" type="radio"  value="1"/> Turn Off
                      
                  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_PTYPE')?>')"; onmouseout="hideddrivetip()"></a></td>
			  </tr>
			  </table></td></tr>
			  <tr style=" display:none;" id="mainimg_id"><td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0">
			    <tr>
			      <td colspan="2"  align="left" class="tdcolorgray">Main Image in Category Details Page <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_SHOW_NO_MAIN_IMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		        </tr>
			    <tr>
               		<td width="11%"  align="left" class="tdcolorgray"> <input name="category_turnoff_mainimage"  type="radio" value="0"/> Turn On
           		  </td>
                    <td width="89%"  align="left" class="tdcolorgray"><input name="category_turnoff_mainimage" type="radio" value="1"/> Turn Off
                      
                  </td>
			  </tr>
			  </table></td></tr>
			  <tr style=" display:none;" id="moreimg_id"><td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0">
			    <tr>
			      <td colspan="2"  align="left" class="tdcolorgray">More Images in Category Details Page <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_MOREIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		        </tr>
			    <tr>
               		<td width="11%"  align="left" class="tdcolorgray"> <input name="category_turnoff_moreimages"  type="radio" value="0"/> Turn On
           		  </td>
                    <td width="89%"  align="left" class="tdcolorgray"><input name="category_turnoff_moreimages" type="radio" value="1"/> Turn Off
                    </td>
			  </tr>
			  </table></td></tr>
			  <tr style=" display:none;" id="showprodimg_id"><td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0">
			    <tr>
			      <td colspan="3"  align="left" class="tdcolorgray">Show any product image for Categories <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_ANYPRODIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		        </tr>
			    <tr>
               		<td width="11%"  align="left" class="tdcolorgray"> <input name="category_showimageofproduct"  type="radio" value="1" onclick="if(document.getElementById('ignore_assigned')){ document.getElementById('ignore_assigned').style.display='';}"/> Turn On
           		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_PTYPE')?>')"; onmouseout="hideddrivetip()"></a></td>
                    <td width="20%"  align="left" class="tdcolorgray"><input name="category_showimageofproduct" type="radio" value="0"  onclick="if(document.getElementById('ignore_assigned')){ document.getElementById('ignore_assigned').style.display='none';}"/> Turn Off
                      
                  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_PTYPE')?>')"; onmouseout="hideddrivetip()"></a></td>
			        <td width="69%"  align="left" class="tdcolorgray"><div id="ignore_assigned" style="display:none"><input name="chk_ignore_assigned_img_cat" type="checkbox" id="chk_ignore_assigned_img_cat" value="1" /> 
		            Ignore categories for which images are already assigned </div></td>
			    </tr>
			  </table></td></tr>
			  <tr style=" display:none;" id="noprod_id"><td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0">
			    <tr>
			      <td colspan="2"  align="left" class="tdcolorgray">Hide "No Products" Message in Category Details Page <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_NOPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		        </tr>
			    <tr>
               		<td width="11%"  align="left" class="tdcolorgray"> <input name="category_turnoff_noproducts"  type="radio" value="1"/> 
               		Yes
           		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_PTYPE')?>')"; onmouseout="hideddrivetip()"></a></td>
                    <td width="89%"  align="left" class="tdcolorgray"><input name="category_turnoff_noproducts" type="radio" value="0"/> 
                    No
                      
                  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_PTYPE')?>')"; onmouseout="hideddrivetip()"></a></td>
			  </tr>
			  </table></td></tr>
			  <tr style=" display:none;" id="prolabelgrp_id"><td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0">
			    <tr>
			      <td align="left" class="tdcolorgray">Select the product label groups to be assigned to categories</td>
		        </tr>
			    <tr>
               		<td align="center" class="tdcolorgray" width="40%">
					<?php
						
						echo generateselectbox('settings_productlabel_group[]',$lblgrp_arr,'','','',15);
					?>
					
					</td>
					<td align="center" class="tdcolorgray">&nbsp;</td>
			  </tr>
			  </table></td></tr>
			  		  
			  <tr style="display:none;" id="in_mobile_api_sites_id"><td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0">
			    <tr>
			      <td align="left" class="tdcolorgray">Show in Mobile Application?   </td>
		        </tr>
			    <tr>
               		<td align="center" class="tdcolorgray" width="40%">
					
					
					<span class="">
			     <input type="radio" name="enable_in_mobile_api_sites" value="1" />
                 <label>Yes </label>
                 <input type="radio" name="enable_in_mobile_api_sites" value="0" />
                 <label>No </label>
               </span>                   
					
					</td>
					<td align="center" class="tdcolorgray">&nbsp;</td>
			  </tr>
			  </table></td></tr>
			  
			  
              <tr>
		<td colspan="3" align="left" class="seperationtd">Select categories for which  you want to set the above settings <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_CATEGORIES')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		</tr>
              <tr>
                <td colspan="3" align="left" class="tdcolorgray"><input type="radio" name="select_categories" value="All" onclick="display_category_selector(this.value)"/>
Apply to all categories
  <input type="radio" name="select_categories" value="Bycat"  onclick="display_category_selector(this.value)"/> 
                Select categories </td>
              </tr>
            
			  
           
		      <tr id="categoryselector_id" style="display:none">
		        <td align="left" valign="middle" class="tdcolorgraynormal">&nbsp;</td>
                <td colspan="2" align="left" valign="top" class="tdcolorgraynormal"><div style="float:left; width:100px; " >Select Category</div>
                    <div style="float:left;"><?php
					
			  	$cat_arr = generate_category_tree(0,0,false,true,true);
				if(is_array($cat_arr))
				{	
					echo generateselectbox('settings_categoryid[]',$cat_arr,'','','',15);
				}
			  ?>
               
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_TOMANY_SELECT_CATEGORIES_CAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></div></td>
    </tr>
              <tr>
                <td colspan="3" align="left" valign="middle" class="tdcolorgraynormal">&nbsp;</td>
              </tr>
        <tr id="categorieselector_id" style="display:" >
          <td colspan="3" align="left" valign="middle" class="tdcolorgraynormal"><div id="listcat_div"></div></td>
        </tr>
		
        </table></div>
		</td>
		</tr>
		<tr>
			<td colspan="4" align="right" valign="middle"> 
				<div class="listingarea_div">
					<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td align="right" valign="middle" class="tdcolorgraynormal" ><input name="Submit" type="submit" class="red" value="Set values" /></td>
					</tr>
					</table>
				</div>
			</td>
		</tr>
		 
        <tr>
          <td width="16%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray">

		<input type="hidden" name="catname" id="catname" value="<?=$_REQUEST['catname']?>" />
        <input type="hidden" name="parentid" id="parentid" value="<?=$_REQUEST['parentid']?>" />
        <input type="hidden" name="catgroupid" id="catgroupid" value="<?=$_REQUEST['catgroupid']?>" />
        <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
        <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
        <input type="hidden" name="search_in_mobile_application" id="search_in_mobile_application" value="<?=$_REQUEST['search_in_mobile_application']?>" />
        <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
        <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
        <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
        <input type="hidden" name="fpurpose" id="fpurpose" value="save_edit" />
		<input type="hidden" name="checkbox[0]" id="checkbox[0]" value="<?php echo $edit_id?>" />
		<input type="hidden" name="src_page" id="src_page" value="prodcat" />
		<input type="hidden" name="src_id" id="src_id" value="<?php echo $edit_id?>" />
		<input type="hidden" name="retdiv_id" id="retdiv_id" value="" />
		<input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="save_settingstomany" /></td>
        </tr>
        <tr>
          <td colspan="5" align="left" valign="bottom" class="tdcolorgray">&nbsp;</td>
        </tr>
      </table>
 	
</form>

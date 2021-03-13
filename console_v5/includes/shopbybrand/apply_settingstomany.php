<?php
	/*#################################################################
	# Script Name 	: apply_settings_many.php
	# Description 	: Page for appliying settings for multiple shops in a single step
	# Coded by 		: Sny
	# Created on	: 29-Jan-2009
	# Modified by	: 
	# Modified On	: 

	#################################################################*/

	//Define constants for this page
	$page_type = 'Shops';
	$help_msg = get_help_messages('SHOP_SETTINGS_TOMANY_MAIN_MESS');
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
function display_shop_selector(checked_val){
	if(checked_val=='All'){
		document.getElementById("shopselector_id").style.display="none";
		document.getElementById("shopsselector_id").style.display="none";
		
	}else if(checked_val=='Byshop'){
			document.getElementById("shopselector_id").style.display="";
			document.getElementById("shopsselector_id").style.display="";
	}
}
function display_select(frm){
for(i=0;i<document.frm_apply_settingstomany.elements.length;i++)
	{
	if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='subshoplist_check')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("subshop_list_id").style.display='';
			}
			else
			{
			   document.getElementById("subshop_list_id").style.display='none'; 
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
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='field_disp_mainimg')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("field_disp_mainimg_id").style.display='';
			}
			else
			{
			   document.getElementById("field_disp_mainimg_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='field_disp_moreimg')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("field_disp_moreimg_id").style.display='';
			}
			else
			{
			   document.getElementById("field_disp_moreimg_id").style.display='none'; 
			}	
		}
		if (document.frm_apply_settingstomany.elements[i].type =='checkbox' && document.frm_apply_settingstomany.elements[i].name=='field_disp_anyprodimg')
		{
			if (document.frm_apply_settingstomany.elements[i].checked==true)
			{
				document.getElementById("field_disp_anyprodimg_id").style.display='';
			}
			else
			{
			   document.getElementById("field_disp_anyprodimg_id").style.display='none'; 
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
					  alert('Please Check any of Product Items to Display ')	   
					  return false;
				}
	}	
	if(frm.field_disp_moreimg.checked==true )
	{		
		if (frm.shopbrand_turnoff_moreimages[0].checked==false  && frm.shopbrand_turnoff_moreimages[1].checked==false)
		{
			alert('Please select option for Shop More Images');
			return false;
		}
	}
	if(frm.field_disp_mainimg.checked==true )
	{		
		if (frm.shopbrand_turnoff_mainimage[0].checked==false  && frm.shopbrand_turnoff_mainimage[1].checked==false)
		{
			alert('Please select option for Shop Main Image');
			return false;
		}
	}
	if(frm.field_disp_anyprodimg.checked==true )
	{		
		if (frm.shopbrand_showimageofproduct[0].checked==false  && frm.shopbrand_showimageofproduct[1].checked==false)
		{
			alert('Please select option for any Product Image for Shop');
			return false;
		}
	}			
	if(frm.subshoplist_check.checked==false && frm.prod_disp_method.checked==false && frm.field_disp_prod.checked==false && frm.field_disp_mainimg.checked==false && frm.field_disp_moreimg.checked==false && frm.field_disp_anyprodimg.checked==false) {
		alert("Select At least One Checkbox");
		return false
	}
	else if(frm.select_shops[0].checked== false && frm.select_shops[1].checked == false){
		alert("Select the Shop(s) to which the settings are to be applied");
		return false;
	}
	if(frm.select_shops[0].checked==true){
		if(confirm("Are you sure You want Set the Selected changes for ALL the Shops")){
			return true;
		}else{
			return false;
		}
	}else if(frm.select_shops[1].checked==true){
	obj = document.getElementById('settings_shopid[]');
			if(obj.options.length==0)
			{
				alert('Shop is required');
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
					alert('Please select the shop');
					return false;
				}		
		if(confirm("Are you sure You want Set the Selected changes for the Selected Shops")){
			return true;
		}else{
			return false;
		}
	} 
}
	</script>	
	<form name='frm_apply_settingstomany' action='home.php?request=shopbybrand' method="post" onsubmit="return valforms(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=shopbybrand&&shopname=<?=$_REQUEST['shopname']?>&show_shopgroup=<? echo $_REQUEST['show_shopgroup']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Shops </a><span>Set Options for Multiple Shops </span></div></td>
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
      <td colspan="4" class="tdcolorgray">
		  <div class="editarea_div">

      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
              <tr>
                <td colspan="3" align="left" class="seperationtd"><table width="40%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="11%"><input type="checkbox" name="subshoplist_check" value="1" onclick="display_select(this)" /></td>
                    <td width="89%">Subshop List&nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SHOP_SETTINGS_TOMANY_SELECT_SUBSHOP_DISP_METH')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</td>
                  </tr>
				  
                  <tr>
                    <td><input type="checkbox" name="prod_disp_method" value="1" onclick="display_select(this)"  /></td>
                    <td>Product Display  &nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SHOP_SETTINGS_TOMANY_SELECT_PROD_DISP_METH')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</td>
                  </tr>
				  
                  <tr>
                    <td><input type="checkbox" name="field_disp_prod" value="1" onclick="display_select(this)" /></td>
                    <td>Products Fields  &nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SHOP_SETTINGS_TOMANY_SELECT_FIELDS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                  </tr>
				   <tr>
                    <td><input type="checkbox" name="field_disp_mainimg" value="1" onclick="display_select(this)" /></td>
                    <td>Main Images in Shop Details Page   &nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_PROD_SHOP_SHOW_NO_MAIN_IMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                  </tr>
				   <tr>
                    <td><input type="checkbox" name="field_disp_moreimg" value="1" onclick="display_select(this)" /></td>
                    <td>More Images in Shop Details Page  &nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_PROD_SHOP_SHOW_NO_MORE_IMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                  </tr>
				   <tr>
                    <td><input type="checkbox" name="field_disp_anyprodimg" value="1" onclick="display_select(this)" /></td>
                    <td>Show any Product Image for Shop  &nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_PROD_SHOP_SHOW_PROD_IMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                  </tr>
                </table></td>
              </tr>
              <tr style=" display:none;" id="subshop_list_id"><td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
               		<td width="27%"  align="left" class="tdcolorgray">Subshop List Format                 <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SHOP_ADD_PROD_SHOP_SUBTYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
               <td width="73%"  align="left" class="tdcolorgray"><?php 
				$subshop_list = array('Middle'=>'Show in Middle Area','List'=>'Show below Selected Shops');
				echo generateselectbox('shopbrand_subshoplisttype',$subshop_list,0);
		  ?></td>
			  </tr>
			  </table></td></tr>
			  
               <tr style=" display:none;" id="prod_disp_method_id"><td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
               		<td width="27%"  align="left" class="tdcolorgray">Product Display Method                  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SHOP_ADD_PROD_SHOP_DISP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
               <td width="73%"  align="left" class="tdcolorgray"><?php 
			    $arr_prod_style 	= $grp_type = array();
			  	$sql_style_prod	= "SELECT product_listingstyles FROM themes WHERE theme_id=".$ecom_themeid." LIMIT 1";
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
						echo generateselectbox('product_displaytype',$grp_type,0);
			?></td>
			  </tr>
			  </table></td></tr>
			  
              <tr style=" display:none;" id="field_disp_prod_id"><td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
               		<td  align="left" class="tdcolorgray"><strong> </strong> Fields to be displayed for Products<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SHOP_ADD_DISPPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
                      <table width="100%" border="0" cellspacing="1" cellpadding="1">
                        <tr>
                          <td width="14%">&nbsp;</td>
          <td width="96%" align="left"><input name="product_showimage" type="checkbox" value="1"  />
            Product Image </td>
        </tr>
                        <tr>
                          <td>&nbsp;</td>
          <td align="left"><input name="product_showtitle" type="checkbox" value="1" />
            Product Title </td>
        </tr>
                        <tr>
                          <td>&nbsp;</td>
          <td align="left"><input name="product_showshortdescription" type="checkbox" value="1" />
            Product Description </td>
        </tr>
                        <tr>
                          <td>&nbsp;</td>
          <td align="left"><input name="product_showprice" type="checkbox" value="1" />
            Product Price </td>
        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td align="left"><input name="product_showrating" type="checkbox" value="1" />
Product Rating </td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td align="left"><input name="product_showbonuspoints" type="checkbox" value="1" />
Product Bonus Points</td>
                        </tr>
                                      </table></td>
               
                 <td width="36%" align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
			  </tr>
			  </table></td></tr>
                <tr style=" display:none;" id="field_disp_mainimg_id"><td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0">
			    <tr>
			      <td colspan="2"  align="left" class="tdcolorgray">Main Image in Shop Details Page <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_PROD_SHOP_SHOW_NO_MAIN_IMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		        </tr>
			    <tr>
               		<td width="11%"  align="left" class="tdcolorgray"> <input name="shopbrand_turnoff_mainimage"  type="radio" value="0"/> Turn On
           		  </td>
                    <td width="89%"  align="left" class="tdcolorgray"><input name="shopbrand_turnoff_mainimage" type="radio" value="1"/> Turn Off
                      
                  </td>
			  </tr>
			  </table></td></tr>
			  <tr style=" display:none;" id="field_disp_moreimg_id"><td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0">
			    <tr>
			      <td colspan="2"  align="left" class="tdcolorgray">More Images in Shop Details Page <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_PROD_SHOP_SHOW_NO_MORE_IMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		        </tr>
			    <tr>
               		<td width="11%"  align="left" class="tdcolorgray"> <input name="shopbrand_turnoff_moreimages"  type="radio" value="0"/> Turn On
           		  </td>
                    <td width="89%"  align="left" class="tdcolorgray"><input name="shopbrand_turnoff_moreimages" type="radio" value="1"/> Turn Off
                    </td>
			  </tr>
			  </table></td></tr>
			  <tr style=" display:none;" id="field_disp_anyprodimg_id"><td colspan="3" align="left" class="tdcolorgray">
			  <table width="100%" cellpadding="0" cellspacing="0" border="0">
			    <tr>
			      <td colspan="3"  align="left" class="tdcolorgray">Show any product image for Shop <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SETTINGS_PROD_SHOP_SHOW_PROD_IMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		        </tr>
			    <tr>
               		<td width="11%"  align="left" class="tdcolorgray"> <input name="shopbrand_showimageofproduct"  type="radio" value="1" onclick="if(document.getElementById('ignore_assigned')){ document.getElementById('ignore_assigned').style.display='';}"/> Turn On
           		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('')?>')"; onmouseout="hideddrivetip()"></a></td>
                    <td width="20%"  align="left" class="tdcolorgray"><input name="shopbrand_showimageofproduct" type="radio" value="0"  onclick="if(document.getElementById('ignore_assigned')){ document.getElementById('ignore_assigned').style.display='none';}"/> Turn Off
                      
                  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('')?>')"; onmouseout="hideddrivetip()"></a></td>
			        <td width="69%"  align="left" class="tdcolorgray"><div id="ignore_assigned" style="display:none"><input name="chk_ignore_assigned_img_shop" type="checkbox" id="chk_ignore_assigned_img_shop" value="1" /> 
		            Ignore shops for which images are already assigned </div></td>
			    </tr>
			  </table></td></tr>
              <tr>
		<td colspan="3" align="left" class="seperationtd">Select Shops for which  you want to set the above settings <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SHOP_SETTINGS_TOMANY_SELECT_SHOPS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		</tr>
              <tr>
                <td colspan="3" align="left" class="tdcolorgray"><input type="radio" name="select_shops" value="All" onclick="display_shop_selector(this.value)"/>
Apply to all Shops
  <input type="radio" name="select_shops" value="Byshop"  onclick="display_shop_selector(this.value)"/> 
                Select Shops </td>
              </tr>
            
			  <tr id="shopselector_id" style="display:none">
		        <td align="left" valign="middle" class="tdcolorgraynormal">&nbsp;</td>
                <td colspan="2" align="left" valign="top" class="tdcolorgraynormal"><div style="float:left; width:100px; " >Select Shop </div>
                    <div style="float:left;"><?php
					
				$shop_arr = generate_shop_tree(0,0,false,true,true);
				if(is_array($shop_arr))
				{	
					echo generateselectbox('settings_shopid[]',$shop_arr,'','','',15);
				}
			  ?>
               
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SHOP_SETTINGS_TOMANY_SELECT_SHOPS_SHOP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></div></td>
    </tr>
              
        <tr id="shopsselector_id" style="display:" >
          <td colspan="3" align="left" valign="middle" class="tdcolorgraynormal"><div id="listcat_div"></div></td>
        </tr>        
         
        </table>
        </div>
        </td>
        </tr>
         <tr>
      <td colspan="4" class="tdcolorgray">
		  <div class="editarea_div">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">               
        <tr>
           <td colspan="3" align="right" valign="top"  >
			  <input type="hidden" name="shopname" id="shopname" value="<?=$_REQUEST['shopname']?>" />
		  <input type="hidden" name="show_shopgroup" id="show_shopgroup" value="<?=$_REQUEST['show_shopgroup']?>" />
		  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
		  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
		  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		  <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
		   <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
		<input type="hidden" name="retdiv_id" id="retdiv_id" value="" />
		<input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="save_settingstomany" />
			   <span class="tdcolorgray">
             <input name="Submit" type="submit" class="red" value="Set values" />
           </span>&nbsp;&nbsp; </td>
         </tr>
      </table>
      </div>
      </td>
      </tr>
      </table>
 	
</form>

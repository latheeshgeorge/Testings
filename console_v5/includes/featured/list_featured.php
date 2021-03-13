<?php
	/*#################################################################
	# Script Name 	: list_featured.php
	# Description 	: Page for listing Featured Product
	# Coded by 		: SKR
	# Created on	: 11-July-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name='product_featured';
$page_type='Featured Product';
$help_msg = get_help_messages('LIST_FEATURED_PROD_MESS1');
$table_headers = array('Slno.','Product','Man ID','Category','Retail','Cost','Bulk Disc','Disc','Stock(All)','Hide');
$header_positions=array('left','left','left','left','left','left','left','left','left','left');
$colspan = count($table_headers);


//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";

//#Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);#Getting total count of records
/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = (is_numeric($_REQUEST['records_per_page']) and $_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:10;#Total records shown in a page
$pg = ($_REQUEST['search_click'])?1:$_REQUEST['pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
$pages = ceil($numcount / $records_per_page);#Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}
$start = ($pg - 1) * $records_per_page;#Starting record.
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
/////////////////////////////////////////////////////////////////////////////////////
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=featured&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start";
// Find the feature_id for mod_adverts module from features table
	$sql_feat = "SELECT feature_id FROM features WHERE feature_modulename='mod_featured'";
	$ret_feat = $db->query($sql_feat);
	if ($db->num_rows($ret_feat))
	{
		$row_feat 	= $db->fetch_array($ret_feat);
		$feat_id	= $row_feat['feature_id'];
	}
?>
<script language="javascript">


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
function call_ajax_delete()
{
	var atleastone 	= 0;
	var del_ids 	= '';
	if(confirm('Are you sure you want to remove featured product?'))
	{
			show_processing();
			Handlewith_Ajax('services/featured_product.php','fpurpose=delete');
	}	
		
}
function valform(frm)
{
	fieldRequired = Array('display_id[]');
	fieldDescription = Array('Atleast One Display Location');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		 if(frm.featured_showimage.checked == false && 
				   frm.featured_showtitle.checked == false &&
				   frm.featured_showshortdescription.checked == false &&
				   frm.featured_showprice.checked == false) 
	    		{
					  alert('Please Check any of Product Items to Display ')	   
					  return false;
				}		
			show_processing();

		return true;
	} else {
		return false;
	}
}
</script>
<form name="frmlistFeatured" action="home.php" method="post" onsubmit="return valform(this);" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="featured" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
  <table border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span>List Featured Product</span></div></td>
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
          			<td colspan="3" align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
          		</tr>
		 <?php
		 	}
		 ?> 
   
     
    <tr>
      <td colspan="3" class="listingarea">
	  <div class="listingarea_div">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	  <tr>
		  <td align="right" valign="middle" class="listeditd"  colspan="<?=$colspan?>">
		  <a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=featured&fpurpose=assign&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Assign</a> 
		  <?
		  if($numcount)
		  {
		  ?>
		 <a href="#" onclick="call_ajax_delete()" class="deletelist">Delete</a>
		  <?
		  }
		  ?>
		  </td>
	  </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_user = "SELECT b.*,a.feature_id FROM $table_name as a,products as b  WHERE a.sites_site_id=$ecom_siteid AND a.products_product_id=b.product_id";
	   $res = $db->query($sql_user);
	   $srno = 1; 
	   while($row_qry = $db->fetch_array($res))
	   {
			$cls= ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
			if($row_qry['product_discount']>0)
			{
				$disctype	= ($row_qry['product_discount_enteredasval']==0)?'%':'';
				$discval_arr= explode(".",$row_qry['product_discount']);
				if($discval_arr[1]=='00')
					$discval = $discval_arr[0];
				else
					$discval = $row_qry['product_discount'];
				$disc		= $discval.$disctype;
				if(($row_qry['product_discount_enteredasval']==1))
					{
					
					 $disc = display_price($disc);
					}
			}	
			else
				$disctype = $disc = '--';
	   
	   ?>
        <tr>
				 
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="1%"><?php echo $srno++?></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="20%"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row_qry['product_id']?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="edit" class="edittextlink"><?php echo stripslashes($row_qry['product_name'])?></a></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>"><?php echo stripslashes($row_qry['manufacture_id'])?></td>	
				  <td align="left" valign="middle" class="<?php echo $cls?>">
				  <?php
				  		$cat_arr		= array();
				  		// Get the list of categories to which the current product is assigned to 
						$sql_cats = "SELECT a.category_id,a.category_name FROM product_categories a,product_category_map b WHERE 
									b.products_product_id=".$row_qry['product_id']." AND a.category_id=b.product_categories_category_id";
						$ret_cats = $db->query($sql_cats);
						if ($db->num_rows($ret_cats))
						{
							while ($row_cats = $db->fetch_array($ret_cats))
							{
								$catid = $row_cats['category_id'];
								$cat_arr[$catid] = stripslashes($row_cats['category_name']);
							}	
						}
						if (count($cat_arr))
						{
							echo generateselectbox('catid_'.$row_qry['product_id'],$cat_arr,0);
						}
						else
							echo "--";	
				  ?>				  </td>	
				  <td align="left" valign="middle" class="<?php echo $cls?>"><?php echo display_price($row_qry['product_webprice'])?></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>"><?php echo display_price($row_qry['product_costprice'])?></td>
				  <td align="center" valign="middle" class="<?php echo $cls?>"><?php echo $row_qry['product_bulkdiscount_allowed']?></td>	
				  <td align="center" valign="middle" class="<?php echo $cls?>"><?php echo $disc?></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>"><?php echo $row_qry['product_webstock']."(".$row_qry['product_actualstock'].")"?></td>
				  <td align="center" valign="middle" class="<?php echo $cls?>">
				  	<?php
				  		echo ($row_qry['product_hide']=='Y')?'Yes':'No';	
					?>				</td>
		  </tr>
      <?
	  $featured_id= $row_qry['feature_id'];
	  $product_id = $row_qry['product_id'];
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext"  colspan="<?=$colspan?>">
				  	No Featured Product Assigned.				  </td>
		  </tr>
		<?
		}
		?>	
		<tr>
		  <td align="right" valign="middle" class="listeditd"  colspan="<?=$colspan?>">
		  <a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=featured&fpurpose=assign&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Assign</a> 
		  <?
		  if($numcount)
		  {
		  ?>
		   <a href="#" onclick="call_ajax_delete()" class="deletelist">Delete</a>
		  <?
		  }
		  ?>
		  </td>
	  </tr>
      </table>
	  </div></td>
    </tr>
	
	<?
	if($numcount)
	{
	$sql_featured="SELECT featured_desc,featured_showimage,featured_showtitle,featured_showshortdescription,featured_showprice,featured_showimagetype FROM product_featured WHERE sites_site_id=".$ecom_siteid;
	$ret_featured = $db->query($sql_featured);
	$row_featured = $db->fetch_array($ret_featured)
	?>
	<tr>
	<td colspan="3">&nbsp;
	
	</td>
	</tr>
	<tr>
	<td colspan="3">
	<div class="editarea_div">
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	<td class="listingtableheader" width="60%" align="left">
	Featured Product Description
	<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_FEATU_PROD_DES')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	<td class="listingtableheader" width="40%">Fields to be shown for the featured product.<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_FEATU_PROD_DISPLAY')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	</tr>
	<tr>
	<td>
	 <?php
	 					$editor_elements = "featured_desc";
						include_once("js/tinymce.php");
						/*$editor 			= new FCKeditor('featured_desc') ;
						$editor->BasePath 	= '/console/js/FCKeditor/';
						$editor->Width 		= '600';
						$editor->Height 	= '300';
						$editor->ToolbarSet = 'BshopWithImages';
						$editor->Value 		= stripslashes($row_featured['featured_desc']);
						$editor->Create() ;*/
						
		?>
		<textarea style="height:300px; width:600px" id="featured_desc" name="featured_desc"><?=stripslashes($row_featured['featured_desc'])?></textarea>
	</td>
	<td valign="top" style="padding-left:20px;">
	<table width="80%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	<td width="6%" align="left" valign="middle" ><input type="checkbox" name="featured_showimage" value="1" <? if($row_featured['featured_showimage']==1) echo "checked";?>   />	</td>
	<td align="left" valign="middle" class="normltdtext" colspan="2" >
	 Image	  </td>
	</tr>
	<tr>
	
	<td width="6%" align="left" valign="middle" >
	 <input type="checkbox" name="featured_showtitle" value="1" <? if($row_featured['featured_showtitle']==1) echo "checked";?> />	 </td>
	 <td  align="left" valign="middle" class="normltdtext"  colspan="2">Title</td>
	</tr>
	<tr>
	
	<td width="6%" align="left" valign="middle">
	 <input type="checkbox" name="featured_showshortdescription" value="1" <? if($row_featured['featured_showshortdescription']==1) echo "checked";?> />	 </td>
	 <td  align="left" valign="middle" class="normltdtext" colspan="2" >Short Desc</td>
	</tr>
	<tr>
	
	<td width="6%" align="left" valign="middle" >
	 <input type="checkbox" name="featured_showprice" value="1" <? if($row_featured['featured_showprice']==1) echo "checked";?> />	 </td>
	 <td align="left" valign="middle" class="normltdtext" colspan="2">Price</td>
	</tr>
	<tr>
	<td align="left" valign="middle" class="normltdtext" >
	<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_FEATU_PROD_TYPE_IMG')?>')"; onmouseout="hideddrivetip()"></a></td>
	<td width="25%" align="left" valign="middle" class="normltdtext" > ImageType 	</td>
	<td width="69%" align="left" valign="middle" class="normltdtext" >
	<?= generateselectbox('featured_showimagetype',array('Thumb' => 'Small Image','Medium' => 'Medium Image','Big' => 'Big Image','Extra' => 'Extra Large Image'),$row_featured['featured_showimagetype']); ?>	<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_FEATU_PROD_TYPE_IMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	</tr>
	<tr>
	<td colspan="3" align="center">&nbsp;	</td>
	</tr>
	<tr  >
			   <td colspan="3" align="left" valign="middle" class="normltdtext">Display Location <span class="redtext">*</span></td>
		   </tr>
	<tr  >
	  <td colspan="2" align="left" valign="middle" class="normltdtext">&nbsp;</td>
	  <td align="left" valign="middle" class="normltdtext"><?php
		   // Find the display settings details for this advert
			$sql_disp = "SELECT a.display_id,a.display_position,a.layout_code,a.themes_layouts_layout_id,a.display_order,b.layout_name FROM 
					display_settings a,themes_layouts b WHERE a.sites_site_id = $ecom_siteid  AND 
					features_feature_id=$feat_id AND a.themes_layouts_layout_id=b.layout_id";
			$ret_disp = $db->query($sql_disp);
			$disp_array		= array();
			if ($db->num_rows($ret_disp))
			{
			   
				while ($row_disp = $db->fetch_array($ret_disp))
				{	
					$layoutid				= $row_disp['themes_layouts_layout_id'];
					$layoutcode				= $row_disp['layout_code'];
					$layoutname				= stripslashes($row_disp['layout_name']);
					$disp_id				= $row_disp['display_id'];
					$curid 					= $disp_id."_".$layoutid."_".stripslashes($layoutcode)."_".stripslashes($row_disp['display_position']);
					$ext_val[]				= $layoutid."_".stripslashes($layoutcode)."_".stripslashes($row_disp['display_position']);
					$disp_array[$curid] 	= $layoutname."(".stripslashes($row_disp['display_position']).")(".$row_disp['display_order'].")";
					$disp_ext_arr[]			= $curid; // used to highlight all the items in the dropdownbox
				}
			}
			// Get the list of position allovable for category groups for the current theme
			$sql_themes = "SELECT featuredproduct_positions FROM themes WHERE theme_id=$ecom_themeid";
			$ret_themes = $db->query($sql_themes);
			if ($db->num_rows($ret_themes))
			{
				$row_themes = $db->fetch_array($ret_themes);
				$featpos_arr	= explode(",",$row_themes['featuredproduct_positions']);
			}
			// Get the layouts fot the current theme
			$sql_layouts = "SELECT layout_id,layout_code,layout_name,layout_positions FROM themes_layouts WHERE 
							themes_theme_id=$ecom_themeid ORDER BY layout_name";
			$ret_layouts = $db->query($sql_layouts);
			if ($db->num_rows($ret_layouts))
			{
				while ($row_layouts = $db->fetch_array($ret_layouts))
				{
					$pos_arr = explode(',',$row_layouts['layout_positions']);
					if(count($pos_arr))
					{
						for($i=0;$i<count($pos_arr);$i++)
						{
							if(in_array($pos_arr[$i],$featpos_arr))
							{
								$curid 				= $row_layouts['layout_id']."_".stripslashes($row_layouts['layout_code'])."_".$pos_arr[$i];
								if(count($ext_val))
								{
									if(!in_array($curid,$ext_val))
									{
										$curname	= stripslashes($row_layouts['layout_name'])." (".$pos_arr[$i].")";
										$disp_array["0_".$curid] = $curname;
									}
								}
								else
								{
									$curname		= stripslashes($row_layouts['layout_name'])." (".$pos_arr[$i].")";
									$disp_array["0_".$curid] = $curname;
								}	
							}	
						}
					}		
				}
			}
			echo generateselectbox('display_id[]',$disp_array,$disp_ext_arr,'','',5);
		  ?></td>
	  </tr>
	  <tr>
	<td colspan="3" align="center">&nbsp;	</td>
	</tr>
	<tr>
	<td colspan="3" align="center">
	<input type="hidden" name="product_id" id="product_id" value="<?=$product_id?>" />
	  <input type="hidden" name="feature_id"  id="feature_id" value="<?=$featured_id?>"  />
	 <input name="Save" type="submit" class="red" id="change_hide"  value="Save">	</td>
	</tr>
	</table>
	</td>
	</tr>
	</table></div>
	</td>
	</tr>
	<?
	}
	?>
  </table>
</form>
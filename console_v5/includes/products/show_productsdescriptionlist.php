<?php
	/*#################################################################
	# Script Name 		: show_productsdescriptionlist.php
	# Description 		: Bulk Update Product Description
	# Coded by 			: Sny
	# Created on		: 10-Nov-2016
	# Modified by		: Sny
	# Modified On		: 10-Nov-2016
#################################################################*/
	//Define constants for this page
	$page_type 	= 'Bulk Update Product Description';
	$help_msg 		= 'This feature allows you to update descriptions of multiple product at a time.';
	//$help_msg = get_help_messages('LIST_DATABASE_OFFLINE_MESS1');

	
	$catinclude_prod = array();
	if($_REQUEST['categoryid']) // case if category is selected
	{
		// Get the id's of products under this category
		$sql_catmap = "SELECT products_product_id FROM product_category_map WHERE product_categories_category_id=".$_REQUEST['categoryid'];
		$ret_catmap = $db->query($sql_catmap);
		if ($db->num_rows($ret_catmap))
		{
			while ($row_catmap = $db->fetch_array($ret_catmap))
			{
				$catinclude_prod[] = $row_catmap['products_product_id'];
			}
		}
	}
	
?>
<script type="text/javascript">
function validate_bulkdesc_form()
{
	if(document.getElementById('categoryid').value=='' || document.getElementById('categoryid').value=='0')
	{
		alert('Please select a category from the list');
		return false;
	}
	return true;
}
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
	<td align="left" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=products">List Products</a> <span><?php echo $page_type?></span></div></td>
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
			<tr id="alert_tr">
				<td colspan="2" align="center" valign="middle" class="errormsg" ><?=stripslashes($alert)?></td>
			</tr>
		<?
		}
		?>
	</table>
	<div class="editarea_div">
		
		<form method="post" name="frm_products" class="frmcls" action="home.php" onsubmit="return validate_bulkdesc_form()">
		<input type="hidden" name="request" value="products" />
		<input type="hidden" name="fpurpose" value="showbulkupdatedesc" />
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td class='sorttd'>
			<table width="100%" border="0" cellspacing="1" cellpadding="2">	
			<tr>
				<td align="left" valign="top" width="15%">Select Category</td> 
				<td align="left" valign="top" width="25%">

					<?php
					$cat_arr = generate_category_tree(0,0,true);
					if(is_array($cat_arr))
					{
						echo generateselectbox('categoryid',$cat_arr,$_REQUEST['categoryid']);
					}
					?>
				
				</td>
				<td align="left" valign="top" width="60%"><input type="submit" name="submitquery" value="Go" class="red"></td> 
			</tr>
			</table>
		</table>
		</form>
	</div> 
</td>
</tr>
<?php
if (count($catinclude_prod))
{
		$editor_elements= '';
		$editor_element_arr = array();
		for($i=0;$i<count($catinclude_prod);$i++)
		{
			$editor_element_arr[] = "long_".$catinclude_prod[$i];
		}	
		$editor_elements = implode(',',$editor_element_arr);

		include_once(ORG_DOCROOT."/console/js/tinymce.php");
?>
	<tr>
	<td valign="top">
		
		<form method="post" name="frm_products" class="frmcls" action="home.php" onsubmit="return validate_bulkdesc_form()">
		<input type="hidden" name="request" value="products" />
		<input type="hidden" name="fpurpose" value="showbulkupdatedesc_save" />
		<input type="hidden" name="categoryid" value="<?php echo $_REQUEST['categoryid']?>" />
		<table  border="0" cellpadding="2" cellspacing="2" class="listingtable">
		
			<?php 
			$sql_prod = "SELECT product_id,product_name,product_shortdesc,product_longdesc,product_keywords 
							FROM 
								products 
							WHERE 
								sites_site_id = $ecom_siteid 
								AND product_id IN(".implode(',',$catinclude_prod).") ORDER BY product_id ASC";
			$ret_prod = $db->query($sql_prod);
			if($db->num_rows($ret_prod))
			{
				$cntr = 1;
				while($row_prod = mysql_fetch_array($ret_prod))
				{
					$cls 		= ($cntr%2==0)?'listingtablestyleB':'listingtablestyleB';
					?>
					<tr>
						<td align="left">
						<input type="hidden" name="prodbulk_arr[]" value="<?php echo $row_prod['product_id'];?>"/>	
						<table width="100%" cellpadding="1" cellspacing="1" border="0">
						<tr>
							<td align="left" class="listingtableheader" colspan="2"><?php echo $cntr.'. ';$cntr++;echo $row_prod['product_name'];?></td>
						</tr>
						<tr>
							<td align="left" class="<?php echo $cls?>" valign="top">Short Description</td>
							<td align="left" class="<?php echo $cls?>" valign="top"><input type="text" name="short_<?php echo $row_prod['product_id']?>" id ="short_<?php echo $row_prod['product_id']?>" value="<?php echo stripslashes($row_prod['product_shortdesc'])?>" maxlength="1000" size="104"></td>
						</tr>
						<tr>
							<td align="left" class="<?php echo $cls?>" valign="top">Long Description</td>
							<td align="left" class="<?php echo $cls?>" valign="top">
							<textarea style="height:300px; width:650px" id="long_<?php echo $row_prod['product_id']?>" name="long_<?php echo $row_prod['product_id']?>"><?=stripslashes($row_prod['product_longdesc'])?></textarea>
							</td>
						</tr>
						<tr>
							<td align="left" class="<?php echo $cls?>" valign="top">Product Keywords</td>
							<td align="left" class="<?php echo $cls?>" valign="top">
							<textarea style="height:100px; width:640px" id="kw_<?php echo $row_prod['product_id']?>" name="kw_<?php echo $row_prod['product_id']?>"><?=stripslashes($row_prod['product_keywords'])?></textarea>
							</td>
						</tr>
						</table>	
						</td>
					</tr>		
					<?php
				}
			}	
			?>
		<tr>
		 <td align="center">
			 <input type="submit" name="save_bulkdesc" id ="save_bulkdesc" value="Update Details" class="red"/>		
		 </td>
		</tr> 	 
		</table>	
		</form>
	</td>
	</tr>	
<?php
}
else
{
	if($_REQUEST['categoryid'])
	{
	?>
	<tr>
		 <td align="center" class="errormsg">Sorry! No Products Found Directly Under this Category</td>
	</tr>	 
	<?php
	}
}
?>
</table>	 	
<script type="text/javascript">
var $pnc = jQuery.noConflict();
$pnc().ready( function() {
        $pnc('#alert_tr').delay(8000).fadeOut();
      });


</script>	

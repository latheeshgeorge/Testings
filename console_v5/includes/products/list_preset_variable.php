<?php
	/*#################################################################
	# Script Name 	: list_sel_linked_products.php
	# Description 	: Page for listing Linked Products
	# Coded by 		: Sny
	# Created on	: 04-July-2007
	# Modified by	: Sny
	# Modified On	: 23-Jul-2007
	#################################################################*/

// Get the name of current product
$sql_prod = "SELECT product_name,product_default_category_id FROM products WHERE product_id=".$_REQUEST['checkbox'][0]." AND sites_site_id=".$ecom_siteid." LIMIT 1" ;
$ret_prod = $db->query($sql_prod);
if ($db->num_rows($ret_prod))
{
	$row_prod    = $db->fetch_array($ret_prod);
	$def_cat_id  = $row_prod['product_default_category_id'];
	$showprodname = stripslashes($row_prod['product_name']);
}
if($def_cat_id)
{
     $sql_cat = "SELECT category_name FROM product_categories  WHERE category_id = $def_cat_id AND sites_site_id=$ecom_siteid LIMIT 1";
    $ret_cat = $db->query($sql_cat);
    $row_cat = $db->fetch_array($ret_cat);
    $cat_name =  $row_cat['category_name'];
    $sql_vargroup = "SELECT var_group_name FROM	product_variables_group a,product_categories b WHERE b.category_id = $def_cat_id AND b.product_variables_group_id = a.var_group_id AND a.sites_site_id=$ecom_siteid LIMIT 1";
    $ret_vargroup = $db->query($sql_vargroup);
    $row_vargroup = $db->fetch_array($ret_vargroup);
    $grp_name     = $row_vargroup['var_group_name'];
    $sql_var1ables = "SELECT a.product_variables_id FROM product_variables_group_variables_map a,product_categories b WHERE b.category_id = $def_cat_id AND b.product_variables_group_id=a.product_variables_group_id AND a.sites_site_id = $ecom_siteid";
	$ret_variables = $db->query($sql_var1ables);
	$var_array = array();

	if($db->num_rows($ret_variables)>0)
	{
	 while($row_variables = $db->fetch_array($ret_variables))
	 {
	   $var_array[] = $row_variables['product_variables_id'];
	 }
	}
	else
	{
	$var_array[0]=-1;
	}
}
//Define constants for this page
$table_name='product_preset_variables';
$page_type='Preset variables';
$help_msg = get_help_messages('EDIT_PROD_GRIDVAR_ASSMORE');
$help_msg = str_replace("[productname]",$showprodname,$help_msg);
$table_headers = array('','Slno.','Variable','Variable Group ','Category','Hide');
$header_positions=array('center','left','left','left','left');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('var_name');

$query_string = "request=products&fpurpose=assign_preset_variable&checkbox[0]=".$_REQUEST['checkbox'][0]."&curtab=".$_REQUEST['curtab']."&records_per_page_link=".$_REQUEST['records_per_page_link']."&pss_records_per_page=".$_REQUEST['pss_records_per_page']."&pss_pg=".$_REQUEST['pss_pg']."&pss_start=".$_REQUEST['pss_start'];
foreach($search_fields as $v) {
	$query_string .= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by_link = (!$_REQUEST['sort_by_link'])?'var_name':$_REQUEST['sort_by_link'];
$sort_order_link = (!$_REQUEST['sort_order_link'])?'ASC':$_REQUEST['sort_order_link'];
$sort_options = array('var_name' => 'Variable Name');
$sort_option_txt = generateselectbox('sort_by_link',$sort_options,$sort_by_link);
$sort_by_txt= generateselectbox('sort_order_link',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order_link);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid AND var_value_exists=1  AND var_id IN(".implode(',',$var_array).")";

// Product Name Condition
if($_REQUEST['variablename_link'])
{
	$where_conditions .= " AND ( var_name LIKE '%".add_slash($_REQUEST['variablename_link'])."%') ";
}
// ==================================================================================================
// ==================================================================================================

//#Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);#Getting total count of records
/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = (is_numeric($_REQUEST['records_per_page_link']) and $_REQUEST['records_per_page_link'] and $_REQUEST['records_per_page_link']>1)?intval($_REQUEST['records_per_page_link']):30;#Total records shown in a page
$pg = ($_REQUEST['search_click'])?1:$_REQUEST['pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
$pages = ceil($numcount / $records_per_page);#Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}
if ($pg>=1)
{
	$start = ($pg - 1) * $records_per_page;//#Starting record.
	$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
}	
else
{
	$start = $count_no = 0;	
}

/////////////////////////////////////////////////////////////////////////////////////

$sql_qry = "SELECT * FROM product_preset_variables	$where_conditions ORDER BY $sort_by_link $sort_order_link LIMIT $start,$records_per_page ";
$ret_qry = $db->query($sql_qry);
?>
<script type="text/javascript">
function val_form()
{
	var atleast = false;
	for(i=0;i<document.frm_products_preset_variables.elements.length;i++)
	{
		if (document.frm_products_preset_variables.elements[i].type =='checkbox' && document.frm_products_preset_variables.elements[i].name.substring(0,11)=='checkboxvar')
		{
			if(document.frm_products_preset_variables.elements[i].checked)
				atleast = true;
		}
	}
	if (atleast==false)
	{
		alert('Select the variable to be linked');
		return false;
	}
	else
	{
		if(confirm('Are you sure you want to assign the selected variables? '))
		{
		document.frm_products_preset_variables.fpurpose.value='grid_assig_presetvar';
		document.frm_products_preset_variables.submit();
		}
	}	
}
//grid display
function show_variable_values(id)
{
	  var ckbxname = 'checkboxvar_'+id;
	  if(document.getElementById(ckbxname).type=='checkbox')
	  {
		if(document.getElementById(ckbxname).checked==true)
		{		
			document.getElementById('tr_'+id).style.display='';
		}
		else
		{
		document.getElementById('tr_'+id).style.display='none';
		}
	  }
 /*if(document.frm_products_preset_variables.checkbox_link[].checked==true)
 { alert('test');
   document.getElementById('tr_28').style.display='block';
 }
 */
 }
</script>
<?php ?>
<form method="post" name="frm_products_preset_variables" class="frmcls" action="home.php">
<input type="hidden" name="request" value="products" />
<input type="hidden" name="fpurpose" value="assign_preset_variable" />
<input type="hidden" name="search_click" value="" />
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
<input type="hidden" name="records_per_page_link" id="records_per_page_link" value="<?=$_REQUEST['records_per_page_link']?>" />
<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
<input type="hidden" name="parent_id" id="parent_id" value="<?=$_REQUEST['parent_id']?>" />
<input type="hidden" name="checkbox[0]" id="checkbox[0]" value="<? echo $_REQUEST['checkbox'][0];?>" />
<input type="hidden" name="curtab" id="curtab" value="<?=$_REQUEST['curtab']?>" />
<input type="hidden" name="pss_records_per_page" id="pss_records_per_page" value="<?=$_REQUEST['pss_records_per_page']?>" />
<input type="hidden" name="pss_start" id="pss_start" value="<?=$_REQUEST['pss_start']?>" />
<input type="hidden" name="pss_pg" id="pss_pg" value="<?=$_REQUEST['pss_pg']?>" />
<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />

<table border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="2" align="left" valign="middle" class="treemenutd">
		  <div class="treemenutd_div">
	  <a href="home.php?request=products&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&start=<?php echo $_REQUEST['pss_start']?>&pg=<?php echo $_REQUEST['pss_pg']?>&records_per_page=<?php echo $_REQUEST['pss_records_per_page']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>">List Products</a>  <a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&start=<?php echo $_REQUEST['pss_start']?>&pg=<?php echo $_REQUEST['pss_pg']?>&records_per_page=<?php echo $_REQUEST['pss_records_per_page']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>&curtab=<?=$_REQUEST['curtab']?>">Edit Product</a>  <span> Assign Preset Variable for &quot;<?php echo $showprodname?>&quot;	 </span></div></td>
    </tr>
	<tr>
	  <td colspan="2" align="left" valign="middle" class="helpmsgtd_main">
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
          			<td colspan="2" align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
       		    </tr>
		 <?php
		 	}
		 ?> 
    <tr>
      <td height="48" colspan="2" class="sorttd">
	  <div class="editarea_div">

		<table width="100%" border="0" cellpadding="0" cellspacing="0">
       
		<tr>
		 <td width="" align="left">Variable Name</td>
              <td width="" align="left"><input name="variablename_link" type="text" class="textfeild" id="variablename_link" value="<?php echo $_REQUEST['variablename_link']?>" /></td>
 
			<td  align="left" valign="top">
		  <table width="100%"  border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="65%" align="left"><div align="right">Show
                <input name="records_per_page_link" type="text" class="textfeild" id="records_per_page_link" size="3" maxlength="3" value="<?php echo $records_per_page?>" />
                  <?php echo $page_type?> Sort By&nbsp;<?php echo $sort_option_txt;?> in <?php echo $sort_by_txt?> </div></td>
              <td width="35%" align="right"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_products_link.search_click.value=1" />
                <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_LINKED_ASSGO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a></td>
            </tr>
          </table>		  </td>
			</tr>
      </table>    	  </div>
  </td>
    </tr>
      <?php 
		 if($numcount)
	  {
			?>
    <tr>
      <td colspan="0" align="right" valign="middle" class="sorttd>
        <div align="center">
        <?php 
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		
		?>
          </div></td>
    </tr>
    <?php
	}
    ?>
    <tr>
      <td colspan="2" class="listingarea">
		  	  <div class="listingarea_div">

      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	 <?php  
	 	echo table_header($table_headers,$header_positions); 
		if ($db->num_rows($ret_qry))
		{  
			$srno = getStartOfPageno($records_per_page,$pg);
			while ($row_qry = $db->fetch_array($ret_qry))
			{
				$cls 		= ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';				
	 ?>
			   	<tr>
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="5%">
				  <input type="checkbox" name="checkboxvar_<?php echo $row_qry['var_id']?>" id="checkboxvar_<?php echo $row_qry['var_id']?>" value="<?php echo $row_qry['var_id']?>" onclick="show_variable_values('<?php echo $row_qry['var_id']?>')" /></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="1%"><?php echo $srno++?></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="20%"><?php echo stripslashes($row_qry['var_name'])?></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>"><?php echo $grp_name?></td>	
				  <td align="left" valign="middle" class="<?php echo $cls?>">
				  <?php
				  		echo $cat_name;
				  ?></td>	
				  	  <td align="center" valign="middle" class="<?php echo $cls?>">
				  	<?php
				  		echo ($row_qry['var_hide']=='Y')?'Yes':'No';	
					?>
					</td>
				</tr>
				<tr style="display:none;" id="tr_<?php echo $row_qry['var_id']?>">
				<td colspan="<?php echo $colspan?>">
               <table  border="0" cellpadding="2" cellspacing="2" class="" width="30%">
				<?php
				 $clsA 		= 'listingtablestyleB';
				 $sql_var_value = "SELECT var_value_id,product_variables_var_id,var_value FROM product_preset_variable_data WHERE product_variables_var_id =".$row_qry['var_id']." AND sites_site_id=$ecom_siteid"; 
				 $ret_var_value = $db->query($sql_var_value);
				 while($row_var_value = $db->fetch_array($ret_var_value))
				 {
				?>
				<tr>
				<td align="center" valign="middle" class="" width="5%">&nbsp;</td>
				<td align="center" valign="middle" class="<?php echo $clsA?>" width="5%"><input type="checkbox" name="checkboxvalue_<?php echo $row_qry['var_id']?>_<?php echo $row_var_value['var_value_id']?>" id="checkboxvalue_<?php echo $row_qry['var_id']?>_<?php echo $row_var_value['var_value_id']?>" value="<?php echo $row_var_value['var_value_id']?>" />
				</td>
				<td align="left" valign="middle" class="<?php echo $clsA?>"><?php echo $row_var_value['var_value']?></td>
				</tr>
				<?php
			     }
				?>
				</table> 
				</td>
				</tr>
	<?php
			}
			?>
			
			<?php
		}
		else
		{
			
	?>	
			<tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>">
				  	No Variables found.				  </td>
			</tr>	  
	<?php
		}
	?>
      </table>				</div>
</td>
    </tr>
    <?php
    if ($db->num_rows($ret_qry))
		{
    ?>
    <tr>
           <td align="left" valign="top" class="" colspan="3" >
		   <div class="editarea_div">
		   <table width="100%" border="0" cellspacing="1" cellpadding="1">
			   <tr>
			<td colspan="<?php echo $colspan ?>" align="center">
			<input name="Assignmore_tab" type="button" class="red" id="Assignmore_tab" value="Assign Selected" onclick="val_form()" />
			</td>
      <td align="center" valign="middle" class="listeditd">	    
        <div align="center">
          <?php 
		
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		?>
          </div></td>
      <td align="center" class="listeditd">&nbsp;</td>
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

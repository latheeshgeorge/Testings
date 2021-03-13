<?php
	/*#################################################################
	# Script Name 	: list_sel_general_attachmemnts.php
	# Description 	: Page for listing product general attachments
	# Coded by 		: Sny
	# Created on	: 13-Aug-2010
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
// Get the name of current product
$sql_prod = "SELECT product_name FROM products WHERE product_id=".$_REQUEST['checkbox'][0];
$ret_prod = $db->query($sql_prod);
if ($db->num_rows($ret_prod))
{
	$row_prod = $db->fetch_array($ret_prod);
	$showprodname = stripslashes($row_prod['product_name']);
}

	$tabale = "products";
	$where  = "product_id=".$_REQUEST['checkbox'][0];
	if(!server_check($tabale, $where)) {
		echo " <font color='red'> You Are Not Authorised  </a>";
		exit;
	}
	
$ext_arr[0] = $_REQUEST['checkbox'][0]; // current product
// Get the list of products which are already linked with the current product
$sql_linkexist = "SELECT product_common_attachments_common_attachment_id FROM product_attachments WHERE products_product_id=".$_REQUEST['checkbox'][0];
$ret_linkexist = $db->query($sql_linkexist);
if ($db->num_rows($ret_linkexist))
{
	while ($row_linkexist = $db->fetch_array($ret_linkexist))
	{
		$ext_arr[] = $row_linkexist['product_common_attachments_common_attachment_id'];
	}
}
//Define constants for this page
$table_name='product_common_attachments';
$page_type='Product Common Tabs';
$help_msg = get_help_messages('EDIT_PROD_LINKED_ASSMORE_ATTACHMENTS');
$help_msg = str_replace("[productname]",$showprodname,$help_msg);
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_gentab_link,\'checkbox_link[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_gentab_link,\'checkbox_link[]\')"/>','Slno.','Common Attachment Title','Type','Hidden');
$header_positions=array('center','center','left','center');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('gen_title');

$query_string = "request=products&fpurpose=add_prodgenattach&prod_dontsave=1&checkbox[0]=".$_REQUEST['checkbox'][0]."&curtab=".$_REQUEST['curtab']."&records_per_page_link=".$_REQUEST['records_per_page_link']."&pss_records_per_page=".$_REQUEST['pss_records_per_page']."&pss_pg=".$_REQUEST['pss_pg']."&pss_start=".$_REQUEST['pss_start'];
foreach($search_fields as $v) {
	$query_string .= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by_link = (!$_REQUEST['sort_by_link'])?'attachment_title':$_REQUEST['sort_by_link'];
$sort_order_link = (!$_REQUEST['sort_order_link'])?'ASC':$_REQUEST['sort_order_link'];
$sort_options = array('attachment_title' => 'Title');
$sort_option_txt = generateselectbox('sort_by_link',$sort_options,$sort_by_link);
$sort_by_txt= generateselectbox('sort_order_link',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order_link);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid AND common_attachment_id  NOT IN (".implode(",",$ext_arr).") ";

// Product Name Condition
if($_REQUEST['gen_title'])
{
	$where_conditions .= " AND ( attachment_title LIKE '%".add_slash($_REQUEST['gen_title'])."%') ";
}


//#Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);#Getting total count of records
/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = (is_numeric($_REQUEST['records_per_page_link']) and $_REQUEST['records_per_page_link'] and $_REQUEST['records_per_page_link']>0)?intval($_REQUEST['records_per_page_link']):10;#Total records shown in a page
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

$sql_qry = "SELECT * FROM product_common_attachments $where_conditions ORDER BY $sort_by_link $sort_order_link LIMIT $start,$records_per_page ";
$ret_qry = $db->query($sql_qry);
?>
<script type="text/javascript">
function val_form()
{
	var atleast = false;
	for(i=0;i<document.frm_gentab_link.elements.length;i++)
	{
		if (document.frm_gentab_link.elements[i].type =='checkbox' && document.frm_gentab_link.elements[i].name=='checkbox_link[]')
		{
			if(document.frm_gentab_link.elements[i].checked)
				atleast = true;
		}
	}
	if (atleast==false)
	{
		alert('Select the attachment(s) to be linked');
		return false;
	}
	else
	{
		if(confirm('Are you sure you want to assign selected Common Product Attachment(s) to current product?'))
		{
			document.frm_gentab_link.fpurpose.value='assig_prodgenattach';
			document.frm_gentab_link.submit();
		}	
	}	
}
</script>
<form method="post" name="frm_gentab_link" class="frmcls" action="home.php">
<input type="hidden" name="request" value="products" />
<input type="hidden" name="fpurpose" value="add_prodgenattach" />
<input type="hidden" name="prod_dontsave" value="1" />
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
	 <div class="treemenutd_div"> <a href="home.php?request=products&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&start=<?php echo $_REQUEST['pss_start']?>&pg=<?php echo $_REQUEST['pss_pg']?>&records_per_page=<?php echo $_REQUEST['pss_records_per_page']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>">List Products</a>  <a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&start=<?php echo $_REQUEST['pss_start']?>&pg=<?php echo $_REQUEST['pss_pg']?>&records_per_page=<?php echo $_REQUEST['pss_records_per_page']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>&curtab=<?=$_REQUEST['curtab']?>">Edit Product</a>  <span> Assign Common Product Attachments for &quot;<?php echo $showprodname?>&quot;</span></div>	 </td>
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
		<div class="sorttd_div">
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="4%" align="left">Title</td>
              <td width="39%" align="left"><input name="gen_title" type="text" class="textfeild" id="gen_title" value="<?php echo $_REQUEST['gen_title']?>" /></td>
              <td width="57%" align="left"><table width="100%"  border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="65%" align="left"><div align="right">Show
                    <input name="records_per_page_link" type="text" class="textfeild" id="records_per_page_link" size="3" maxlength="3" value="<?php echo $records_per_page?>" />
                          <?php echo $page_type?> Sort By&nbsp;<?php echo $sort_option_txt;?> in <?php echo $sort_by_txt?> </div></td>
                  <td width="35%" align="right"><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frm_gentab_link.search_click.value=1" />
                    <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_LINKED_ASSGO_ATTACH')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                </tr>
              </table></td>
              </tr>
          </table>
		 </div> 
		  </td>
    </tr>
    <tr>
      <td width="764" align="center" class="listeditd1">
        <div align="center">
          <?php 
		if ($db->num_rows($ret_qry))
		{
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		}
		?>
          </div></td><td width="409" align="right" class="listeditd1">
        <input name="Assignmore_tab" type="button" class="red" id="Assignmore_tab" value="Assign Selected" onclick="val_form()" />
     </td>
    </tr>
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
				  <input type="checkbox" name="checkbox_link[]" id="checkbox_link[]" value="<?php echo $row_qry['common_attachment_id']?>" /></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="1%"><?php echo $srno++?></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="50%"><a href="home.php?request=common_prod_attachment&fpurpose=edit&checkbox[0]=<?php echo $row_qry['common_attachment_id']?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="edit" class="edittextlink"><?php echo stripslashes($row_qry['attachment_title'])?></a></td>
				  <td align="center" valign="middle" class="<?php echo $cls?>">
				  	<?php
				  		echo $row_qry['attachment_type'];	
					?>
					</td>	
				  <td align="center" valign="middle" class="<?php echo $cls?>">
				  	<?php
				  		echo ($row_qry['attachment_hide']==1)?'Yes':'No';	
					?>
					</td>
				</tr>
	<?php
			}
		}
		else
		{
	?>	
			<tr>
				<td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>">
				  	No unassigned Common Product Attachments found.
				</td>
			</tr>	  
	<?php
		}
	?>
      </table>
	  </div>
	  </td>
    </tr>
	<tr>
      <td align="center" valign="middle" class="listeditd">
	    
        <div align="center">
        <?php 
		if ($db->num_rows($ret_qry))
		{
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		}
		?>
          </div></td>
      <td align="center" class="listeditd">&nbsp;</td>
	</tr>
    </table>
</form>
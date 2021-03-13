<?php
	/*#################################################################
	# Script Name 	: edit_newsletter.php
	# Description 	: Page for editing News Letter
	# Coded by 		: ANU
	# Created on	: 29-Aug-2007
	# Modified by	: ANU
	# Modified On	: 29-Aug-2007
	#################################################################*/
#Define constants for this page

$page_type = 'newsletter';
$help_msg = get_help_messages('NEWSLETTER_ASSIGNED_PROD_MESS1');
($newsletter_id)?$newsletter_id:$_REQUEST['newsletter_id']; 
	
	$tabale = "newsletters";
	$where  = "newsletter_id=".$newsletter_id;
	if(!server_check($tabale, $where)) {
		echo " <font color='red'> You Are Not Authorised  </a>";
		exit;
	}
	
$sql="SELECT newsletter_id,newsletter_title,newsletter_contents,newsletter_createdate,newsletter_lastupdate 
			FROM newsletters 
				WHERE sites_site_id=$ecom_siteid AND newsletter_id=".$newsletter_id;
$res=$db->query($sql);
$row=$db->fetch_array($res);

?>	
<script language="javascript" type="text/javascript">

function skipval()
{
	frm = document.frmProductNewsletter;
	frm.fpurpose.value='preview';
	frm.submit();
}
function newsletter_continue()
{
	frm = document.frmProductNewsletter;
	frm.fpurpose.value='preview';
	frm.submit();
}

function unassiagn_prod(mod,checkboxname)
{
	var newsletter_id			= '<?php echo $newsletter_id?>';
	var atleastone 			= 0;
	var del_ids 			= '';
	for(i=0;i<document.frmProductNewsletter.elements.length;i++)
	{
		if (document.frmProductNewsletter.elements[i].type =='checkbox' && document.frmProductNewsletter.elements[i].name==checkboxname)
		{

			if (document.frmProductNewsletter.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmProductNewsletter.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		
		
		case 'product': // Case of product tabs
			atleastmsg 	= 'Please select the product(s) to be deleted';
			confirmmsg 	= 'Are you sure you want to delete the selected Product(s)?'
			fpurpose	= 'delete_assign_products';
		break;
		
	}
	if (atleastone==0)
	{
		alert(atleastmsg);
	}
	else 
	{
		if(confirm(confirmmsg))
		{ 
		//	document.frmProductNewsletter.action = 'home.php?request=newsletter+&fpurpose='+fpurpose+'&cur_newsletter_id='+newsletter_id+'&del_ids='+del_ids+'&'+qrystr;
			document.frmProductNewsletter.fpurpose.value  = fpurpose;
			document.frmProductNewsletter.del_ids.value  = del_ids;			
			document.frmProductNewsletter.submit();
		}	
	}
}

</script>
<form name='frmProductNewsletter' action='home.php'  method="post" >
<input type="hidden" name="fpurpose" />
<input type="hidden" name="request" value="newsletter" />
 <input type="hidden" name="newsletter_id" id="newsletter_id" value="<?=$_REQUEST['newsletter_id']?>" />
 <input type="hidden" name="retdiv_id" id="retdiv_id" value="maincontent" /> 
  <input type="hidden" name="del_ids" id="del_ids"  /> 
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="9" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=newsletter&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&search_name=<?=$_REQUEST['pass_search_name']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>">List Newsletter</a> <a href="home.php?request=newsletter&fpurpose=edit&newsletter_id=<?=$newsletter_id?>"> Edit Newsletter </a><span> Assigned Products</span></div></td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="9">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<tr>
          <td colspan="5" align="left" valign="middle" > <?PHP echo newsletter_tabs('prods_tab_td',$newsletter_id) ?></td>
      </tr>	
       	<?php 
		$sql_products = "SELECT p.product_id,p.product_name,p.product_discount,p.product_webprice, p.product_costprice, p.product_bulkdiscount_allowed,
								p.product_webstock, p.product_actualstock, adp.id 
								FROM products p,newsletter_products adp
									WHERE adp.products_product_id=p.product_id  AND adp.sites_site_id=$ecom_siteid
										  AND newsletters_newsletter_id=$newsletter_id
												ORDER BY product_name";
		$ret_products = $db->query($sql_products);
		
					?> 
		<tr>
          <td colspan="9" align="left" valign="middle" class="tdcolorgray" >
		  <div class="editarea_div">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr> <td colspan="3" align="left" valign="middle" class="tdcolorgray" >&nbsp;<strong>Assigned Products</strong> </td>
		  <td colspan="6" align="left" valign="middle" class="tdcolorgray" ><div align="right">
		  <?PHP 
		  $prodcont = $db->num_rows($ret_products);
		  if ($prodcont) {
		  ?>
		    <input name="continue" type="button" class="red" id="continue" value=" Continue " onclick="javascript:newsletter_continue()" />&nbsp;
		  		    <input name="products_unassign" type="button" class="red" id="products_unassign" value="Un Assign" onclick="unassiagn_prod('product','checkboxproducts[]')" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('NEWSLETTER_UNASS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;
			<input name="Addmore" type="button" class="red" id="Addmore" value="Assign More" onclick="document.frmProductNewsletter.fpurpose.value='list_assign_products';document.frmProductNewsletter.submit();" />
						<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('NEWSLETTER_ASS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;

			<?PHP } else { ?>
			
  <input name="Addmore" type="button" class="red" id="Addmore" value=" Assign " onclick="document.frmProductNewsletter.fpurpose.value='list_assign_products';document.frmProductNewsletter.submit();" />
  
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('NEWSLETTER_ASS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;
		    <input type="button" name="Submit" value=" Skip " onclick="javascript:skipval()" class="red" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('NEWSLETTER_SKIP_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a><? } ?> </div></td>
	</tr>
		<tr>
		  <td colspan="9" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
    </tr>
		
	<?php 
		
			if($alert)
			{
					?>
		<tr>
		  <td colspan="9" align="center" valign="middle" class="errormsg"><?php echo $alert?></td>
   		</tr>
   <?PHP
	    }		
			$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmProductNewsletter,\'checkboxproducts[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmProductNewsletter,\'checkboxproducts[]\')"/>','Slno.','Product Name','Retail','Cost','Bulk Discount','Disc','Stock(All)','Hidden'); //'Image',
			$header_positions=array('center','center','left','center','center','center','center','center','center');
			$colspan = count($table_headers);
			echo table_header($table_headers,$header_positions); 

		if ($db->num_rows($ret_products))
		{
				
		
			$cnt = 1; 
			while ($row_products = $db->fetch_array($ret_products))
			{
				$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';	 
				if($row_products['product_discount']>0)
				{
					$disctype	= ($row_products['product_discount_enteredasval']==0)?'%':'';
					$discval_arr= explode(".",$row_products['product_discount']);
					if($discval_arr[1]=='00')
						$discval = $discval_arr[0];
					else
						$discval = $row_products['product_discount'];
					$disc		= $discval.$disctype;
					if($row['product_discount_enteredasval']==1)
					{
					 $disc = display_price($disc);
					}
				}	
				else
					$disctype = $disc = '--';	
	
   ?>
   <tr>
	  <td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxproducts[]" value="<?php echo $row_products['id'];?>" /></td>
		<td width="48" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
		<td width="650" align="left" class="<?php echo $cls?>"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row_products['product_id'];?>" class="edittextlink" title="Edit"><?php echo stripslashes($row_products['product_name']);?></a></td>
		<td width="150" align="center" class="<?php echo $cls?>"><?php echo display_price($row_products['product_webprice']);?></td>
		<td width="440" align="center" class="<?php echo $cls?>"><?php echo display_price($row_products['product_costprice']);?></td>
		<td width="440" align="center" class="<?php echo $cls?>"><?php echo stripslashes($row_products['product_bulkdiscount_allowed']);?></td>
		<td width="499" align="center" class="<?php echo $cls?>"><?php echo $disc;?></td>
		<td width="499" align="center" class="<?php echo $cls?>"><?php echo $row_products['product_webstock']."(".$row_products['product_actualstock'].")"?></td>
		<td width="499" align="center" class="<?php echo $cls?>"><?php echo ($row_products['advert_display_product_hide']==1)?'Yes':'No'?></td>
	</tr>
	<?php
							}
						}
						else
						{
						?>
						 <tr>
		<td colspan="9" align="center" class="norecordredtext">&nbsp;No product Assigned to this Newsletter. 
								    <input type="hidden" name="products_norec" id="products_norec" value="1" /></td>
	</tr>
						
		<?php
						}
						?>	
		 <tr>
						   <td colspan="9" align="center" style="padding-top:10px;" >
						   
			<?PHP
			   if ($prodcont) {
		  	?>
				 <input name="continue" type="button" class="red" id="continue" value=" Continue " onclick="javascript:newsletter_continue()" />
			<?PHP 
				} 
			?>
						   &nbsp;
						   
						   </td>
    </tr>
	</table>
	</div>
	</td>
	</tr>			
      </table>
</form>	  
<?php
	
function show_prod_listing($alert)
	{
		global $db,$ecom_siteid,$ecom_themeid ;
		//# Retrieving the values of super admin from the table
	$sql 		= "SELECT * FROM general_settings_sites_listorders WHERE sites_site_id=".$ecom_siteid;
	$res_admin 	= $db->query($sql);
	$row 		= $db->fetch_array($res_admin);
		?>
		<div class="editarea_div">
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<?php
			if ($alert)
			{
		?>
        <tr>
          <td colspan="4" align="center" valign="middle" class="errormsg" ><?php echo $alert; ?></td>
          </tr>
		 <?php
		 	}
		 ?> 
		<tr valign="middle">
          <td colspan="4" align="left" class="sorttd" ><?=get_help_messages('LIST_ORDER_PROD_SUBMESS')?></td>
        </tr>
		<tr>
		  <td width="25%" align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
		  <td align="left" valign="middle" class="tdcolorgray"  >&nbsp;</td>
		  <td align="right" valign="middle" class="tdcolorgray"  >&nbsp;</td>
		  <td align="left" valign="middle" class="tdcolorgray"  >&nbsp;</td>
		  </tr>
		  <tr>
		  <td colspan="4" align="right" valign="middle">
		  <table width="40%" cellpadding="2" cellspacing="2" border="0">
		  <tr>
		  <td colspan="2" align="center" class="listingtablestyleB"><strong>Settings for values to be displayed in following Dropdown boxes</strong></td>
		  </tr>
		  <tr>
		    <td width="50%" align="center" class="listingtablestyleB">Maximum Value </td>
		    <td width="53%" align="center" class="listingtablestyleB">Increment Value </td>
		  </tr>
		  <tr>
		    <td align="center" class="listingtablestyleB"><input type="text" name="productlist_maxval" id="productlist_maxval" value="<?php echo $row['productlist_maxval']?>" style="text-align:center" size="6" /></td>
		    <td align="center" class="listingtablestyleB"><input type="text" name="productlist_interval" id="productlist_interval" value="<?php echo $row['productlist_interval']?>"  style="text-align:center" size="6"/></td>
		    </tr>
		  <tr>
		    <td colspan="2" align="center">&nbsp;</td>
		    </tr>
		  </table>
		  </td>
		  </tr>
        <tr>
          
         <td align="right" valign="middle" class="tdcolorgray">On Search result page			</td>
		 <td width="29%" align="left" valign="middle" class="tdcolorgray"  ><?php
					$catgrsort_arr = array('product_name'=>'Product Name','price'=>'Price','product_id'=>'Date Added');
					echo generateselectbox('product_orderfield_search',$catgrsort_arr,$row['product_orderfield_search']);
					echo "&nbsp;"; 
					$sort_ord = array('ASC'=>'Asc','DESC'=>'Desc');
					echo generateselectbox('product_orderby_search',$sort_ord,$row['product_orderby_search']); 
			?>
           <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_PRODUCT_LISTSEARCH')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
         <td width="26%" align="right" valign="middle" class="tdcolorgray"  >Products per page</td>
         <td width="20%" align="left" valign="middle" class="tdcolorgray"  ><!--<input name="product_maxcntperpage_search" type="text" id="product_maxcntperpage_search" value="<?php //echo $row['product_maxcntperpage_search'];?>" size="5" />-->
<select name="product_maxcntperpage_search" id="product_maxcntperpage_search"> 
		 <?php
							for ($ii=$row['productlist_interval'];$ii<=$row['productlist_maxval'];$ii+=$row['productlist_interval'])
							{
						?>
								<option value="<?php echo $ii?>" <?php echo ($row['product_maxcntperpage_search']==$ii)?'selected="selected"':''?>><?php echo $ii?></option>
						<?php	
							}
						?>
		 </select>&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_NOFPRD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<tr>
		  <td align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
		  <td align="left" valign="middle" class="tdcolorgray"  >&nbsp;</td>
		  <td align="right" valign="middle" class="tdcolorgray"  >&nbsp;</td>
		  <td align="left" valign="middle" class="tdcolorgray"  >&nbsp;</td>
		  </tr>
		<tr>
          
         <td align="right" valign="middle" class="tdcolorgray">On Best Seller page</td>
		 <td align="left" valign="middle" class="tdcolorgray"  ><?php
					$catgrsort_arr = array('custom'=>'Custom Ordering','product_name'=>'Product Name','price'=>'Price','product_id'=>'Date Added');
					echo generateselectbox('product_orderfield_bestseller',$catgrsort_arr,$row['product_orderfield_bestseller']); 
					echo "&nbsp;";
					$sort_ord = array('ASC'=>'Asc','DESC'=>'Desc');
					echo generateselectbox('product_orderby_bestseller',$sort_ord,$row['product_orderby_bestseller']); 
			?>
           <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_BESTSEL_SORT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		 <td align="right" valign="middle" class="tdcolorgray"  >Products per page</td>
		 <td align="left" valign="middle" class="tdcolorgray"  ><!--<input name="product_maxcntperpage_bestseller" type="text" id="product_maxcntperpage_bestseller" value="<?php //echo $row['product_maxcntperpage_bestseller'];?>" size="5" />-->
<select name="product_maxcntperpage_bestseller" id="product_maxcntperpage_bestseller"> 
		 <?php
							for ($ii=$row['productlist_interval'];$ii<=$row['productlist_maxval'];$ii+=$row['productlist_interval'])
							{
						?>
								<option value="<?php echo $ii?>" <?php echo ($row['product_maxcntperpage_bestseller']==$ii)?'selected="selected"':''?>><?php echo $ii?></option>
						<?php	
							}
						?>
		 </select>&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_BSTSELPRPAGE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		</tr>
		<tr>
		  <td align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
		  <td align="left" valign="middle" class="tdcolorgray"  >&nbsp;</td>
		  <td align="right" valign="middle" class="tdcolorgray"  >&nbsp;</td>
		  <td align="left" valign="middle" class="tdcolorgray"  >&nbsp;</td>
		  </tr>
		<tr>
         <td align="right"   valign="middle" class="tdcolorgray" nowrap="nowrap"><?php /*?>Maximum Bestseller Products Allowed Per page <?php */?></td>
		 <td  align="left" valign="middle" class="tdcolorgray" ><?php /*?><input name="product_maxcnt_bestseller" type="text" id="product_maxcnt_bestseller" value="<?php echo $row['product_maxcnt_bestseller'];?>" size="5" /><?php 
           &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_BSTSELPRPAGE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>*/?></td>
		 <td  align="right" valign="middle" class="tdcolorgray" >Limit Bestseller In Left/Right Panel To</td>
		 <td  align="left" valign="middle" class="tdcolorgray" ><input name="product_maxcnt_leftrightpanel" type="text" id="product_maxcnt_leftrightpanel" value="<?php echo $row['product_maxbestseller_in_component'];?>" size="5" />
&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_LEFTRIGHTBSTSELPRPAGE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		</tr>
		<tr>
		  <td align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
		  <td align="left" valign="middle" class="tdcolorgray"  >&nbsp;</td>
		  <td align="right" valign="middle" class="tdcolorgray"  >&nbsp;</td>
		  <td align="left" valign="middle" class="tdcolorgray"  >&nbsp;</td>
		  </tr>
		<tr>
         <td align="right" valign="middle" class="tdcolorgray"> Products in Product Shops</td>
		 <td align="left" valign="middle" class="tdcolorgray">
		 	<?php
					$prodshopsort_arr = array('custom'=>'Custom Ordering','product_name'=>'Product Name','price'=>'Price','product_id'=>'Date Added');
					echo generateselectbox('productshop_orderfield',$prodshopsort_arr,$row['productshop_orderfield']); 
					echo "&nbsp;";
					$sort_ord = array('ASC'=>'Asc','DESC'=>'Desc');
					echo generateselectbox('productshop_orderby',$sort_ord,$row['productshop_orderby']); 
			?>
           <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_SHOP_SORT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		 <td align="right" valign="middle" class="tdcolorgray">Products per page</td>
		 <td align="left" valign="middle" class="tdcolorgray"><!--<input name="product_maxcntperpage_shops" type="text" id="product_maxcntperpage_shops" value="<?php //echo $row['product_maxcntperpage_shops'];?>" size="5"/>-->
<select name="product_maxcntperpage_shops" id="product_maxcntperpage_shops"> 
		 <?php
						for ($ii=$row['productlist_interval'];$ii<=$row['productlist_maxval'];$ii+=$row['productlist_interval'])
							{
						?>
								<option value="<?php echo $ii?>" <?php echo ($row['product_maxcntperpage_shops']==$ii)?'selected="selected"':''?>><?php echo $ii?></option>
						<?php	
							}
						?>
		 </select>&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_SHOPPRDNO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		</tr>
		<tr>
		  <td align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
		  <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		  <td align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
		  <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		  </tr>
		  
		<tr>
          <td align="right" valign="middle" class="tdcolorgray">On Product Shelf page </td>
		  <td align="left" valign="middle" class="tdcolorgray"><?php
					$catgrsort_arr = array('custom'=>'Custom Ordering','product_name'=>'Product Name','price'=>'Price','product_id'=>'Date Added');
					echo generateselectbox('product_orderfield_shelf',$catgrsort_arr,$row['product_orderfield_shelf']); 
					echo "&nbsp;";
					$sort_ord = array('ASC'=>'Asc','DESC'=>'Desc');
					echo generateselectbox('product_orderby_shelf',$sort_ord,$row['product_orderby_shelf']); 
			?>
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_SHELF_SORT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  <td align="right" valign="middle" class="tdcolorgray">Products per page</td>
		  <td align="left" valign="middle" class="tdcolorgray"><!--<input name="product_maxcntperpage_shelf" type="text" id="product_maxcntperpage_shelf" value="<?php //echo $row['product_maxcntperpage_shelf'];?>" size="5"/>-->
		    <select name="product_maxcntperpage_shelf" id="product_maxcntperpage_shelf"> 
		 <?php
							for ($ii=$row['productlist_interval'];$ii<=$row['productlist_maxval'];$ii+=$row['productlist_interval'])
							{
						?>
								<option value="<?php echo $ii?>" <?php echo ($row['product_maxcntperpage_shelf']==$ii)?'selected="selected"':''?>><?php echo $ii?></option>
						<?php	
							}
						?>
		 </select>&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_SHELPRDNO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  </tr>
		  <tr>
		    <td align="right" valign="middle" class="tdcolorgray">Limit Shelf In Left/Right Panel To</td>
		    <td align="left" valign="middle" class="tdcolorgray"><input name="product_maxcnt_leftrightshelf" type="text" id="product_maxcnt_leftrightshelf" value="<?php echo $row['product_maxshelfprod_in_component'];?>" size="5" />
&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_LEFTRIGHTSHELFPRPAGE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		    <td align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
		    <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
	      </tr>
		  <tr>
		    <td align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
		    <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		    <td align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
		    <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
	      </tr>
		  <tr>
		    <td align="right" valign="middle" class="tdcolorgray">Products in Preorder Settings </td>
		    <td align="left" valign="middle" class="tdcolorgray"><?php
					$catgrsort_arr = array('custom'=>'Custom Ordering','product_name'=>'Product Name','price'=>'Price','product_id'=>'Date Added'); //'custom'=>'Custom Ordering',
					echo generateselectbox('product_orderfield_preorder',$catgrsort_arr,$row['product_orderfield_preorder']); 
					echo "&nbsp;";
					$sort_ord = array('ASC'=>'Asc','DESC'=>'Desc');
					echo generateselectbox('product_orderby_preorder',$sort_ord,$row['product_orderby_preorder']); 
			?>
           <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_PREORDER_SORT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		    <td align="right" valign="middle" class="tdcolorgray">Products in Preorder Per Page </td>
		    <td align="left" valign="middle" class="tdcolorgray">
			<select name="product_maxcntperpage_preorder" id="product_maxcntperpage_preorder"> 
		 <?php
							for ($ii=$row['productlist_interval'];$ii<=$row['productlist_maxval'];$ii+=$row['productlist_interval'])
							{
						?>
								<option value="<?php echo $ii?>" <?php echo ($row['product_maxcntperpage_preorder']==$ii)?'selected="selected"':''?>><?php echo $ii?></option>
						<?php	
							}
						?>
		 </select>
		 &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_PREORDERPRPAGE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	      </tr>
		  <tr> 
		    <td align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
		    <td colspan="2" align="right" valign="middle" class="tdcolorgray">Limit Preorder Products in Left/Right Panel To</td>
		    <td align="left" valign="middle" class="tdcolorgray"><input name="product_maxcnt_preorder_leftrightpanel" type="text" id="product_maxcnt_preorder_leftrightpanel" value="<?php echo $row['product_preorder_in_component'];?>" size="5" />
&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_LEFTRIGHTPREORDSELPRPAGE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	      </tr>
		  <?php /*?><tr>
		    <td align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
		    <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		    <td align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
		    <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
	      </tr>
		  <tr>
		    <td align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
		    <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		    <td align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
		    <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
	      </tr>
		  <tr>
		  <td align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
		  <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		  <td align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
		  <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		  </tr><? */?>
		  <tr>
          <td align="right" valign="middle" class="tdcolorgray">On Product Combo page </td>
		  <td align="left" valign="middle" class="tdcolorgray" colspan="3"><?php
					$catgrsort_arr = array('custom'=>'Custom Ordering','product_name'=>'Product Name','price'=>'Price','product_id'=>'Date Added');
					echo generateselectbox('product_orderfield_combo',$catgrsort_arr,$row['product_orderfield_combo']); 
					echo "&nbsp;";
					$sort_ord = array('ASC'=>'Asc','DESC'=>'Desc');
					echo generateselectbox('product_orderby_combo',$sort_ord,$row['product_orderby_combo']); 
			?>
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_COMBO_SORT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  </tr>
		<tr>
          <td align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
		  <td colspan="3" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		  </tr>
		
		<tr>
          
         <td align="right" valign="middle" class="tdcolorgray">On Product Listing (Category) page</td>
		 <td align="left" valign="middle" class="tdcolorgray"><?php
					$catgrsort_arr = array('custom'=>'Custom Ordering','product_name'=>'Product Name','price'=>'Price','product_id'=>'Date Added');
					echo generateselectbox('product_orderfield',$catgrsort_arr,$row['product_orderfield']); 
					echo "&nbsp;";
					$sort_ord = array('ASC'=>'Asc','DESC'=>'Desc');
					echo generateselectbox('product_orderby',$sort_ord,$row['product_orderby']); 
			?>
           <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_PRDLIST_SORT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		 <td align="right" valign="middle" class="tdcolorgray">Products per page</td>
		 <td align="left" valign="middle" class="tdcolorgray"><!--<input name="product_maxcntperpage" type="text" id="product_maxcntperpage" value="<?php echo $row['product_maxcntperpage'];?>" size="5" />-->
		 <select name="product_maxcntperpage" id="product_maxcntperpage"> 
		 <?php
							for ($ii=$row['productlist_interval'];$ii<=$row['productlist_maxval'];$ii+=$row['productlist_interval'])
							{
						?>
								<option value="<?php echo $ii?>" <?php echo ($row['product_maxcntperpage']==$ii)?'selected="selected"':''?>><?php echo $ii?></option>
						<?php	
							}
						?>
		 </select>
&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_PRDLIST_NO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		</tr>
		<tr>
          
         <td align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
		 <td colspan="3" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		</tr>
		<?php /*?><tr>
          
         <td align="right" valign="middle" class="tdcolorgray">On Favourite Product Listing (Myhome) page</td>
		 <td align="left" valign="middle" class="tdcolorgray"><?php
					$favsort_arr = array('product_name'=>'Product Name','price'=>'Price','product_id'=>'Date Added');
					echo generateselectbox('product_orderfield_favorite',$favsort_arr,$row['product_orderfield_favorite']); 
					echo "&nbsp;";
					$sort_ord = array('ASC'=>'Asc','DESC'=>'Desc');
					echo generateselectbox('product_orderby_favorite',$sort_ord,$row['product_orderby_favorite']); 
			?>
           <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_FAVPRDLIST_SORT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		 <td align="right" valign="middle" class="tdcolorgray">Products per page</td>
		 <td align="left" valign="middle" class="tdcolorgray"><!--<input name="product_maxcntperpage" type="text" id="product_maxcntperpage" value="<?php echo $row['product_maxcntperpage'];?>" size="5" />-->
		 <select name="product_maxcntperpage_favorite" id="product_maxcntperpage_favorite"> 
		 <?php
							for ($ii=$row['productlist_interval'];$ii<=$row['productlist_maxval'];$ii+=$row['productlist_interval'])
							{
						?>
								<option value="<?php echo $ii?>" <?php echo ($row['product_maxcntperpage_favorite']==$ii)?'selected="selected"':''?>><?php echo $ii?></option>
						<?php	
							}
						?>
		 </select>
&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_FAVPRDLIST_NO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		</tr>
           <?php */?>
       <tr>
         
          <td colspan="4" align="center" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
	   
</table>
</div>
        <div class="editarea_div">
				<table border="0" cellspacing="2" cellpadding="2" width="100%">
					 <tr>
         
          <td colspan="4" align="right" valign="middle" class="tdcolorgray"><input name="Submit" type="submit" class="red" id="Submit" value="Save Changes" /></td>
        </tr>
         </table>
         </div>
		<?
		
	}	
	function show_regcustomers_listing($alert)
	{
	global $db,$ecom_siteid,$ecom_themeid ;
	//# Retrieving the values of super admin from the table
	$sql 		= "SELECT * FROM general_settings_sites_listorders WHERE sites_site_id=".$ecom_siteid;
	$res_admin 	= $db->query($sql);
	$row 		= $db->fetch_array($res_admin);
	?>
	<div class="editarea_div">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<?php
			if ($alert)
			{
		?>
        <tr>
          <td colspan="4" align="center" valign="middle" class="errormsg" ><?php echo $alert; ?></td>
      </tr>
		 <?php
		 	}
		 ?> 
	<tr>
          <td colspan="4" align="left" valign="middle" class="sorttd" ><?=get_help_messages('LIST_REGCUST_SUBMESS')?> </td>
      </tr>
        <tr>
          <td align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
        <tr>
          <td align="right" valign="middle" class="tdcolorgray">Sort My Enquiries Review by </td>
          <td align="left" valign="middle" class="tdcolorgray">
		  <?php
				$enquiryview_ord	= array('enquiry_date'=>'Enquiry Date');
				echo generateselectbox('enquiry_orderfield_settings',$enquiryview_ord,$row['enquiry_orderfield_settings']);
				echo "&nbsp;";
				$enquiryview_ord_by	= array('ASC'=>'Asc','DESC'=>'Desc');
			 	echo generateselectbox('enquiry_orderby_settings',$enquiryview_ord_by,$row['enquiry_orderby_settings']);
			?>
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_ENQUIRYRW_SORT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td align="right" valign="middle" class="tdcolorgray">My Enquiries per page </td>
          <td align="left" valign="middle" class="tdcolorgray"><input name="enquiry_maxcntperpage" type="text" id="enquiry_maxcntperpage" value="<?php echo $row['enquiry_maxcntperpage'];?>" size="5" />
&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_ENQUIRYRW_CNT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
        <tr>
          <td align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
        <tr>
          <td align="right" valign="middle" class="tdcolorgray">Sort My Orders Review By </td>
          <td align="left" valign="middle" class="tdcolorgray">
		   <?php
				$orderview_ord	= array('order_date'=>'Order Date');
				echo generateselectbox('orders_orderfield_settings',$orderview_ord,$row['orders_orderfield_settings']);
				echo "&nbsp;";
				$orderview_ord_by	= array('ASC'=>'Asc','DESC'=>'Desc');
			 	echo generateselectbox('orders_orderby_settings',$orderview_ord_by,$row['orders_orderby_settings']);
			?>
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_SORT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td align="right" valign="middle" class="tdcolorgray">My Orders listingPer page </td>
          <td align="left" valign="middle" class="tdcolorgray"><input name="orders_maxcntperpage" type="text" id="orders_maxcntperpage" value="<?php echo $row['orders_maxcntperpage'];?>" size="5" />
&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_CNT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
        <tr>
          <td align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
        <tr>
          <td align="right" valign="middle" class="tdcolorgray">Sort My Order Enquiries Review by </td>
          <td align="left" valign="middle" class="tdcolorgray"><?php
				$orderenquiryview_ord	= array('query_date'=>'Query Date');
				echo generateselectbox('orders_orderfield_enquiry',$orderenquiryview_ord,$row['orders_orderfield_enquiry']);
				echo "&nbsp;";
				$orderenquiry_ord_by	= array('ASC'=>'Asc','DESC'=>'Desc');
			 	echo generateselectbox('orders_orderby_enquiry',$orderenquiry_ord_by,$row['orders_orderby_enquiry']);
			?>
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_ENQ_SORT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td align="right" valign="middle" class="tdcolorgray">My Orders Enquiries Listing per page </td>
          <td align="left" valign="middle" class="tdcolorgray"><input name="orders_maxcntperpage_enquiry" type="text" id="orders_maxcntperpage_enquiry" value="<?php echo $row['orders_maxcntperpage_enquiry'];?>" size="5" />
&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_ENQ_CNT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
        <tr>
          <td align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
        <tr>
          <td align="right" valign="middle" class="tdcolorgray">Sort by Order Enquiry Posts Review By </td>
          <td align="left" valign="middle" class="tdcolorgray"><?php
				$orderenquirypostview_ord	= array('post_date'=>'Post Date');
				echo generateselectbox('orders_orderfield_enqposts',$orderenquirypostview_ord,$row['orders_orderfield_enqposts']);
				echo "&nbsp;";
				$orderenquirypost_ord_by	= array('ASC'=>'Asc','DESC'=>'Desc');
			 	echo generateselectbox('orders_orderby_enqposts',$orderenquirypost_ord_by,$row['orders_orderby_enqposts']);
			?>
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_POST_SORT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td align="right" valign="middle" class="tdcolorgray"> Order Enquiry Posts Listing per page </td>
          <td align="left" valign="middle" class="tdcolorgray"><input name="orders_maxcntperpage_enqposts" type="text" id="orders_maxcntperpage_enqposts" value="<?php echo $row['orders_maxcntperpage_enqposts'];?>" size="5" />
&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_POST_CNT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
         <tr>
          <td align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
        <tr>
          
         <td align="right" valign="middle" class="tdcolorgray">On Favourite Product Listing (Myhome) page</td>
		 <td align="left" valign="middle" class="tdcolorgray"><?php
					$favsort_arr = array('product_name'=>'Product Name','price'=>'Price','product_id'=>'Date Added');
					echo generateselectbox('product_orderfield_favorite',$favsort_arr,$row['product_orderfield_favorite']); 
					echo "&nbsp;";
					$sort_ord = array('ASC'=>'Asc','DESC'=>'Desc');
					echo generateselectbox('product_orderby_favorite',$sort_ord,$row['product_orderby_favorite']); 
			?>
           <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_FAVPRDLIST_SORT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		 <td align="right" valign="middle" class="tdcolorgray">Products per page</td>
		 <td align="left" valign="middle" class="tdcolorgray"><!--<input name="product_maxcntperpage" type="text" id="product_maxcntperpage" value="<?php echo $row['product_maxcntperpage'];?>" size="5" />-->
		 <select name="product_maxcntperpage_favorite" id="product_maxcntperpage_favorite"> 
		 <?php
							for ($ii=$row['productlist_interval'];$ii<=$row['productlist_maxval'];$ii+=$row['productlist_interval'])
							{
						?>
								<option value="<?php echo $ii?>" <?php echo ($row['product_maxcntperpage_favorite']==$ii)?'selected="selected"':''?>><?php echo $ii?></option>
						<?php	
							}
						?>
		 </select>
&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_FAVPRDLIST_NO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		</tr>
		 <tr>
		 <td colspan="2" align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td align="right" valign="middle" class="tdcolorgray"> Pay on Account statements per page</td>
          <td align="left" valign="middle" class="tdcolorgray"><input name="payon_maxcntperpage_statements" type="text" id="payon_maxcntperpage_statements" value="<?php echo $row['payon_maxcntperpage_statements'];?>" size="5" />
		
		</tr>
		<tr>
		    <td align="right" valign="middle" class="tdcolorgray" colspan="3">Limit for products In Favorite Categories and Recently Purchased Products in My home page</td>
		    <td align="left" valign="middle" class="tdcolorgray"><input name="product_limit_homepage_favcat_recent" type="text" id="product_limit_homepage_favcat_recent" value="<?php echo $row['product_limit_homepage_favcat_recent'];?>" size="5" />
&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_FAVCATEGORY_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<tr>
		    <td align="right" valign="middle" class="tdcolorgray" colspan="3">Limit for products In Favorite Categories(ShowAll)</td>
		    <td align="left" valign="middle" class="tdcolorgray"><input name="product_maxcnt_fav_category" type="text" id="product_maxcnt_fav_category" value="<?php echo $row['product_maxcnt_fav_category'];?>" size="5" />
&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_FAVCATEGORY_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<tr>
		    <td align="right" valign="middle" class="tdcolorgray" colspan="3">Limit for recently purchased products(ShowAll)</td>
		    <td align="left" valign="middle" class="tdcolorgray"><input name="product_maxcnt_recent_purchased" type="text" id="product_maxcnt_recent_purchased" value="<?php echo $row['product_maxcnt_recent_purchased'];?>" size="5" />
&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_RECENT_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>

        </tr>
		 <tr>
          <td colspan="4" align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
        
</table>
</div>
<div class="editarea_div">
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr>
         
          <td colspan="4" align="right" valign="middle" class="tdcolorgray"><input name="Submit" type="button" class="red" id="Submit" value="Save Changes" onclick="save_settings('regcust')" /></td>
        </tr>
        </table>
	</div>
	<?
		
	}
	function show_others_listing($alert)
	{
	global $db,$ecom_siteid,$ecom_themeid ;
	$sql 		= "SELECT * FROM general_settings_sites_listorders WHERE sites_site_id=".$ecom_siteid;
	$res_admin 	= $db->query($sql);
	$row 		= $db->fetch_array($res_admin);
	?>        <div class="editarea_div">
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<?php
			if ($alert)
			{
		?>
        <tr>
          <td colspan="4" align="center" valign="middle" class="errormsg" ><?php echo $alert; ?></td>
          </tr>
		 <?php
		 	}
		 ?> 
	  <tr>
          <td colspan="4" align="left" valign="middle" class="sorttd" ><?=get_help_messages('LIST_OTHERS_CATSHOP_SUBMESS')?></td>
        </tr>
		 <tr>
          <td colspan="4" align="left" valign="middle" class="sorttd" ><b>Category Listing Order within category group </b></td>
        </tr>
        <tr>
          
          <td width="33%" align="right" valign="middle" class="tdcolorgray">
		 
Sort By  </td>
          <td colspan="3" align="left" valign="middle" class="tdcolorgray"  ><?php
				$catord_fld	= array('custom'=>'Custom Ordering','cname'=>'Category Name');
				echo generateselectbox('category_orderfield',$catord_fld,$row['category_orderfield']);
				echo "&nbsp;";
				$catord_order	= array('ASC'=>'Asc','DESC'=>'Desc');
			 	echo generateselectbox('category_orderby',$catord_order,$row['category_orderby']);
			?>
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_CATEGORY_SORT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<tr>
          <td colspan="4" align="left" valign="middle" class="sorttd" ><b>Shop Listing Order within Shop Group </b></td>
        </tr>
        <tr>
          
          <td width="33%" align="right" valign="middle" class="tdcolorgray">
		 
Sort By  </td>
          <td colspan="3" align="left" valign="middle" class="tdcolorgray"  >
		  <?php
				$prodshopord_fld	= array('custom'=>'Custom Ordering','shopbrand_name'=>'Shop Name');
				echo generateselectbox('shopbybrand_shops_orderfield',$prodshopord_fld,$row['shopbybrand_shops_orderfield']);
				echo "&nbsp;";
				$prodshopord_order	= array('ASC'=>'Asc','DESC'=>'Desc');
			 	echo generateselectbox('shopbybrand_shops_orderby',$prodshopord_order,$row['shopbybrand_shops_orderby']);
			?>
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_SHOP_SORT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		
		
		
		<tr>
          <td colspan="4" align="left" valign="middle" class="sorttd" ><b>Review Listing </b></td>
        </tr>
        <tr>
          <td align="right" valign="middle" class="tdcolorgray">Sort Product Review by </td>
          <td width="20%" align="left" valign="middle" class="tdcolorgray"  ><?php
				$prodreview_ord	= array('review_date'=>'Review Date','review_rating'=>'Review Rating');
				echo generateselectbox('prodreview_ord_fld',$prodreview_ord,$row['prodreview_ord_fld']);
				echo "&nbsp;";
				$prodreview_ord_by	= array('ASC'=>'Asc','DESC'=>'Desc');
			 	echo generateselectbox('prodreview_ord_orderby',$prodreview_ord_by,$row['prodreview_ord_orderby']);
			?>
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_PRODUCTREVIEW_SORT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td width="27%" align="right" valign="middle" class="tdcolorgray"  >Product Reviews per page </td>
          <td width="20%" align="left" valign="middle" class="tdcolorgray"  ><input name="productreview_maxcntperpage" type="text" id="productreview_maxcntperpage" value="<?php echo $row['productreview_maxcntperpage'];?>" size="5" />
&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_PRODREVIEW_CNT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
        <tr>
          
          <td width="33%" align="right" valign="middle" class="tdcolorgray">
		 
Sort Site Review By  </td>
          <td align="left" valign="middle" class="tdcolorgray"  ><?php
				$sitereview_ord	= array('review_date'=>'Review Date','review_rating'=>'Review Rating');
				echo generateselectbox('sitereview_ord_fld',$sitereview_ord,$row['sitereview_ord_fld']);
				echo "&nbsp;";
				$sitereview_ord_by	= array('ASC'=>'Asc','DESC'=>'Desc');
			 	echo generateselectbox('sitereview_ord_orderby',$sitereview_ord_by,$row['sitereview_ord_orderby']);
			?>
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_SITEREVIEW_SORT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td align="right" valign="middle" class="tdcolorgray"  >Site Reviews per page </td>
          <td align="left" valign="middle" class="tdcolorgray"  ><input name="sitereview_maxcntperpage" type="text" id="sitereview_maxcntperpage" value="<?php echo $row['sitereview_maxcntperpage'];?>" size="5" />
&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ORDER_SITEREVIEW_CNT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="sorttd" colspan="4"><strong>Saved Search</strong></td>
        </tr>
        <tr>
          <td align="right" valign="middle" class="tdcolorgray">Number of Saved Search Keywords to be displayed in website</td>
          <td align="left" valign="middle" class="tdcolorgray">
          <?php
          $saved_search_interval = 5;
		  $saved_search_mincnt_val = 25;
		  $saved_search_maxcnt_val = 75;
		  ?>
          <select name="saved_search_display_cnt" id="saved_search_display_cnt"> 
			<?php
                for ($ii=$saved_search_mincnt_val;$ii<=$saved_search_maxcnt_val;$ii+=$saved_search_interval)
                {
            ?>
                    <option value="<?php echo $ii?>" <?php echo ($row['saved_search_display_cnt']==$ii)?'selected="selected"':''?>><?php echo $ii?></option>
            <?php	
                }
            ?>
		 </select>
          </td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
		 <tr>
          <td colspan="4" align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
        </table>
        </div>       
        <div class="editarea_div">
  <table width="100%" border="0" cellspacing="2" cellpadding="2">	
        <tr>
         
          <td colspan="4" align="right" valign="middle" class="tdcolorgray"><input name="Submit" type="button" class="red" id="Submit" value="Save Changes" onclick="save_settings('others_catshop')"/></td>
        </tr>
		</table>
		</div>
	<?
	
	}
	?>

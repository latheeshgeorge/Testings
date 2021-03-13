<?php
	// ###############################################################################################################
	//Function which holds the display logic of export or import select box
    //called using ajax;
	// ###############################################################################################################
    function show_importexport_selectbox($mod)
	{
		global $db,$ecom_siteid;
		if ($mod=='export')
		{
		if($_REQUEST['cur_what']){

			if($_REQUEST['cur_what']=='cat')
			{
			   $exp_type ='Categories';
			}
			elseif($_REQUEST['cur_what']=='cust')
			{
			   $exp_type ='Customers';
			}
			elseif($_REQUEST['cur_what']=='prod')
			{
			   $exp_type ='Products';
			}
			elseif($_REQUEST['cur_what']=='order')
			{
			   $exp_type ='Orders';
			}
			elseif($_REQUEST['cur_what']=='shop')
			{
			   $exp_type ='Shop By Brand';
			}
			elseif($_REQUEST['cur_what']=='giftvoucher')
			{
			   $exp_type ='Gift Voucher';
			}
			elseif($_REQUEST['cur_what']=='newsletter_customers')
			{
			   $exp_type ='Newsletter Customers';
			}
			$mess = get_help_messages('SELECT_EXPORT_OPTION_RROM');
			$mess2 =str_replace("$",$exp_type,$mess);
			?>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="20%" align="left"><div class="import_export_what_div" ><div>Export What?</div>
				<div><strong>:&nbsp;&nbsp;<?=$exp_type?></strong>
               &nbsp;<a href="#" onmouseover ="ddrivetip('<?=$mess2?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> 
				<input type="hidden" name="export_what_sub" id="export_what_sub" value="<?=$_REQUEST['cur_what']?>" /></div>
				</td>
				<td width="65%" align="left" class="redtext"> <? if($_REQUEST['cur_what']){ ?><?=$exp_type?> Selected From <?=$exp_type?> Listing <? }?></td></tr>
            </table>
			<?php
			}
			else
			{
	?>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" >
              <tr>
                <td width="20%" align="left" ><div class="import_export_what_div" ><div>Export What? </div>
				<div><select name="export_what" id="export_what" onchange="call_ajax_showlistall('subexport_select','submod','export')">
                  <option value="">-- Select --</option>
				  <option value="cat" >Categories</option>
                  <option value="prod" >Products</option>
                  <option value="shop" >Shop By Brand</option>
                  <option value="cust" >Customers</option>
                  <option value="newsletter_customers" >Newsletter Customers</option>
				  <option value="order" >Orders</option>
				  <option value="giftvoucher">Gift Voucher</option>
                </select>&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SELECT_EXPORT_OPTION')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></div></div>
				</td>
              </tr>
            </table>
<?
		  }
		}
		elseif ($mod=='import')
		{
		if($_REQUEST['cur_what'])
			{
			?>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="20%" align="left"  ><div class="import_export_what_div" ><div>Import What? </div>
					<div><select name="import_what_sub" id="import_what_sub" onchange="call_ajax_showlistall('main_select',this.value)">
					  <option value="">-- Select --</option>
					  <option value="cat" <?php echo ($_REQUEST['cur_what']=='cat')?'selected':''?>>Categories</option>
					  <option value="prod"<?php echo ($_REQUEST['cur_what']=='prod')?'selected':''?>>Products</option>
					  <option value="shop" <?php echo ($_REQUEST['cur_what']=='shop')?'selected':''?>>PShop By Brand</option>
					  <option value="cust" <?php echo ($_REQUEST['cur_what']=='cust')?'selected':''?> >Customers</option>
					  <option value="newsletter_customers" <?php echo ($_REQUEST['cur_what']=='newsletter_customers')?'selected':''?> >Newsletter Customers</option>
					</select>
				&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SELCT_IMPORT_OPTION')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>				
				
				 </div></div>
				</td>
             			  </tr>
            </table>
			<?php
			}
			else{
		?>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="20%" align="left"  ><div class="import_export_what_div" ><div>Import What? </div>
				<div>
					<select name="import_what" id="import_what" onchange="call_ajax_showlistall('subimport_select')">
					  <option value="">-- Select --</option>
					  <option value="cat" <?php echo ($_REQUEST['cur_what']=='cat')?'selected':''?>>Categories</option>
					  <option value="prod"<?php echo ($_REQUEST['cur_what']=='prod')?'selected':''?>>Products</option>
					  <option value="shop" <?php echo ($_REQUEST['cur_what']=='shop')?'selected':''?>>Shop By Brand</option>
					  <option value="cust" <?php echo ($_REQUEST['cur_what']=='cust')?'selected':''?> >Customers</option>
					  <option value="newsletter_customers" <?php echo ($_REQUEST['cur_what']=='newsletter_customers')?'selected':''?> >Newsletter Customers</option>
					</select>
				&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SELCT_IMPORT_OPTION')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> 		</div>	</div>	</td>
              </tr>
            </table>
<?php	
			}	
		}
	}
	// ###############################################################################################################
	//Function which holds the display logic to show the fields to be exported when 
    //called using ajax;
	// ###############################################################################################################
    function show_export_fields($mod,$export_from)
	{
		global $outformat_arr,$cat_field_arr,$cat_sort_arr,$prod_field_arr,$prod_sort_arr,$shop_field_arr,$shop_sort_arr
				,$cust_field_arr,$cust_sort_arr,$prod_special_arr,$shop_special_arr,$db,$order_field_arr,$order_sort_arr,$id_arrays,$order_special_arr,
				$order_product_arr,$order_special_gift_arr,$order_gift_arr,$giftvoucher_field_arr,$giftvoucher_sort_arr,$giftvoucher_special_cust_arr ,$giftvoucher_special_arr,
				$cust_special_arr,$ecom_siteid,$newscust_field_arr,$newscust_sort_arr;
				$prevent = 0;
				if($export_from=='export_from')
				{
				global $ids;
				}
			$field_arr 				= array();
		switch($mod)
		{
			case 'cat': // case of categories
				$field_arr			= $cat_field_arr;
				$sort_arr			= $cat_sort_arr	;
				$exp_type			= 'export_category';
				if($export_from=='export_main'){
				$show_category		= 1;	
				$show_categorygroup = 1;
				}
			break;
			case 'prod': // case of products
				$field_arr			= $prod_field_arr;
				$sort_arr			= $prod_sort_arr;
				$exp_type			= 'export_product';
				$cur_special_arr	= $prod_special_arr;
				if($export_from=='export_main'){
				$show_product_category		= 1;
				}
				$prevent = 1;
			break;
			case 'shop': // case of product shops
				$field_arr			= $shop_field_arr;
				$sort_arr			= $shop_sort_arr;
				$cur_special_arr	= $shop_special_arr;
				$exp_type			= 'export_shops';
				if($export_from=='export_main'){
				$show_shop  		=1;
				$show_shopgroup		=1;
				}
			break;
			case 'cust': // case of customers
				$field_arr			= $cust_field_arr;
				$sort_arr			= $cust_sort_arr;
				$exp_type			= 'export_cust';
				$prevent = 1;
			break;
			case 'newsletter_customers': // case of newsletter customers
				$field_arr			= $newscust_field_arr;
				$sort_arr			= $newscust_sort_arr;
				$exp_type			= 'export_newscust';
				$prevent = 1;
			break;
			case 'order': // case of customers
				$field_arr			= $order_field_arr;
				$sort_arr			= $order_sort_arr;
				$exp_type			= 'export_order';
				$cur_special_arr	= $order_special_arr;
				$cur_gift_arr		= $order_special_gift_arr;
				if(!$_REQUEST['ids'])
				{
				$show_order_date 	= 1;
				}
			break;
			case 'giftvoucher': // case of customers
				$field_arr			= $giftvoucher_field_arr;
				$sort_arr			= $giftvoucher_sort_arr;
				$exp_type			= 'export_giftvoucher';
				$cur_special_arr	= $giftvoucher_special_arr;
				$cust_special_arr   = $giftvoucher_special_cust_arr;
			break;
		};
		if(count($cur_special_arr))
		{
			foreach($cur_special_arr as $k=>$v)
			{
				$field_arr[$k] = $v;
			}
		}
		if(count($cur_gift_arr))
		{
			foreach($cur_gift_arr as $k=>$v)
			{
				$field_arr[$k] = $v;
			}
		}
		
		if(count($field_arr))
		{
			if($ecom_siteid==105 || $ecom_siteid==104)
			{
				 if($prevent==1 && $_SESSION['console_id']!=26054)
				 {
			  ?>
					<span class="redtext"><b>You are not authorized to do this action !!! </b></span>
			  <?php
					exit;
				}
			}
		?><div class="import_export_details_div" >
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<?PHP if($show_category==1 || $show_categorygroup==1) { ?>
			<? if($show_categorygroup==1)
					{ ?> 
			  <tr>
			  <td width="41%" align="left"><div> Select the Category Menu </div>
			  <div> 
			  <?php
			  $catgroup_arr = array(0=>'Any');
				$sql_catgroup ="SELECT catgroup_id,catgroup_name FROM product_categorygroup WHERE sites_site_id=$ecom_siteid";
				//echo $sql_shopgroup;
				$sql_catres    = $db->query($sql_catgroup);
				while($row_catgroup = $db->fetch_array($sql_catres))
				{
				  $id 		= $row_catgroup['catgroup_id'];
				  $catgroup_arr[$id] = $row_catgroup['catgroup_name'];
				}
				//$shop_arr = generate_shop_tree(0,0,false,false);
				echo generateselectbox('sel_catgroup_id', $catgroup_arr,0,'','','');
		  		?>&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SELCT_EXPORT_PARENT_CATGROUP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></div>			  </td>
			  </tr>
			  <?php
			  }
			  ?>
			  <tr>
			    <td colspan="2" align="left" valign="top"><?php
				if($show_category==1)
				{
			?>   Select the Parent Category <?php
				} ?></td>
		      </tr>
			  <tr>
			    <td colspan="2" align="left" valign="top" > <?php
					if($show_category==1)
				{
							$cat_arr = generate_category_tree(0,0,false,false,false);
							echo generateselectbox('sel_category_id[]',$cat_arr,'-1','','',5);
					  ?>			      &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SELCT_EXPORT_PARENT_CAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			    <? } ?>					  </td>
		        
	          </tr>
			  <?php
				}		
				if($show_product_category==1)
				{
			?>
			  <tr>
			    <td colspan="2" align="left" valign="top"><div>Select the Category 
		 <span class="ex_all"> <span class="ex_all_a"><input type="checkbox" name="select_allfields_category" id="select_allfields_category" onclick="handle_select_allcategory('select_category')" /></span> <span class="ex_all_b">
Select All&nbsp;</span></span><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SELCT_EXPORT_ALLFIELD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></div></td>
		      </tr>
			  <tr>
			    <td colspan="2" align="left" valign="top" >
			    <?php
							$cat_arr = generate_category_tree(0,0,false,false,false);
							echo generateselectbox('sel_category_id[]',$cat_arr,'-1','','',5);
					  ?>				  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SELCT_EXPORT_PROD_CAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
		      </tr>
			<?php
				}
				if($show_shop==1 || $show_shopgroup==1) {
			?>
			<?PHP 	if($show_shopgroup==1)
			{ ?>
          <tr>
		  <td align="left">
		 <div> Select the Shop Menu</div> 
		 <div> <?php 
		  $shopgroup_arr = array(0=>'-- Any --');
									$sql_shopgroup ="SELECT shopbrandgroup_id,shopbrandgroup_name FROM product_shopbybrand_group WHERE sites_site_id= $ecom_siteid";
									//echo $sql_shopgroup;
									$sql_res    = $db->query($sql_shopgroup);
									while($row_group = $db->fetch_array($sql_res))
									{
									  $id 		= $row_group['shopbrandgroup_id'];
									  $shopgroup_arr[$id] = $row_group['shopbrandgroup_name'];
									}
									//$shop_arr = generate_shop_tree(0,0,false,false);
									echo generateselectbox('sel_shopgroup_id', $shopgroup_arr,0,'','','');
							  ?>
&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SELCT_EXPORT_SHOPGROUP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></div>
		  </td>
		  </tr>
		  <?php
		  }
		  ?>
		  <tr>
            <td colspan="2" align="left" valign="top"><?PHP 
			if($show_shop==1)
				{ ?>Select the Parent Shop <? } ?></td>
          </tr>
          <tr>
            <td colspan="2" align="left" valign="top" >
			          <?php
					  if($show_shop==1) {
							$shop_arr = generate_shop_tree(0,0,false,false);
							echo generateselectbox('sel_shop_id[]',$shop_arr,-1,'','',5);
					  ?>              &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SELCT_EXPORT_PARENT_SHOP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>       
					  <? } ?>					  </td>
				<td width="1%"></td>
          </tr>

          <?php
		  }
				if($show_order_date==1)
				{
				?>
				<tr>
				  <td colspan="2"  align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td >Between date </td>
                      <td ><input name="ord_fromdate" class="textfeild" type="text" size="12" value="<?php echo $_REQUEST['ord_fromdate']?>" />
                    <a href="javascript:show_calendar('frm_importexport.ord_fromdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a> and
                    <input name="ord_todate" class="textfeild" id="ord_todate" type="text" size="12" value="<?php echo $_REQUEST['ord_todate']?>" />
                    <a href="javascript:show_calendar('frm_importexport.ord_todate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a></td>
                    </tr>
                  </table></td>
			  </tr>
				
				<?
				}
			?>		
			    <tr>
			      <td colspan="2" align="left" valign="top" nowrap="nowrap"><div>Select the Fields to be exported
		<span class="ex_all"> <span class="ex_all_a"> <input type="checkbox" name="select_allfields" id="select_allfields" onclick="handle_select_allfields('export_fields')" /></span>
		          <span  class="ex_all_a">All Fields Required &nbsp;</span></span><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SELCT_EXPORT_ALLFIELD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></div></td>
	          </tr>
		      <tr>
			<td colspan="2" align="left" valign="top" nowrap="nowrap" >
			<select name="export_fields[]" id="export_fields[]" multiple="multiple" size="8">
			<?php
				foreach($field_arr as $k=>$v)
				{
			?>
					<option value="<?php echo $k?>"><?php echo $v?></option>
			<?php
				}		
			?>
			</select>			&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SELCT_EXPORT_FIELDS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> 	</td>
			</tr>
			  <tr>
			    <td colspan="2" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td ><div>Sort By </div>
                  <div><select name="export_sort" id="export_sort" size="1">
                      <?php
				foreach($sort_arr as $k=>$v)
				{
			?>
                      <option value="<?php echo $k?>"><?php echo $v?></option>
                      <?php
				}		
			?>
                    </select>
&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SELCT_EXPORT_SORT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></div></td>
                  </tr>
                  <tr>
                    <td><div>Output Format </div>
                   <div><select name="export_output_format" id="export_output_format" size="1">
                <?php
				foreach($outformat_arr as $k=>$v)
				{
			?>
                <option value="<?php echo $k?>"><?php echo $v?></option>
                <?php
				}		
			?>
              </select>
		      &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SELCT_EXPORT_OUTPUT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></div></td>
                  </tr>
                </table></td>
		      </tr>
			  
			<tr>
			 
			  <td align="left" class="export_btn"><input name="export_Submit" type="submit" id="export_Submit" value="Export" class="red" onclick="return check_fields('export_Submit','<?=$mod?>')"/></td>
			  </tr>
			</table>
            </div>
			<input type="hidden" name="ids" id="ids" value="<?=$ids?>" />
			<input type="hidden" name="mod" id="mod" value="export" />
			<input type="hidden" name="cur_mod" id="cur_mod" value="<?php echo $exp_type?>" />
		<?php
		}
	}
	// ###############################################################################################################
	//Function which holds the display logic to show the fields to be exported when 
    //called using ajax;
	// ###############################################################################################################
    function show_import_fields($mod)
	{
		
		//echo $mod;
		global $outformat_arr,$cat_field_arr,$cat_sort_arr,$prod_field_arr,$prod_sort_arr,$shop_field_arr,$shop_sort_arr
				,$cust_field_arr,$cust_sort_arr,$prod_special_arr,$shop_special_arr,$db,$cat_importfield_arr,$cust_importfield_arr
				,$prod_importfield_arr,$importshop_field_arr,$ecom_siteid,$newscust_importfield_arr,$newscust_sort_arr;
		$field_arr 				= array();
		switch($mod)
		{
			case 'cat': // case of categories
				$field_arr			= $cat_importfield_arr;
				$sort_arr			= $cat_sort_arr	;
				$imp_type			= 'import_category';
				$show_category		= 1;	
				$show_categorygroup		= 1;
			break;
			case 'prod': // case of products
				$field_arr			= $prod_importfield_arr;
				$sort_arr			= $prod_sort_arr;
				$imp_type			= 'import_product';
				$cur_special_arr	= $prod_special_arr;
				$show_category_prod		= 1;
			break;
			case 'shop': // case of product shops
				$field_arr			= $importshop_field_arr;
				$sort_arr			= $shop_sort_arr;
				$cur_special_arr	= $shop_special_arr;
				$imp_type			= 'import_shops';
				$show_shop  		=1;
				$show_shopgroup     =1; 
			break;
			case 'cust': // case of customers
				$field_arr			= $cust_importfield_arr;
				$sort_arr			= $cust_sort_arr;
				$imp_type			= 'import_cust';
			break;
			case 'newsletter_customers': // case of newsletter customers
				$field_arr			= $newscust_importfield_arr;
				$sort_arr			= $newscust_sort_arr;
				$imp_type			= 'import_newscust';
			break;
		};
		if(count($cur_special_arr))
		{
			foreach($cur_special_arr as $k=>$v)
			{
				$field_arr[$k] = $v;
			}
		}
		if(count($field_arr))
		{
		?>
        <div class="import_export_details_div" >
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
         	<tr>
			<td colspan="3">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
		    <tr>
            <td  align="right"> <input name="template_Submit" type="submit" id="template_Submit" value="Download Template" class="red"/>
			 <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('FILE_TEMPLATE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			</td>
          </tr>
		  <?php
				if($show_category==1)
				{
			?>
        
          <tr>
            <td align="left" valign="top" ><div>Select the Parent Category</div>
      <div>      <?php
							$cat_arr = generate_category_tree(0,0,false,false);
							echo generateselectbox('sel_category_id',$cat_arr,0,'','','');
					  ?>  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SELCT_PARENT_CAT_OPTION')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>  </div></td>
          </tr>
          <?php
				}
			
				if($show_shop==1)
				{
			?>
          <tr>
            <td align="left" valign="top"><div>Select the Parent Shop</div>
          <div> <?php
							$shop_arr = generate_shop_tree(0,0,false,false);
							echo generateselectbox('sel_shop_id',$shop_arr,0,'','','');
					  ?>    <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SELECT_SHOP_PARENT_IMPORT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>      </div>   </td>
          </tr>
          <?php
				}
				if($show_shopgroup==1)
				{
					?>
          <tr>
            <td align="left" valign="top" ><div>Select the Shop Menu</div>
          <div> <?php
									$sql_shopgroup ="SELECT shopbrandgroup_id,shopbrandgroup_name FROM product_shopbybrand_group WHERE sites_site_id =$ecom_siteid";
									//echo $sql_shopgroup;
									$sql_res    = $db->query($sql_shopgroup);
									while($row_group = $db->fetch_array($sql_res))
									{
									  $id 		= $row_group['shopbrandgroup_id'];
									  $shopgroup_arr[$id] = $row_group['shopbrandgroup_name'];
									}
									//$shop_arr = generate_shop_tree(0,0,false,false);
									echo generateselectbox('sel_shopgroup_id[]', $shopgroup_arr,0,'','',5);
							  ?>            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SELECT_SHOP_GROUP_IMPORT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></div></td>
          </tr>
          <?php
				}
				if($show_categorygroup==1)
				{
					?>
          <tr>
            <td align="left" valign="top" width="21%"><div>Select the Category Menu </div>
          <div> <?php
									$sql_catgroup ="SELECT catgroup_id,catgroup_name FROM product_categorygroup WHERE sites_site_id = $ecom_siteid";
									//echo $sql_shopgroup;
									$sql_catres    = $db->query($sql_catgroup);
									while($row_catgroup = $db->fetch_array($sql_catres))
									{
									  $id 		= $row_catgroup['catgroup_id'];
									  $catgroup_arr[$id] = $row_catgroup['catgroup_name'];
									}
									//$shop_arr = generate_shop_tree(0,0,false,false);
									echo generateselectbox('sel_catgroup_id[]', $catgroup_arr,0,'','',5);
							  ?>            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SELCT_PARENT_CAT_GROUP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></div></td>
          </tr>
          <?php
				}
				if($show_category_prod==1)
				{
			?>
          <tr>
            <td align="left" valign="top" width="21%"><div>Select the Category </div>
 <div> <?php
							$cat_arr = generate_category_tree(0,0,true,true);
							echo generateselectbox('sel_prod_category_id[]',$cat_arr,0,'','','');
					  ?>          <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SELECT_PARENT_CAT_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </div></td>
          </tr>
          <?php
				}
			?>
         
          <tr>
            <td align="left" valign="middle" width="21%"  ><div>Select file to import <span class="redtext">*</span></div>
           <div> <input name="file_import" type="file" id="file_import"  />
              &nbsp;  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('SELCT_FILE_IMPORT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></div></td>
          
          </tr>
		  </table></td></tr>
		   
          <tr>
            <td width="34%" align="left" valign="top">
            <div>  Whether the importing file Have Header?</div>

               <div> <input type="checkbox" name="header_include"  id="header_include" />
              <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('INCLUDE_HEADER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></div>
		    </td>
          </tr>
		  
          <tr>
            <td align="left" valign="top" class="export_btn">
            <input name="import_Submit" type="submit" id="import_Submit" value="Import" class="red" onclick="return check_fields('import_Submit','<?=$mod?>')"/></td>
          </tr>
         
        </table>
        </div>
		<input type="hidden" name="mod" id="mod" value="import" />

			<input type="hidden" name="cur_mod" id="cur_mod" value="<?php echo $imp_type?>" />
		<?php
		}
	}
?>	

<?php
	/*############################################################################
	# Script Name 	: categoryHtml.php
	# Description 	: Page which holds the display logic for category details
	# Coded by 		: Sny
	# Created on	: 16-Jan-2008
	# Modified by	: Sny
	# Modified On	: 22-Jan-2008
	##########################################################################*/
	class category_Html
	{
		// Defining function to show the selected category details
		function Show_CategoryDetails($ret_cat)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,$Settings_arr,$ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr;
			$Captions_arr['CAT_DETAILS'] 	= getCaptions('CAT_DETAILS');
			// ** Fetch the category details
			$row_cat	= $db->fetch_array($ret_cat);
			?>
			<div class="pro_det_treemenu">
					<ul><?php echo generate_tree($_REQUEST['category_id'],-1,'<li>','</li>');
				?></ul>
				</div>
			<form method="post" action="<?php url_link('manage_products.html')?>"  name="frm_proddetails" id="frm_proddetails">
			<div class="lst_outer">
				<?php	
					// Check whether subcategories exists under current product
					$sql_subcat = "SELECT category_id ,category_name 
								FROM 
									product_categories 
								WHERE 
									sites_site_id = $ecom_siteid 
									AND parent_id = ".$_REQUEST['category_id']." 
									AND category_hide = 'N' 
								ORDER BY 
									category_order ASC";
					$ret_subcat = $db->query($sql_subcat);
					if ($db->num_rows($ret_subcat))
					{
						$show_desc = '';
						//Get the short description of parent category
						$sql_pcat = "SELECT category_shortdescription 
												FROM 
													product_categories 
												WHERE 
													sites_site_id = $ecom_siteid 
													AND category_id =".$_REQUEST['category_id']."  
													AND category_hide = 'N' 
												LIMIT 
													1";
						$ret_pcat = $db->query($sql_pcat);
						if($db->num_rows($ret_pcat))
						{
							$row_pcat = $db->fetch_array($ret_pcat);
							$show_desc_arr = explode('~',stripslashes($row_pcat['category_shortdescription']));
							if(count($show_desc_arr)>1)
							{
								$show_desc = $show_desc_arr[1];
							}
							else
								$show_desc = $show_desc_arr[0];

						}	
					?>
						<div class="pro_ajx" >
						<div class="pro_ajx_hdr">
						<div class="pro_ajx_stplt">Step 1</div>
						<div class="pro_ajx_stptxt"><?php echo $show_desc?></div>
						</div>
						<div class="pro_ajx_cont">
						
							<select name="first_cat" id="first_cat" onchange="handle_div(this,0,'cat')">
								<option value="">--Select --</option>
							<?php
							while($row_subcat = $db->fetch_array($ret_subcat)) 
							{
							?>
								<option value="<?=$row_subcat['category_id']?>"><?=$row_subcat['category_name']?></option>
							<?
							}
							?>
							</select>
						</div>
						</div>
					<?php	
					}
					else // case if no sub categories exists
					{						
						$show_desc = '';
						//Get the short description of parent category
						$sql_pcat = "SELECT category_shortdescription,product_orderfield ,product_orderby 
												FROM 
													product_categories 
												WHERE 
													sites_site_id = $ecom_siteid 
													AND category_id =".$_REQUEST['category_id']." 
													AND category_hide = 'N' 
												LIMIT 
													1";
						$ret_pcat = $db->query($sql_pcat);
						if($db->num_rows($ret_pcat))
						{
							$row_pcat = $db->fetch_array($ret_pcat);
							$show_desc_arr = explode('~',stripslashes($row_pcat['category_shortdescription']));
							if(count($show_desc_arr)>1)
							{
								$show_desc = $show_desc_arr[1];
							}
							else
								$show_desc = $show_desc_arr[0];
							if(trim($row_pcat['product_orderfield'])!='')
							{
								$def_orderfield 	= $row_pcat['product_orderfield'];
								$def_orderby		= $row_pcat['product_orderby'];
							}
							else
							{
								$def_orderfield 	= 'product_name';
								$def_orderby		= 'ASC';
							}	
							switch ($def_orderfield)
							{
								case 'custom': // case of order by customer fiekd
								$def_orderfield		= 'b.product_order';
								break;
								case 'product_name': // case of order by product name
								$def_orderfield		= 'a.product_name';
								break;
								case 'price': // case of order by price
								$def_orderfield		= 'a.product_webprice';
								break;
								case 'product_id': // case of order by price
								$def_orderfield		= 'a.product_id';
								break;
								default: // by default order by product name
								$def_orderfield		= 'a.product_name';
								break;
							};	
						}		
						// Check whether products exists under current category
						$sql_prod = "SELECT a.product_id,a.product_name 
												FROM 
													products a, product_category_map b 
												WHERE 
													a.sites_site_id = $ecom_siteid 
													AND a.product_id = b.products_product_id 
													AND b.product_categories_category_id = ".$_REQUEST['category_id']." 
													AND a.product_hide = 'N' 
											ORDER BY 
												$def_orderfield $def_orderby ";
						$ret_prod = $db->query($sql_prod);
						if ($db->num_rows($ret_prod))
						{
							
						?>
							<div class="pro_ajx" >
							<div class="pro_ajx_hdr">
							<div class="pro_ajx_stplt">Step 1</div>
							<div class="pro_ajx_stptxt"><?php echo $show_desc?></div>
							</div>
							<div class="pro_ajx_cont">
								<select name="product_id" id="product_id" onchange="handle_div(this,0,'pdt')">
									<option value="">--Select --</option>
								<?php
								while($row_prod = $db->fetch_array($ret_prod)) 
								{
								?>
									<option value="<?=$row_prod['product_id']?>"><?=$row_prod['product_name']?></option>
								<?
								}
								?>
								</select>
							</div>
							</div>
						<?php							
						}
						else
						{
						?>
							<div class="pro_ajx" >
							<div class="pro_ajx_hdr">
								<div class="pro_ajx_noprods">
									Sorry no products under this category
								</div>
							</div>
							</div>
						<?php
						}							 
					}
					?>

			
			<div id="1" class="pro_ajx_cont_sub">
			</div>
			</div>
			<?php 
			$cat_desc = stripslashes($row_cat['category_bottom_description']);
			if ($cat_desc!='')
			{
		?>
				<div class="lst_outer">
				<div class="lst_cat_des">
				<?php echo $cat_desc?>
				</div>
				</div>
		<?php 
			}
			$sql_inline = "SELECT display_order,display_component_id,display_id,display_title 
					FROM 
						display_settings a,features b 
					WHERE 
						a.sites_site_id=$ecom_siteid 
						AND a.display_position='middle' 
						AND b.feature_allowedinmiddlesection = 1  
						AND layout_code='".$default_layout."' 
						AND a.features_feature_id=b.feature_id 
						AND b.feature_modulename='mod_staticgroup' 
					ORDER BY 
							display_order 
							ASC";
				$ret_inline = $db->query($sql_inline);
				if ($db->num_rows($ret_inline))
				{
					while ($row_inline = $db->fetch_array($ret_inline))
					{
						$body_dispcompid	= $row_inline['display_component_id'];
						$body_dispid			= $row_inline['display_id'];
						$body_title				= $row_inline['display_title'];
						include ("includes/base_files/homepage_static_group.php");
					}
				}	
			?>
			
			<input type="hidden" name="counter" id="counter" value="0" />
			<input type="hidden" name="desc_change" id="desc_change" value="0" />	
			</form>
			<script type="text/javascript">
				imgsobj = new Image();
				imgsobj.src = '<img scr="<?php url_site_image('loading.gif')?>"  border="0" alt="loading..."/>';
				function handle_show_prod_det_bulk_disc(opt)
				{
					var varstr 	= '';
					var varidstr	= '';
					var prodid = document.getElementById('ajax_pass_prod_id').value;
					for(i=0;i<document.frm_proddetails.elements.length;i++)
					{
						if (document.frm_proddetails.elements[i].name.substr(0,4)=='var_')
						{
							splt_arr = 	document.frm_proddetails.elements[i].name.split('_');				
							if (varstr!='')
								varstr += '~';
							if (varidstr!='')
								varidstr += '~';	
							varstr 	+= document.frm_proddetails.elements[i].value;	
							varidstr 	+= splt_arr[1];	
						}
					}					
					var fpurpose									= '';
					switch (opt)
					{
						case 'price_qty':
							document.getElementById('ajax_qty_change').value = 1;
							handle_show_prod_det_bulk_disc('price');
							return;
						break;
						case 'price':
							var qtys
							if (document.frm_proddetails.qty)
							{
								qtys = document.frm_proddetails.qty.value;
								if(qtys==0)
									qtys = 1;
								document.frm_proddetails.qty.value = qtys;
							}	
							else
								qtys = 1;	
							var retdivid										= 'price_holder';
							var qrystr										= 'cur_qty='+qtys;
							fpurpose										= 'ajax_show_variable_price';	
							document.getElementById('desc_change').value = 3;
						break;
						case 'bulk':
								var retdivid										= 'bulkdisc_holder';
								var qrystr										= '';
								fpurpose										= 'ajax_show_bulk_discount';
								document.getElementById('desc_change').value = 4;
						break;	
						case 'main_img':
							var retdivid										= 'mainimage_holder';
							var qrystr										= '';
							fpurpose										= 'ajax_show_main_image';
							document.getElementById('desc_change').value = 5;
						break;	
						case 'more_img':
							var retdivid										= 'moreimage_holder';
							var qrystr										= '';
							if (document.getElementById('main_img_hold_id'))
							{
								qrystr = 'exclude_id='+document.getElementById('main_img_hold_id').value;
								
							}
							fpurpose										= 'ajax_show_more_image';
							document.getElementById('desc_change').value = 6;
						break;			
					};	
						
						document.getElementById('counter').value = retdivid;
						retobj 											= eval("document.getElementById('"+retdivid+"')");
						if(opt!='bulk' && opt !='more_img')
						{
							retobj.innerHTML 							= "<div align='center'><img src ='<?php echo url_site_image('loading.gif',1)?>' border='0'></div>";		
						}
						else
						{
							retobj.innerHTML 							= "";
						}	
						/* Calling the ajax function */
						Handlewith_Ajax('themes/<?php echo $ecom_themename?>/html/categoryAjax_onchange.php','fpurpose='+fpurpose+'&prodid='+prodid+'&pass_var='+varstr+'&pass_varid='+varidstr+'&'+qrystr);
				}
				function ajax_return_categorycontents() 
				{
					var ret_val='';
					if(req.readyState==4)
					{
						if(req.status==200)
						{
							ret_val 				= req.responseText;
							retobj 				= eval("document.getElementById('"+document.getElementById('counter').value+"')");				
							retobj.innerHTML	= ret_val;
							if (document.getElementById('desc_change').value==3) // case of price
							{
								if(document.getElementById('ajax_qty_change').value != 1)
								{
									document.getElementById('ajax_qty_change').value = '';
									handle_show_prod_det_bulk_disc('bulk');
								}	
								else
									document.getElementById('ajax_qty_change').value = '';
							}
							else if (document.getElementById('desc_change').value==4) // case of bulk disc
							{
								if(document.getElementById('comb_img_allowed').value=='Y' ) /* Do the following only if variable combination image option is set for current product */ 
									handle_show_prod_det_bulk_disc('main_img');
							}
							else	if(document.getElementById('desc_change').value==5)
							{
								if(document.getElementById('comb_img_allowed').value=='Y') /* Do the following only if variable combination image option is set for current product */ 
									handle_show_prod_det_bulk_disc('more_img');
							}
						}
						else
						{
							alert("Problem in requesting XML :"+req.statusText);
						}
					}
				}
				function handle_div(selectObj,c,page_type)
				{
					var counter = eval(c)+1;
					document.getElementById('desc_change').value = 0;
					document.getElementById('counter').value = counter;
					retobj = eval("document.getElementById('"+counter+"')");
					retobj.innerHTML = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="<?php url_site_image('loading.gif')?>"  border="0" alt="load...">';
					Handlewith_Ajax('themes/<?php echo $ecom_themename?>/html/categoryAjax_onchange.php','curval='+selectObj.value+'&page_type='+page_type+'&counter='+counter);
				}
				function handle_ajax_desc(tabid,prodid)
				{
					var txt = document.getElementById('hold_tab_ids').value.split('~');
					for(i=0;i<txt.length;i++)
					{
						oj = eval("document.getElementById('tabid_"+txt[i]+"')");
						oj.className = '';
						if(tabid==txt[i])
							oj.className = 'selectedtab';
					}
					document.getElementById('desc_change').value = 1;
					retobj = document.getElementById('proddet_maincontent');
					document.getElementById('counter').value = 'proddet_maincontent';
					retobj.innerHTML= '<img src="<?php url_site_image('loading.gif')?>"  border="0" alt="load..."/>';
					Handlewith_Ajax('themes/<?php echo $ecom_themename?>/html/categoryAjax_onchange.php','curval=1&page_type=cont_change&prodid='+prodid+'&tabid='+tabid);
				}
				function handle_image_swap(src_id)
				{
					imglocal_arr = new Array();
					var img_path = '<?php echo "http://$ecom_hostname/images/$ecom_hostname/"?>';
					var destindex = 0;
					if(document.getElementById('main_img_hold_var'))
					{
						var main_img = document.getElementById('main_img_hold_var').value
						if(main_img!='')
						{
							imglocal_arr[0]  = main_img;
						}	
					}
					if(document.getElementById('more_img_hold_var'))
					{	
						var more_img = document.getElementById('more_img_hold_var').value;
						if(more_img!='')
						{
							more_img_arr = more_img.split('~');
							for(i=0;i<more_img_arr.length;i++)
							{
								imglocal_arr[i+1] = more_img_arr[i];
							}
						}
					}
					if (src_id)
					{
						document.getElementById('main_det_img').src = img_path + 'big/'+imglocal_arr[src_id];
						
						srcobj = eval ("document.getElementById('moreid_"+src_id+"')");
						srcobj.src =  img_path + 'icon/'+imglocal_arr[destindex];
						
						var tempval 			= imglocal_arr[destindex];
						imglocal_arr[destindex] 	= imglocal_arr[src_id];
						imglocal_arr[src_id] 		= tempval;
						
						document.getElementById('main_img_hold_var').value = imglocal_arr[destindex];
						var temp_hold = '';
						if (imglocal_arr.length>1)
						{
							for(i=1;i<imglocal_arr.length;i++)
							{
								if(temp_hold!='')
									temp_hold += '~';
								temp_hold += imglocal_arr[i];
							}
						}
						document.getElementById('more_img_hold_var').value = temp_hold;
					}
				}
				function handle_bulk_disc(obj)
				{
					objs = eval("document.getElementById('"+obj+"')");
					if (objs)
					{
						if(objs.style.display=='none')
							objs.style.display = '';
						else
							objs.style.display = 'none';
					}
				}
			</script>
			<?php
		}
	};	
?>
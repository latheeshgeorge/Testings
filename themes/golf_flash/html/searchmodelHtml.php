<?php
/*############################################################################
# Script Name 	: searchHtml.php
# Description 		: Page which holds the display logic for search
# Coded by 		: LSH
# Created on		: 01-Feb-2008
# Modified on		: 27-Nov-2008
# Modified by		: Sny
##########################################################################*/
class searchmodel_Html
{
	//Defining the product details function
	function Show_Search($search_sql='',$tot_cnt=0,$sql_relate='')
	{ 
		global $inlineSiteComponents,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$prodperpage,$quick_search,$head_keywords,$row_desc;
		$Captions_arr['SEARCH'] 	= getCaptions('SEARCH');
		//Default settings for the search
		
		$prodsort_by		= ($_REQUEST['search_sortby'])?$_REQUEST['search_sortby']:$Settings_arr['product_orderfield_search'];
		$prodperpage		= ($_REQUEST['search_prodperpage'])?$_REQUEST['search_prodperpage']:$Settings_arr['product_maxcntperpage_search'];// product per page
		$prodsort_order		= ($_REQUEST['search_sortorder'])?$_REQUEST['search_sortorder']:$Settings_arr['product_orderby_search'];
		$showqty			= $Settings_arr['show_qty_box'];// show the qty box
		$prodperpage = 12;
		if($_REQUEST['search_id']>0)
		{
			$sql = "SELECT search_desc FROM saved_search WHERE search_id='".$_REQUEST['search_id']."' AND sites_site_id=$ecom_siteid";
			$res = $db->query($sql);
			$row = $db->fetch_array($res);
			$search_desc = $row['search_desc'];
		}
		switch ($prodsort_by)
		{
			case 'product_name': // case of order by product name
			$prodsort_bysql		= 'product_name';
			break;
			case 'price': // case of order by price
			$prodsort_bysql		= 'product_webprice';
			break;
			case 'product_id': // case of order by price
			$prodsort_bysql		= 'product_id';
			break;	
		};
		switch ($prodsort_order)
		{
			case 'ASC': // case of order by product name
			$prodsort_order		= 'ASC';
			break;
			case 'DESC': // case of order by price
			$prodsort_order		= 'DESC';
			break;
		};
		$query_string = "&";
		$search_fields = array('quick_search','search_category_id','search_model','search_minstk','search_minprice','search_maxprice','cbo_keyword_look_option','rdo_mainoption','rdo_suboption');
		foreach($search_fields as $v)
		{
			$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
		}
		if($_REQUEST['search_label_value'])
		{
			foreach($_REQUEST['search_label_value'] as $lab_val)
			{
				$query_string .= "search_label_value[]=$lab_val&";#For passing searh labels to javascript for passing to different pages.
			}
		}	
		$first_querystring=$query_string; //Assigning the initial string to the variable.
		$pg_variable	= 'search_pg';
		if($_REQUEST['top_submit_Page'] || $_REQUEST['bottom_submit_Page'] )
		{
			$_REQUEST[$pg_variable] = 0;
		}
		if ($_REQUEST['req']!='')// LIMIT for products is applied only if not displayed in home page
		{
			$start_var 		= prepare_paging($_REQUEST[$pg_variable],$prodperpage,$tot_cnt);
		}	
		else
			$Limit = '';		
		$querystring = ""; // if any additional query string required specify it over here
		//echo $search_sql.$Limit;
		
			// Calling the function to get the type of image to shown for current 
			$pass_type = get_default_imagetype('search');
			$comp_active = isProductCompareEnabled();
			
				$top_content = '';
				// Getting the top content for searches
				if(trim($search_desc))
				{
					$top_content = trim($search_desc);
				}
				elseif($row_desc['search_content'] && $_REQUEST['quick_search'])
				{
					$srch_arr 			= array('[title]','[keywords]','[first_keyword]');
					$rp_arr				= array($ecom_title,$head_keywords,$_REQUEST['quick_search']);
					$top_content		= trim(str_replace($srch_arr,$rp_arr,$row_desc['search_content']));
				}
				$HTML_comptitle = $HTML_maindesc = $HTML_paging = $HTML_showall = '';
				if ($tot_cnt>0 and ($_REQUEST['req']!='') and $tot_cnt>$prodperpage)
				{
					$pageclass_arr['container'] = 'pagenavcontainer';
					$pageclass_arr['navvul']	= 'pagenavul';
					$pageclass_arr['current']	= 'pagenav_current';
					$query_string 	.= "&amp;search_sortby=".$_REQUEST['search_sortby'].'&amp;search_sortorder='.$_REQUEST['search_sortorder'].'&amp;search_prodperpage='.$_REQUEST['search_prodperpage'];
					$paging 		= paging_footer_advanced($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr);
					$HTML_paging	= '	<div class="page_nav_con">
										<div class="page_nav_top"></div>
											<div class="page_nav_mid">
												<div class="page_nav_content">
												<ul>
												'.$paging['navigation']['start_nav'].$paging['navigation']['page_no'].$paging['navigation']['end_nav'].'
												</ul>
												</div>
											</div>
										<div class="page_nav_bottom"></div>
									</div>';
				}
				$HTML_treemenu = '<div class="tree_menu_con">
									  <div class="tree_menu_top"></div>
									  <div class="tree_menu_mid">
										<div class="tree_menu_content">
										  <ul class="tree_menu">
										<li><a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
										';
				
					 $HTML_treemenu .='<li>Advanced Search</li>';
				
				$HTML_treemenu .=	  '</ul>
									  </div>
								  </div>
								  <div class="tree_menu_bottom"></div>
								</div>';
				echo $HTML_treemenu;	
				
				
				//End section 
				$HTML_tophidden ='
									<input type="hidden" name="pos" value="top" />
									<input type="hidden" name="quick_search" value="'.$quick_search.'" />
									<input type="hidden" name="category_id" value="'.$_REQUEST['category_id'].'" />
									<input type="hidden" name="search_model" value="'.$_REQUEST['search_model'].'" />
									<input type="hidden" name="search_minstk" value="'.$_REQUEST['search_minstk'].'" />
									<input type="hidden" name="search_minprice" value="'.$_REQUEST['search_minprice'].'" />
									<input type="hidden" name="search_maxprice" value="'.$_REQUEST['search_maxprice'].'" />
									<input type="hidden" name="searchVariableName" value="'.$_REQUEST['searchVariableName'].'" />
									<input type="hidden" name="searchVariableOption" value="'.$_REQUEST['searchVariableOption'].'" />';
									
									/*echo "<br><br><br>makeid:".$_SESSION['searchmodel_makeid'];
						echo "<br><br><br>modelid:".$_SESSION['searchmodel_modelid'];
						echo "<br><br><br>serialno:".$_SESSION['searchmodel_serialno'];
						echo "<br><br><br>categoryid:".$_SESSION['searchmodel_categoryid'];*/
						
						
						$ret_arrs_n		= get_lsrefine_holder();
						$d_make_id 		= $ret_arrs_n['make_id'];
						$d_model_id 	= $ret_arrs_n['model_id'];
						$d_srno 		= $ret_arrs_n['serialno_id'];
						$d_category_id  = $ret_arrs_n['cat_id'];
						?>
									
					<table width="537" border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
					<td valign="top"><table width="537" border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
					<td>
						<div class="make_select">
					<table width="100%" border="0" cellspacing="3" cellpadding="5">
					<tr>
					<td width="34%">Make</td>
					<td colspan="2">Model</td>
					</tr>
					<tr>
					<td>
						<div id="thisAAA"></div>
						<?php
						$sql_selmake = "SELECT make_id,make_caption FROM ls_refine_make WHERE sites_site_id=$ecom_siteid AND make_hide=0 ORDER BY make_caption ASC";
						$ret_selmake = $db->query($sql_selmake);
						
						?>
					<select name="select_make" id="select_make">
						
					<option value="0">--Select Make--</option>
					<?php 
						if($db->num_rows($ret_selmake)>0)
						{
							while($row_make = $db->fetch_array($ret_selmake))
							{
								$make_name = $row_make['make_caption'];
								$make_id = $row_make['make_id'];
								if($d_make_id==$make_id)
								{
									$sel_retain = 'selected="selected"';
								}
								else
								{
									$sel_retain = '';
								}
								?>
									<option value="<?php echo $make_id?>" <?php echo $sel_retain?>><?php echo $make_name?></option>
								<?php
							}
						}
					?>
					</select></td>
					<td width="34%"><select name="select_model" id="select_model">
										<?php 
										if($d_make_id)
										{
											$mk_id = $d_make_id;
										}
										else
										{
											$mk_id=0;
										}	
										show_model_select($mk_id); ?>
					</select></td>
					</tr>
					<tr>
					<td width="34%">Serial Prefix</td>
					<td colspan="2"></td>
					</tr>
					<tr>
						<td width="54%"><select name="select_serialno" id="select_serialno">
										<?php 
										//$mk_id=0;
										if($d_model_id)
										{
											$mod_id = $d_model_id;
										}
										else
										{
											$mod_id=0;
										}	
										show_serialno_select($mod_id); ?>
					</select></td>
					<td width="32%"><a href="javascript:void(0)" id="refine_category_search"><img src="<?php url_site_image('search_bts.png')?>" width="120" height="26" border="0" /></a></td>
					</tr>					
					
					</table>
					</div>
					<div id="select_model_image"><?php 
										$mk_id=0;
										show_srno_image($mk_id); ?></div></td>
					</tr>
					</table></td>
					</tr>
					<tr>
					<td style="text-align:left;background-color:#f0f0f0;border:solid 1px #ccc;" colspan="2">
					
					<?php /*<div style="font-weight:bold;font-size:12px;text-decoration:underline;padding: 5px;">How to use</div>
					<div style="font-weight:normal;font-size:11px;text-decoration:none;padding: 5px;">&bull; First locate your forklift name plate. Make a note of the "Model" and "serial number" (sometimes also referred to as a Chassis Number or Frame Number)</div>
					<div style="font-weight:normal;font-size:11px;text-decoration:none;padding: 5px;">&bull; Select the "make" of your truck from the drop down box above</div>
					<div style="font-weight:normal;font-size:11px;text-decoration:none;padding: 5px;">&bull; Then select the "model"</div>
					<div style="font-weight:normal;font-size:11px;text-decoration:none;padding: 5px;">&bull; Some forklifts will assign different serial prefixes to the same model select your "serial prefix" if required</div>
					<div style="font-weight:normal;font-size:11px;text-decoration:none;padding: 1px 5px 5px 5px;text-align:center;">(this is the first 4-5 characters on the serial number e.g Hyster D177 or Mitsubishi EF18B) </div>
					*/?>
					<style>
					.newulclass{
						padding-left:10px;
					}
					.newulclass li{
						margin-left:10px;
						padding:5px;
						list-style:square !important;
						font-size:12px;
					}
					</style>
										<h2 style="padding: 5px 5px 10px 5px;"><span style="font-weight:bold;font-size:14px;text-decoration:none;">How to search using the Forklift Part Finder.</span></h2>

					<div class="model_content_search">
					
					<ul class="newulclass">
					<li>First locate your <strong><em>forklift name plate</em></strong> and make a note of the "<em><strong>Forklift Model</strong></em>" and "<strong><em>Forklift Serial Number</em></strong>" (sometimes also referred to as a <em><strong>Forklift Chassis Number or Frame Number</strong></em>)</li>
					<li>Select the <em><strong>"make" of your Forklift Truck</strong></em> from the drop down box above</li>
					<li>Then select the <em><strong>"model" of your Forklift Truck</strong></em></li>
					<li>Some forklifts will assign different serial prefixes to the same model select your "serial prefix" if required<br>(This is the first 4-5 characters on the serial number e.g <strong>Hyster D177</strong> or <strong>Mitsubishi EF18B</strong>)</li>
					<li>Finally Click on the <strong>&ldquo;Search Button&rdquo;</strong> to view the results page.</li>
					</ul>
					
					<br /><p style="font-weight:normal;font-size:12px;text-decoration:none;padding: 5px;">At <em><strong>LS Forklifts</strong></em> we are constantly acquiring and adding new forklift parts to our website. If you cannot find the part required in our
search part finder; Please phone us on <em><strong>+44 (0)28 388 52503</strong></em> as we might have it in stock ready to be listed..<br /><br /></p>
					</div>
					</td>
					</tr>
					<tr>
					<td valign="middle">
						
						<div class="refine_categoryloading_container" id="refine_categoryloading_div" style="display:none;padding:10px 3px 10px 150px;" ><img src="<?php echo url_site_image('ajax-loader_cart.gif',1)?>"/>		</div>
						<div class="horizontal_container" id="refine_category_div" style="display:none">		
						
					</div>
					<div class="product_list_wrap_outer" id="refine_categoryprodcut_div" style="display:none">
						
					</div>
					</td>
					</tr>
					<tr>
					<td valign="top">&nbsp;</td>
					</tr>
					<tr>
					<td valign="top">
						
					</td>
					</tr>
					</table>
					<form method="post" name="ModelFormtoprod" id="ModelFormtoprod" action="" class="frm_cls">
						<input type="hidden" name="fproduct_id" id="searchproduct_id" value="" />
						<input type="hidden" name="searchmodelto_prod" id="searchmodelto_prod" value="1" />


			        </form>
			        <script type="text/javascript">
				function gotoproduct(url,prod_id)
				{ 
					 document.getElementById("searchproduct_id").value = prod_id;
					 document.getElementById("ModelFormtoprod").action = url;
				   document.getElementById("ModelFormtoprod").submit();
				}
				</script>
					<script language="javascript">
						jQuery.noConflict();
						var $ajax_jj = jQuery;
						
						$ajax_jj(document).ready(function(){ 
					    /*$ajax_jj("#select_make").val(0) ;
					    $ajax_jj("#select_model").val(0) ;
					    $ajax_jj("#select_serialno").val(0);*/
						$ajax_jj(document).on("click", '.subcat_3row_pdt_outrcat', function(event) { 
							//$ajax_jj("#refine_category_div").hide();

							var x_value=parseInt($ajax_jj(this).attr('catid'));
							var x_catname=$ajax_jj(this).attr('catname');

							var mk_value=parseInt($ajax_jj("#select_make").val());
						    var md_value=parseInt($ajax_jj("#select_model").val());
					        var srno_value=parseInt($ajax_jj("#select_serialno").val());

								if(parseInt(x_value)>-1)
								{   $ajax_jj("#refine_category_div").hide();
								     $ajax_jj(".make_select").hide();					
                                    $ajax_jj("#refine_categoryloading_div").show();

									$ajax_jj("#refine_categoryprodcut_div").show();
									$ajax_jj.ajax({
									url:'includes/base_files/search_model.php',
									data:{srhcategory_id:x_value,
										category_name:x_catname,
										make_id:mk_value,
										model_id:md_value,
										srno_id:srno_value,
										prod_perpage:<?php echo $prodperpage; ?>,
									search_meth:'show_category_productmodel'},
									type: 'post',
									success : function(resp){
									$ajax_jj("#refine_categoryprodcut_div").html(resp);  
									$ajax_jj("#refine_categoryloading_div").delay(500).fadeOut();             
									},
									error : function(resp){}
									});

								}
								else
								{
									$ajax_jj("#select_model").hide();
									$ajax_jj("#select_serialno").hide();
								}
						});
						$ajax_jj(document).on("click", '.blacklinkA', function(event) { 
							var x_value=parseInt($ajax_jj(this).attr('id'));
						    var c_value_mk=parseInt($ajax_jj("#select_category_hidden").val());
						    var cn_value_mk=$ajax_jj("#select_categoryn_hidden").val();

                            var x_value_mk=parseInt($ajax_jj("#select_make_hidden").val());
							var x_value_md=parseInt($ajax_jj("#select_model_hidden").val());
							var x_value_srno=parseInt($ajax_jj("#select_srno_hidden").val());

							$ajax_jj("#refine_categoryloading_div").show();
							
							$ajax_jj.ajax({
									url:'includes/base_files/search_model.php',
									data:{
										srhcategory_id:c_value_mk,
										category_name:cn_value_mk,
										make_id:x_value_mk,
										model_id:x_value_md,
										srno_id:x_value_srno,
										page_id:x_value,
										prod_perpage:<?php echo $prodperpage; ?>,
									search_meth:'show_category_productmodel'},
									type: 'post',
									success : function(resp){
									$ajax_jj("#refine_categoryprodcut_div").html(resp);  
									$ajax_jj("#refine_categoryloading_div").delay(500).fadeOut();             
									},
									error : function(resp){}
									});
				              $ajax_jj("#"+x_value).attr('class','redlinkA');


						});
							$ajax_jj("#select_make").change(function(){  
								var x_value=parseInt($ajax_jj("#select_make").val());
								if(parseInt(x_value)>-1)
								{ 
									$ajax_jj("#refine_categoryloading_div").show();

									$ajax_jj("#select_model").show();
									$ajax_jj.ajax({
									url:'includes/base_files/search_model.php',
									data:{make_id:x_value,
									search_meth:'show_model_select'},
									type: 'post',
									success : function(resp){
									$ajax_jj("#select_model").html(resp);
									$ajax_jj("#refine_categoryloading_div").delay(500).fadeOut();             
               
									},
									error : function(resp){}
									});
									
									$ajax_jj.ajax({
									url:'includes/base_files/search_model.php',
									data:{make_id:0,
									model_id:0,
									search_meth:'show_serialno_select'},
									type: 'post',
									success : function(resp){
									$ajax_jj("#select_serialno").html(resp); 
									$ajax_jj("#refine_categoryloading_div").delay(500).fadeOut();              
									},
									error : function(resp){}
								});
								$ajax_jj.ajax({
									url:'includes/base_files/search_model.php',
									data:{make_id:x_value,
									model_id:0,
									srno_id:0,
									search_meth:'show_category_search'},
									type: 'post',
									success : function(resp){
									$ajax_jj("#refine_category_div").html(resp); 
									$ajax_jj("#refine_categoryloading_div").delay(500).fadeOut();              
									},
									error : function(resp){}
								});
								}
								else
								{ 
									$ajax_jj("#select_model").hide();
									$ajax_jj("#select_serialno").hide();

								}

							});
							$ajax_jj("#select_model").change(function(){ 
								$ajax_jj("#refine_category_div").hide();
								$ajax_jj("#select_model_image").hide();

								$ajax_jj("#refine_categoryloading_div").show();
								var x_value_mk=parseInt($ajax_jj("#select_make").val());
								var x_value_md=parseInt($ajax_jj("#select_model").val());
                                
								$ajax_jj("#refine_category_div").show();
								if(parseInt(x_value_md)>-1)
								{
									$ajax_jj.ajax({
									url:'includes/base_files/search_model.php',
									data:{make_id:x_value_mk,
									model_id:x_value_md,
									search_meth:'show_serialno_select'},
									type: 'post',
									success : function(resp){
									$ajax_jj("#select_serialno").html(resp); 
									$ajax_jj("#refine_categoryloading_div").delay(500).fadeOut();              
									},
									error : function(resp){}
								});
								
								/*
								$ajax_jj.ajax({
									url:'includes/base_files/search_model.php',
									data:{model_id:x_value_md,
									search_meth:'show_model_image'},
									type: 'post',
									success : function(resp){
									$ajax_jj("#select_model_image").html(resp); 
									},
									error : function(resp){}
								});
								*/
								
								$ajax_jj.ajax({
									url:'includes/base_files/search_model.php',
									data:{make_id:x_value_mk,
									model_id:x_value_md,
									search_meth:'show_category_search'},
									type: 'post',
									success : function(resp){
									$ajax_jj("#refine_category_div").html(resp); 
									$ajax_jj("#refine_categoryloading_div").delay(500).fadeOut();              
									},
									error : function(resp){}
								});
								
								}
								else
								{ 
								$ajax_jj("#select_serialno").hide();

								}

							});
							$ajax_jj("#select_serialno").change(function(){ 
								$ajax_jj("#refine_category_div").hide();
								$ajax_jj("#refine_categoryloading_div").show();
							    $ajax_jj("#select_model_image").show();

								var x_value_mk=parseInt($ajax_jj("#select_make").val());
								var x_value_md=parseInt($ajax_jj("#select_model").val());
								var srno_value=parseInt($ajax_jj("#select_serialno").val());

								$ajax_jj("#refine_category_div").show();
								$ajax_jj.ajax({
									url:'includes/base_files/search_model.php',
									data:{srno_id:srno_value,
									search_meth:'show_serial_image'},
									type: 'post',
									success : function(resp){
									$ajax_jj("#select_model_image").html(resp); 
									},
									error : function(resp){}
								});
								$ajax_jj.ajax({
									url:'includes/base_files/search_model.php',
									data:{make_id:x_value_mk,
									model_id:x_value_md,
									srno_id:srno_value,
									search_meth:'show_category_search'},
									type: 'post',
									success : function(resp){
									$ajax_jj("#refine_category_div").html(resp); 
									$ajax_jj("#refine_categoryloading_div").delay(500).fadeOut();              
									},
									error : function(resp){}
								});

							});
						$ajax_jj("#refine_category_search").click(function(){ 
								var x_value_mk=parseInt($ajax_jj("#select_make").val());
								var x_value_md=parseInt($ajax_jj("#select_model").val());
								var srno_value=parseInt($ajax_jj("#select_serialno").val());

								if(x_value_mk==0)
								{
								 alert('Select make !!!');
								 return false;
								}
								if(x_value_md==0)
								{
								 alert('Select model !!!');
								 return false;
								}
								if(srno_value==0)
								{
								 alert('Select Serial Prefix!!!');
								 return false;
								}
								$ajax_jj("#refine_categoryloading_div").show();


								$ajax_jj("#refine_category_div").show();
								$ajax_jj.ajax({
									url:'includes/base_files/search_model.php',
									data:{make_id:x_value_mk,
									model_id:x_value_md,
									srno_id:srno_value,
									search_meth:'show_category_search'},
									type: 'post',
									success : function(resp){
									$ajax_jj("#refine_category_div").html(resp); 
									$ajax_jj("#refine_categoryloading_div").delay(500).fadeOut();              
									},
									error : function(resp){}
								});



						});
						$ajax_jj(document).on("click", '#category_backpid', function(event) { 

								var x_value_mk=parseInt($ajax_jj("#select_make_hidden").val());
								var x_value_md=parseInt($ajax_jj("#select_model_hidden").val());
							    var x_value_srno=parseInt($ajax_jj("#select_srno_hidden").val());
								$ajax_jj("#refine_categoryloading_div").show();

								$ajax_jj(".make_select").show();	
								$ajax_jj("#refine_category_div").show();
								$ajax_jj("#refine_categoryprodcut_div").hide();

								$ajax_jj.ajax({
									url:'includes/base_files/search_model.php',
									data:{make_id:x_value_mk,
									model_id:x_value_md,
									srno_id:x_value_srno,
									search_meth:'show_category_search'},
									type: 'post',
									success : function(resp){
									$ajax_jj("#refine_category_div").html(resp);   
									$ajax_jj("#refine_categoryloading_div").delay(500).fadeOut();            
									},
									error : function(resp){}
								});
								



						});
						
							<?php 
							if($d_category_id)
							{
								$cname = "";
								// get the name of the cateogry
								global $ecom_siteid;
								$sql_cm = "SELECT category_name FROM product_categories WHERE sites_site_id = $ecom_siteid AND category_id = '".$d_category_id."' LIMIT 1";
								$ret_cm = $db->query($sql_cm);
								if($db->num_rows($ret_cm))
								{
									$row_cm = $db->fetch_array($ret_cm);
									$cname = str_replace("'",'',$row_cm['category_name']);
								}
							?>		
								
								handle_category_product_show_onload(<?php echo $d_category_id?>,'<?php echo $cname?>',<?php echo $d_make_id?>,parseInt(<?php echo $d_model_id?>),parseInt(<?php echo $d_srno?>));
							<?php 
							}
							else
							{
							?>	
								var mk_value_hold=parseInt($ajax_jj("#select_make").val());
								var md_value_hold=parseInt($ajax_jj("#select_model").val());
								var srno_value_hold=parseInt($ajax_jj("#select_serialno").val());
								if(mk_value_hold>0 && md_value_hold>0)
								{
									$ajax_jj( "#refine_category_search" ).trigger( "click" );
								}
							<?php	
							}	
							?>
							
							
						});
						
						function handle_category_product_show_onload(catid,catname,make,model,serial) { 
							//$ajax_jj("#refine_category_div").hide();

							var x_value=parseInt(catid);
							var x_catname=catname;

							var mk_value=parseInt(make);
						    var md_value=parseInt(model);
					        var srno_value=parseInt(serial);

								if(parseInt(x_value)>-1)
								{   $ajax_jj("#refine_category_div").hide();
								     $ajax_jj(".make_select").hide();					
                                    $ajax_jj("#refine_categoryloading_div").show();

									$ajax_jj("#refine_categoryprodcut_div").show();
									$ajax_jj.ajax({
									url:'includes/base_files/search_model.php',
									data:{srhcategory_id:x_value,
										category_name:x_catname,
										make_id:mk_value,
										model_id:md_value,
										srno_id:srno_value,
										prod_perpage:<?php echo $prodperpage; ?>,
									search_meth:'show_category_productmodel_reload'},
									type: 'post',
									success : function(resp){
									$ajax_jj("#refine_categoryprodcut_div").html(resp);  
									$ajax_jj("#refine_categoryloading_div").delay(500).fadeOut();  
									         
									},
									error : function(resp){}
									});

								}
								else
								{
									$ajax_jj("#select_model").hide();
									$ajax_jj("#select_serialno").hide();
								}
						}
						
						
					/*window.onpopstate = function() {

						<?php 
							if($_SESSION['searchmodel_categoryid'])
							{
								$cname = "";
								// get the name of the cateogry
								global $ecom_siteid;
								$sql_cm = "SELECT category_name FROM product_categories WHERE sites_site_id = $ecom_siteid AND category_id = '".$_SESSION['searchmodel_categoryid']."' LIMIT 1";
								$ret_cm = $db->query($sql_cm);
								if($db->num_rows($ret_cm))
								{
									$row_cm = $db->fetch_array($ret_cm);
									$cname = str_replace("'",'',$row_cm['category_name']);
								}
							?>		
								
								handle_category_product_show_onload(<?php echo $_SESSION['searchmodel_categoryid']?>,'<?php echo $cname?>',<?php echo $_SESSION['searchmodel_makeid']?>,parseInt(<?php echo $_SESSION['searchmodel_modelid']?>),parseInt(<?php echo $_SESSION['searchmodel_serialno']?>));
							<?php 
							}
							else
							{
							?>	
								var mk_value_hold=parseInt($ajax_jj("#select_make").val());
								var md_value_hold=parseInt($ajax_jj("#select_model").val());
								var srno_value_hold=parseInt($ajax_jj("#select_serialno").val());
								if(mk_value_hold>0 && md_value_hold>0)
								{
									$ajax_jj( "#refine_category_search" ).trigger( "click" );
								}
							<?php	
							}	
							?>
					};	*/
					
					if(!!window.performance && window.performance.navigation.type == 2)
					{
						window.location.reload();
					}		
						
				</script>
					<?Php
				
			
	}	
	
};	

?>

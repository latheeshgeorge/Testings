<?php
// ###############################################################################################################
// ###############################################################################################################
function show_display_maininfo($fb_id,$alert)
	{
		global $db,$ecom_siteid ;
		$sql  		=   "SELECT * FROM facebook_tab_content WHERE id=$fb_id AND sites_site_id = $ecom_siteid LIMIT 1";
		$ret_sql    = $db->query($sql);
		if($db->num_rows($ret_sql)>0)
		{
		$row_fb 	= $db->fetch_array($ret_sql ); 
		$template   = $row_fb['fb_content'];
		}
		?>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
       <tr>
          <td  align="center" valign="middle" class="tdcolorgray" >
		  <div class="editarea_div">
			  <table width="100%" border="0" cellspacing="0" cellpadding="0">
				   <tr>
           <td align="left" valign="middle" class="tdcolorgray" width="20%" >Is Active?<span class="redtext">*</span></td>
		   <td align="left" valign="middle" class="tdcolorgray"><input type="checkbox" name="fb_is_active" value="1" <?php echo ($row_fb['fb_is_active']==1)?'checked':''; ?>>
		   <a href="#" onmouseover="ddrivetip('You should tick this option, if you would like to set the content of this template to be displayed in the facebook business tab.')" ;="" onmouseout="hideddrivetip()"><img src="images/helpicon.png" border="0" height="13" width="17"></a>
		   </td>
           <td colspan="2" align="right" valign="middle" class="tdcolorgray" ></td>
           <td align="left" valign="middle" class="tdcolorgray"></td>
    </tr>	
		   <tr>
           <td align="left" valign="middle" class="tdcolorgray" width="20%" >Title<span class="redtext">*</span>
             </td>
		   <td align="left" valign="middle" class="tdcolorgray"><input type="text" name="fb_subject" value="<?php echo $row_fb['fb_subject'] ?>"></td>
           <td colspan="2" align="right" valign="middle" class="tdcolorgray" ></td>
           <td align="left" valign="middle" class="tdcolorgray"></td>
    </tr>	
       <tr>
          <td  align="center" valign="middle" class="tdcolorgray"  colspan="5" >
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		 			<tr><td width="62%" align="left" valign="middle" class="tdcolorgray"><table width="65%" border="0" cellspacing="0" cellpadding="0">
                   
		 		 
		 <tr>
		   <td colspan="5" align="left" valign="top" class="tdcolorgray">
		<textarea style="height:500px; width:750px" id="fb_content" name="fb_content"><?=stripslashes($template)?></textarea>		</td>
    </tr>		 
		 <tr>
		   <td colspan="5" align="left" valign="middle" class="tdcolorgray" ></td>
    </tr>	 
             </table></td>
			 <td width="38%" colspan="2" align="left" valign="top" class="tdcolorgray" >
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      
       <tr>
          <td  align="center" valign="middle" class="tdcolorgray" >	
          <div id="select_showproduct_div">
			<?php 		
									$mod = 'main';
									show_selected_products($mod,$fb_id);
			?>
		</div>		 
</td>
			 </tr>  
		 
	</table>
	      </td>
	
</tr>
</table>
</td>
</tr>
<tr>
		  <td colspan="5" align="left" valign="middle" class="tdcolorgray" ><table width="100%" border="0" class="listingtable">
             <tr >
               <td colspan="3" class="helpmsgtd" align="left"><div class="helpmsg_divcls"><?=get_help_messages('NEWSLETTER_CODE_REPLACE')?></div></td>
             </tr>
             <tr class="listingtableheader">
               <td width="17%"><div align="left"><strong>&nbsp; Code</strong></div></td>
               <td width="5%">&nbsp;</td>
               <td width="78%"><div align="left"><strong>&nbsp; Description</strong></div></td>
             </tr>
             <?PHP 
               $templatetab = array(get_help_messages('LIST_FACEBOOK_CODE_PRODUCT_MESS1')=>'[Products]');						

			 	foreach($templatetab AS $key=>$val) {
			 ?>
             <tr class="listingtablestyleB">
               <td align="left" > &nbsp; <?PHP echo $val; ?></td>
               <td>=&gt;</td>
               <td align="left">&nbsp; <?PHP echo $key; ?></td>
             </tr>
             <?PHP } ?>
           </table></td>
    </tr>
</table>
	</div>
	</td>
			
	</tr>	
	<tr>
		<td align="right" colspan="2" valign="middle" class="tdcolorgray" >
			<div class="editarea_div">
				<table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
				<tr>
				  <td align="right" valign="middle">&nbsp;</td>
				  </tr>
				<tr>
					<td align="right" valign="middle">							
						<input name="Submit" type="button" class="red" value="Save & Continue " onclick="valform(frmfacebookedit,'main')" />					</td>
				</tr>
				</table>
			</div>
		</td>
	</tr>		
      </table>
		<?php
		 
	}
	function show_selected_products($mod,$fb_id,$prod_arr=array(),$alert='')
	{
	    global $db,$ecom_siteid;	   		
		$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmfacebookedit,\'checkbox_prod[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmfacebookedit,\'checkbox_prod[]\')"/>','Product','Order');
			$header_positions=array('left','left','left');
		if($mod=='main')
		{
		    $products_sql = "SELECT a.product_id,a.product_name,b.product_order  FROM products a,facebook_tab_product_map b WHERE a.sites_site_id = $ecom_siteid AND b.fbtab_id=".$fb_id." AND b.product_product_id=a.product_id";
            $ret_products = $db->query($products_sql);
		}
		elseif($mod=='popup')
		{
			 if(count($prod_arr))
			 {
			 $products_sql = "SELECT a.product_id,a.product_name  FROM products a WHERE a.sites_site_id = $ecom_siteid  AND a.product_id IN (".implode(",",$prod_arr).") ";
             $ret_products = $db->query($products_sql);
		    }
		}
		//if($fb_id)
		{
			
            ?>
            <table width="100%" cellspacing="1" cellpadding="1" border="0" class="category_box">
				<?php
				if($alert!='')
				{
				 ?>
				 <tr>
				 <td align="center" valign="middle" class="errormsg" colspan="3"><?php echo $alert; ?></td>
				 </tr>
				 <?php
			 }
				 ?>
				 <tr>
				 	<td align="left" colspan="3" class="listingtableheader">	
					&nbsp;&nbsp;<strong>Select Products to be listed in the content.</strong>
					</td>
				</tr>	
				 <tr>
					<td align="right" valign="middle" colspan="3">							
						<input name="button" type="button" class="red" value="Asign more" onclick="call_ajax_assign(<?php echo $fb_id ?>)" />
					&nbsp;
						<?php
			if($db->num_rows($ret_products)>0)
			{
				?>
					<input name="button" type="button" class="red" value="Unassign" onclick="call_ajax_unassign(<?php echo $fb_id ?>)" />
                    <?php /*&nbsp;<input name="button" type="button" class="red" value="Save Order" onclick="call_ajax_saveorder(<?php echo $fb_id ?>)" /> */?>                    
				<?php
			}
				?>
						</td>	
				</tr>
            <?php
			if($db->num_rows($ret_products)>0)
			{				
					echo table_header($table_headers,$header_positions); 
			
				 	$cnt=0;
				 	while($row_products=$db->fetch_array($ret_products))
				 	{						
						$product_name = $row_products['product_name'];
						$prod_id   = $row_products['product_id'];
											
				 	?>	
				 	<tr>
				    <td class="listingtablestyleA"><input type="checkbox" value="<?php echo $prod_id?>" name="checkbox_prod[]" id="checkbox_prod[]"/> 
						<input type="hidden" name="sel_product_id_<?php echo $prod_id?>" id="sel_product_id_<?php echo $prod_id?>" value="<?php echo $prod_id?>">
						</td>
						<td class="listingtablestyleA"><?php echo $product_name;?></td>
						<td class="listingtablestyleA"><input type="text" size="2" name="product_order_<?php echo $prod_id?>" id="product_order_<?php echo $prod_id?>" value="<?php echo $row_products['product_order']?>"></td>	
				  	</tr>					 					
				  	<?php				  	
				  	    $cnt ++;						
					}				  				
			}
			?>
			</table>
			<?php
		}
		
	}
	function show_display_preview($fb_id,$dept_id=0)
	{
		global $db,$ecom_siteid ,$ecom_hostname;
		$sql  		=   "SELECT * FROM facebook_tab_content WHERE id=$fb_id AND sites_site_id = $ecom_siteid LIMIT 1";
		$ret_sql    = $db->query($sql);
		if($db->num_rows($ret_sql)>0)
		{
		$row_fb 	= $db->fetch_array($ret_sql ); 
		$template   = $row_fb['fb_content'];
		}
		$mod = ($_REQUEST['mod'])?$_REQUEST['mod']:'list';
		if($row_fb['fb_preview_content']=='')
		{
			$mod='main';
		}
		if($mod=='main')
		{
		$product_template = "SELECT fb_product FROM common_facebook_template LIMIT 1";
		$ret_template     = $db->query($product_template);
		 if($db->num_rows($ret_template)>0)
        {
		$row_template     = $db->fetch_array($ret_template);
		$product_layout   = $row_template['fb_product'];
	    }
		$products_sql = "SELECT a.product_id,a.product_name, a.product_shortdesc, a.product_webprice, a.product_discount, a.product_discount_enteredasval FROM products a,facebook_tab_product_map b WHERE a.sites_site_id = $ecom_siteid AND b.fbtab_id=".$row_fb['id']." AND b.product_product_id=a.product_id";
        $ret_products = $db->query($products_sql);
        if($db->num_rows($ret_products)>0)
        {
			while($row_products=$db->fetch_array($ret_products))
			{
			   $product_layouttem = $product_layout;
			   $count+=1;
				
				 $imagsql     = "SELECT  image_thumbpath 
				                      FROM images a, images_product b 
									                 WHERE a.image_id=b.images_image_id 
													   AND b.products_product_id = '".$row_products['product_id']."' 
													   AND a.sites_site_id = '".$ecom_siteid."'
													   		ORDER BY b.image_order ASC
													     ";
				$imagres      = $db->query($imagsql);
				$imagrow      = $db->fetch_array($imagres);
			    $images       = $imagrow['image_thumbpath'];

			  if($row_products['product_discount']>0)
				{
					switch($row_products['product_discount_enteredasval']) 
					{
						case '0' :
							$rate =  $row_products['product_webprice'] - ($row_products['product_webprice']*$row_products['product_discount']/100);
						break;
						case '1' :
						    $rate =  $row_products['product_webprice'] - $row_products['product_discount'];
						break;
						case '2' :
							$rate =  $row_products['product_discount'];		
						break;
						default :
							$rate = $row_products['product_webprice'];
					}
				}					
				$link = "http://".$ecom_hostname."/p".$row_products['product_id']."/".strip_url($row_products['product_name']).".html";				 
                if(trim($images)) {					
					$imgname = "<a href=\"http://".$ecom_hostname."/p".$row_products['product_id']."/".strip_url($product_id['product_name']).".html\">
					<img src='http://".$ecom_hostname."/images/".$ecom_hostname."/".$images."' border='0'/></a>";					 
				} else { 
					$imgname = "<a href=\"http://".$ecom_hostname."/p".$row_products['product_id']."/".strip_url($row_products['product_name']).".html\"><img src='http://".$ecom_hostname."/images/".$ecom_hostname."/site_images/no_small_image.gif' border='0'/></a>";
				}
					$product_layouttem =	str_replace('[IMG]',$imgname,$product_layouttem);
					$prodname 	   =    add_slash($row_products['product_name']);
					$prodname_link =   "http://".$ecom_hostname."/p".$row_products['product_id']."/".strip_url($row_products['product_name']).".html";
					$prodshortdesc =    $row_products['product_shortdesc'];
					$org_rate = $rate;
					$ret_rate      =  display_price($row_products['product_webprice']);
					$rate 	   	   	=    display_price($rate);
					$anyprice = 0;
					if($org_rate)
						$anyprice = display_price($org_rate);
					else
						$anyprice = $ret_rate;
					
					$product_layouttem =	str_replace('[TITLE]',$prodname,$product_layouttem);
					$product_layouttem =	str_replace('[DESCRIPTION]',$prodshortdesc,$product_layouttem);
					$product_layouttem =	str_replace('[PRICE]',$rate,$product_layouttem);
					$product_layouttem =	str_replace('[RET_PRICE]',$ret_rate,$product_layouttem);
					$product_layouttem =	str_replace('[ANY_PRICE]',$anyprice,$product_layouttem);
					$product_layouttem =	str_replace('[LINK]',$link,$product_layouttem);
					$productlayoutdesign .=  	$product_layouttem;
			}
					$template =	str_replace('[Products]',$productlayoutdesign,$template);

		
		}
	  }
	  elseif($mod=='list')
	  {
	     $template = $row_fb['fb_preview_content'];
	  }
    ?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
       <tr>
          <td colspan="5" align="center" valign="middle" class="tdcolorgray" >
		  <div class="editarea_div">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">		  
		  			        
		 <tr>
           <td align="left" valign="middle" class="tdcolorgray" >Facebook Tab Content<span class="redtext">*</span></td>
		   <td align="left" valign="middle" class="tdcolorgray"></td>
           <td colspan="2" align="right" valign="middle" class="tdcolorgray" ></td>
           <td align="left" valign="middle" class="tdcolorgray"></td>
    </tr>		 
		 <tr>
		   <td colspan="5" align="left" valign="top" class="tdcolorgray"><?php    
		   
						$editor_elements = "fb_content";
						include_once("js/tinymce.php");										       
		?>
		<textarea style="height:500px; width:750px" id="fb_review_content" name="fb_review_content"><?=stripslashes($template)?></textarea>
		</td>
    </tr>		 
		 <tr>
		   <td colspan="5" align="left" valign="middle" class="tdcolorgray" ></td>
    </tr>
		 <tr>
		  <td colspan="5" align="left" valign="middle" class="tdcolorgray" ><table width="100%" border="0" class="listingtable">
             <tr >
               <td colspan="3" class="helpmsgtd" align="left"><div class="helpmsg_divcls"><?=get_help_messages('NEWSLETTER_CODE_REPLACE')?></div></td>
             </tr>
             <tr class="listingtableheader">
               <td width="17%"><div align="left"><strong>&nbsp; Code</strong></div></td>
               <td width="5%">&nbsp;</td>
               <td width="78%"><div align="left"><strong>&nbsp; Description</strong></div></td>
             </tr>
             <?PHP 
               $templatetab = array(get_help_messages('LIST_FACEBOOK_CODE_PRODUCT_MESS1')=>'[Products]');						

			 	foreach($templatetab AS $key=>$val) {
			 ?>
             <tr class="listingtablestyleB">
               <td align="left" > &nbsp; <?PHP echo $val; ?></td>
               <td>=&gt;</td>
               <td align="left">&nbsp; <?PHP echo $key; ?></td>
             </tr>
             <?PHP } ?>
           </table></td>
    </tr>
	</table>
	</div>
	</td>
	</tr>	
	<tr>
		<td align="right" colspan="5" valign="middle" class="tdcolorgray" >
			<div class="editarea_div">
				<table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
				<tr>
					<td align="right" valign="middle">	
						
						<input name="Submit" type="button" class="red" value=" Confirm " onclick="valform(frmfacebookedit,'preview')" />
					</td>
				</tr>
				</table>
			</div>
		</td>
	</tr>		
      </table>
    <?php
	}
	function show_product_list($fb_id,$prod_arr=array(),$alert='')
	{
			global $db,$ecom_siteid ,$ecom_hostname;
			//Define constants for this page
			$table_name='products';
			$page_type='Products';
			$help_msg = get_help_messages('EDIT_PROD_FB_ASSMORE');
			$help_msg = str_replace("[productname]",$showprodname,$help_msg);
			$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmfacebookedit,\'checkbox_link[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmfacebookedit,\'checkbox_link[]\')"/>','Slno.','Product','Category','Hide');
			$header_positions=array('center','left','left','left','left');
			$colspan = count($table_headers);

			//#Search terms
			$search_fields = array('productname_link','categoryid_link','perpage_pop');

			$query_string = "request=facebook_tab&fpurpose=show_product_popup&curtab=".$_REQUEST['curtab']."&perpage_pop=".$_REQUEST['perpage_pop']."&pss_records_per_page=".$_REQUEST['pss_records_per_page']."&pss_pg=".$_REQUEST['pss_pg']."&pss_start=".$_REQUEST['pss_start'];
			foreach($search_fields as $v) {
			$query_string .= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
			}

			//#Sort
			$sort_by_link = (!$_REQUEST['sort_by_link'])?'product_name':$_REQUEST['sort_by_link'];
			$sort_order_link = (!$_REQUEST['sort_order_link'])?'ASC':$_REQUEST['sort_order_link'];
			$sort_options = array('product_name' => 'Name','manufacture_id'=>'Manufacture Id','product_costprice'=>'Cost Price','product_webprice'=>'Web Price');
			$sort_option_txt = generateselectbox('sort_by_link',$sort_options,$sort_by_link);
			$sort_by_txt= generateselectbox('sort_order_link',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order_link);

			//#Search Options
			 if(!count($prod_arr))
			 {
				 $prod_arr = array('-1');
			 }
			$where_conditions = "WHERE sites_site_id=$ecom_siteid AND product_id NOT IN (".implode(",",$prod_arr).") ";

			// Product Name Condition
			if($_REQUEST['productname_link'])
			{
			$where_conditions .= " AND ( product_name LIKE '%".add_slash($_REQUEST['productname_link'])."%') ";
			}

			// ==================================================================================================
			// Case if category or vendor is selected 
			// ==================================================================================================
			if ($_REQUEST['categoryid_link'] )
			{

			$count_check ='Y';
			$catinclude_prod		= array(0);
			$vendinclude_prod		= array(0);
			if($_REQUEST['categoryid_link']) // case if category is selected
			{
			// Get the id's of products under this category
			$sql_catmap = "SELECT products_product_id FROM product_category_map WHERE product_categories_category_id=".$_REQUEST['categoryid_link'];
			$ret_catmap = $db->query($sql_catmap);
			if ($db->num_rows($ret_catmap))
			{
			while ($row_catmap = $db->fetch_array($ret_catmap))
			{
				$catinclude_prod[] = $row_catmap['products_product_id'];
			}
			}
			else
			{
			/*	$catinclude_prod		= array(0);
				$vendinclude_prod		= array(0);*/
				$count_check='N';
			}
			}	
			$include_prod = array();
			if($count_check=='Y')
			{
			if(count($catinclude_prod)>1)
			{			
			$include_prod = $catinclude_prod;
			}else{
			$include_prod[] = -1;
			}
			}
			else
			{
			$include_prod[] = -1;
			}	
			if (count($include_prod))
			{
			$include_prod_str = implode(",",$include_prod);
			$where_conditions .= " AND ( product_id IN ($include_prod_str)) ";
			}
			}
			// ==================================================================================================
			// ==================================================================================================

			//#Select condition for getting total count
			$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
			$res_count = $db->query($sql_count);
			list($numcount) = $db->fetch_array($res_count);#Getting total count of records
			/////////////////////////////////For paging///////////////////////////////////////////
			$records_per_page = (is_numeric($_REQUEST['perpage_pop']) and $_REQUEST['perpage_pop'] and $_REQUEST['perpage_pop']>1)?intval($_REQUEST['perpage_pop']):30;#Total records shown in a page
			$pg = $_REQUEST['pg'];
			$pg = !($pg)?1:$pg;		
	if (!($pg > 0) || $pg == 0) { $pg = 1; }
	$pages = ceil($numcount / $records_per_page);//#Getting the total pages
	
	if($pg >= 1)
	{
	   $page = $pg ;
	   $start = $records_per_page * ($pg-1) ;
	}
	else
	{
	   $page = 0;
	   $start = 0;
	}
	$page  = $pg;
	$next  = $pg+1;
	$prev  = $pg-1;
			/////////////////////////////////////////////////////////////////////////////////////
			 $sql_qry = "SELECT * FROM products	$where_conditions ORDER BY $sort_by_link $sort_order_link LIMIT $start,$records_per_page ";
			$ret_qry = $db->query($sql_qry);
			?>
			<div class="popup_category_scrolldiv">

			<table border="0" cellpadding="0" cellspacing="0" class="maintable">

			<tr>
			<td height="48" colspan="2" class="sorttd">

			<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
			<td width="66%" align="left" valign="top">
			<div class="editarea_div">
			<table width="100%" border="0" cellspacing="1" cellpadding="1">
			<tr>		
			  <td width="6%" height="30" align="left">Category</td>
			  <td width="11%" height="30" align="left">
			  <?php
				$cat_arr = generate_category_tree(0,0);
				if(is_array($cat_arr))
				{
					echo generateselectbox('categoryid_link',$cat_arr,$_REQUEST['categoryid_link']);
				}
			  ?>			  </td>
			  <td colspan="3"  align="left" valign="middle"><div class="close_pop_div" ><img src="images/close_cal.png" onclick="call_cancel()" title="Click here to close" /></div></td>
			</tr>	
			<tr>
			  <td width="15%" height="30" align="left">Product Name</td>
			  <td width="16%" height="30" align="left"><input name="productname_link" type="text" class="textfeild" id="productname_link" value="<?php echo $_REQUEST['productname_link']?>" /></td>
			  <td width="20%" height="30" align="left">Records Per Page</td>
			  <td width="7%" height="30" align="left"><input name="perpage_pop" type="text" class="textfeild" id="perpage_pop" size="3" maxlength="3" value="<?php echo $records_per_page?>" /></td>
			  
			  <td width="8%" height="30" align="left"><input name="Search_go" type="button" class="red" id="Search_go" value="Go" onclick="ajax_search(<?php echo $fb_id?>)" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_LINKED_ASSGO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			</tr>
			<?php

			if ($db->num_rows($ret_qry))
				{
					?>
					<tr>
					<td colspan="<?php echo $colspan?>" align="center" valign="middle" class="sorttd">
					<?php 
					if( $page > 0 )
					{
					   $prev = $page - 1;
					   $next_s = "<input type=\"button\"  value=\"Next\" id=\"Next\" class=\"red\" name=\"next\" onclick=\"call_ajax_page($fb_id,$next)\">";
					   $prev_s = "<input type=\"button\"  value=\"Prev\" id=\"prev\" class=\"red\" name=\"prev\" onclick=\"call_ajax_page($fb_id,$prev)\">&nbsp;";
					  if( $prev>0)
						echo $prev_s;
					}?>
			&nbsp;&nbsp;
					<?php echo $numcount;?> Product(s) found. Page
			<b><?php echo $page;?></b>
			of
			<b><?php echo $pages ?></b>&nbsp;&nbsp;<?php
			if( $page > 0 )
			{
			$next_s = "<input type=\"button\"  value=\"Next\" id=\"Next\" class=\"red\" name=\"next\" onclick=\"call_ajax_page($fb_id,$next)\">";
			if($pages>=$next)
			echo $next_s;
			}
			else if( $page == 0 )
			{
			echo $next_s;
			}
			?>

					</td>
					</tr>
			<?php
				}	
			?>

			</table> 
			</div>           </td>
			</tr>
			</table>      </td>
			</tr>
			<tr>
			<td colspan="2" class="listingarea">
			<div class="listingarea_div">
			<table  border="0" cellpadding="0" cellspacing="0" class="listingtable">	  
			<tr>
			<td align="right" valign="middle" class="listeditd" colspan="<?php echo $colspan?>">
			<input name="Assignmore_tab" type="button" class="red" id="Assignmore_tab" value="Assign Selected" onclick="assign_selected(<?php echo $fb_id ?>)" /></td>
			</tr>
			<?php  
			echo table_header($table_headers,$header_positions); 
			if ($db->num_rows($ret_qry))
			{ 
			$srno = getStartOfPageno($records_per_page,$pg);
			while ($row_qry = $db->fetch_array($ret_qry))
			{
				$cls 		= ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
				if($row_qry['product_discount']>0)
				{
					$disctype	= ($row_qry['product_discount_enteredasval']==0)?'%':'';
					$discval_arr= explode(".",$row_qry['product_discount']);
					if($discval_arr[1]=='00')
						$discval = $discval_arr[0];
					else
						$discval = $row_qry['product_discount'];
					$disc		= $discval.$disctype;
				}	
				else
					$disctype = $disc = '--';
			?>
				<tr>
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="10%">
				  <input type="checkbox" name="checkbox_link[]" id="checkbox_link[]" value="<?php echo $row_qry['product_id']?>" /></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="1%"><?php echo $srno++?></td>
				  <td align="left" valign="middle" class="<?php echo $cls?>" width="20%"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row_qry['product_id']?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="edit" class="edittextlink"><?php echo stripslashes($row_qry['product_name'])?></a></td>
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
				  <td align="center" valign="middle" class="<?php echo $cls?>">
					<?php
						echo ($row_qry['product_hide']=='Y')?'Yes':'No';	
					?>				</td>
				</tr>
			<?php
			}
			}
			else
			{
			?>	
			<tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>">
					No unassigned Products found.				  </td>
			</tr>	  
			<?php
			}
			?>
			
			</table></div></td>
			</tr>

			</table>
			</div>
			<?php

}
?>

<?php
/*############################################################################
	# Script Name 	: myfavoritesHtml.php
	# Description 	: Page which holds the display logic for listing my favorite categories and products
	# Coded by 		: ANU
	# Created on	: 02-Feb-2008
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class myfavories_Html
	{
		// Defining function to show the site review
		function Show_Myfavorites($alert)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr;
			 $customer_id 					= get_session_var("ecom_login_customer");
			$Captions_arr['MY_FAVORITES'] = getCaptions('MY_FAVORITES'); // to get values for the captions from the general settings site captions
			
			$sql_tot_fav_categories = "SELECT count(id) 
								FROM 
									product_categories pc,customer_fav_categories cfc 
								WHERE
									 pc.category_id = cfc.categories_categories_id and 
							cfc.sites_site_id = $ecom_siteid  and cfc.customer_customer_id= $customer_id";
			$ret_totfav_categories = $db->query($sql_tot_fav_categories);
			list($tot_cntcateg) 	= $db->fetch_array($ret_totfav_categories); 
			$categperpage	=	2;
			$pg_variablecateg	= 'categ_pg';
			if ($_REQUEST['req']!='')// LIMIT for Favorites is applied only if not displayed in home page
						{
							$start_varcateg		= prepare_paging($_REQUEST[$pg_variablecateg],$categperpage,$tot_cnt);
							$Limit			= " LIMIT ".$start_varcateg['startrec'].", ".$categperpage;
						}	
						else
							$Limit = '';
						
							
			echo $sql_fav_categories = "SELECT category_id,category_name,category_shortdescription,
										  categories_categories_id,id 
								FROM 
									product_categories pc,customer_fav_categories cfc 
								WHERE
									 pc.category_id = cfc.categories_categories_id and 
							cfc.sites_site_id = $ecom_siteid  and cfc.customer_customer_id= $customer_id
								$Limit	";
				$ret_fav_categories = $db->query($sql_fav_categories);
				/************************FOR FAVORITE PRODCUTS***************************************8888************/
				/*$sql_tot_fav_products = "SELECT count(id) 
								FROM 
									products p,customer_fav_products cfp 
								WHERE
									 p.product_id = cfp.products_product_id and 
							cfp.sites_site_id = $ecom_siteid  and cfp.customer_customer_id= $customer_id";
			$ret_totfav_products = $db->query($sql_tot_fav_products);
			list($tot_cntprod) 	= $db->fetch_array($ret_totfav_products); 
			$prodperpage	=	2;
			$pg_variableprod	= 'prod_pg';
			if ($_REQUEST['req']!='')// LIMIT for Favorites is applied only if not displayed in home page
						{
							$start_varprod 		= prepare_paging($_REQUEST[$pg_variableprod],$prodperpage,$tot_cnt);
							$Limit			= " LIMIT ".$start_varprod['startrec'].", ".$prodperpage;
						}	
						else
							$Limit = '';
						
							
			$sql_fav_products = "SELECT id,product_name, products_product_id
								FROM 
									products p,customer_fav_products cfp
								WHERE
									 p.product_id = cfp.products_product_id and 
							cfp.sites_site_id = $ecom_siteid  and cfp.customer_customer_id= $customer_id
								$Limit	";
				$ret_fav_products = $db->query($sql_fav_products);*/
				
			

?>
<script language="javascript" type="text/javascript">

	</script>
			<form method="post" action="" name="frm_myfavorites" id="frm_myfavorites" class="frm_cls" onsubmit="return validate_allforms(this);">
			<input type="hidden" name="fpurpose" id="fpurpose" value="" />
			<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?=$Captions_arr['MY_FAVORITES']['FAVORITES_TREEMENU_TITLE']?></div>
		
		<table width="100%" border="0" cellpadding="0" cellspacing="4"  class="favoritecategorytable">
		<?php if($alert){ ?>
			<tr>
				<td colspan="3" class="errormsg" align="center">
				<?php 
						  if($Captions_arr['CUST_REG'][$alert]){
						  		echo "Error !! ". $Captions_arr['CUST_REG'][$alert];
						  }else{
						  		echo  "Error !! ". $alert;
						  }
				?>
				</td>
			</tr>
		<?php } 
		
		?>
		<tr>
			<td colspan="3" class="regiheader"><?=$Captions_arr['MY_FAVORITES']['FAVORITES_CATEGORY_HEADING']?></td>
		</tr>
		
		<tr><td colspan="3">
		<table border="0" cellpadding="0" cellspacing="2" width="100%">
		<tr>
			<td colspan="3"  align="center" valign="middle" class="pagingcontainertd" ><?php 
			$path = '';
		$query_string .= "";
		paging_footer($path,$query_string,$tot_cntcateg,$start_varcateg['categ_pg'],$start_varcateg['pages'],'',$pg_variablecateg,'Favorite categories',$pageclass_arr); 			
			?></td>
			
		</tr>
		<tr>
			<td width="18%" align="left" valign="middle" class="favtableheader">Sl No</td>
			<td width="63%" align="left" valign="middle"  class="favtableheader">Category name</td>
			<td width="19%" align="left" valign="middle"  class="favtableheader">action</td>
		</tr>
		<?php
		$i=0;
		 while($fav_categories = $db->fetch_array($ret_fav_categories)) {
		$i++;?>
		<tr>
			<td width="18%" align="left" valign="middle" class="favcontent"><?=$i?></td>
			<td width="63%" align="left" valign="middle" class="favcontent"><?=$fav_categories['category_name']?></td>
			<td width="19%" align="left" valign="middle" class="favcontent"><a href="remove-favorite-category-<?=$fav_categories['id']?>.html" >remove </a></td>
		</tr>
		
		<? }?>
		
		</table>
		</td></tr>
		<tr>
			<td align="left" valign="middle" class="regiconent">&nbsp;
				
		  </td>
			<td align="left" valign="middle">&nbsp;</td>
			<td align="left" valign="middle" class="regiconent">&nbsp;
				
			</td>
				
		</tr>
	</table>
		<?php /**************FOR LISTIN OF FAVORITE PRODUCTS****************************************/?>
		
		
			</form>
			
		
		
			
			
			
			<div class="pagenavcontainer" >
                  <ul class="pagenavul" >
                    <li ><a href="ASDAS">&laquo; Pre</a> </li>
                    <li ><a href="asdas">1</a></li>
                    <li class="selected" >2</li>
                    <li ><a href="asdasd">3</a></li>
                    <li ><a href="zXDzs">4</a></li>
                    <li ><a href="ASDFASD">4</a></li>
                    <li ><a href="SDGFSD">5</a></li>
                    <li ><a href="SDGFSD">Next &gt;&gt;</a></li>
                  </ul>
              </div>
			
		
			
		
		<?php	
		}
		function Display_Message($mesgHeader,$Message){
		global $Captions_arr;
		?>
		<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regifontnormal">
      <tr>
        <td width="7%" align="left" valign="middle" class="message_header" > 
       	  <?php
		 	   echo $mesgHeader;
			   ?></td>
      
      </tr>
      <tr>
        <td align="left" valign="middle" class="message"><?php echo $Message; ?></td>
        
      </tr>
	   <?php if(get_session_var("ecom_login_customer")){?>
	   <tr>
        <td  valign="middle" class="regiconent" align="center"><a href="<?=$ecom_hostname?>/myprofile.html"><?=$Captions_arr['CUST_REG']['CUSTOMER_UPDATE_SUCESSFULL_GO_BACK_LINK'];?></a> <?=$Captions_arr['CUST_REG']['CUSTOMER_UPDATE_SUCESSFULL_GO_BACK_TEXT'];?></td>
        
      </tr>
	  <? }?>
           </table>
		<?php	
		}
		
	};	
?>
	<?php 
	



?>
			
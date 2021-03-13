<?php
	/*############################################################################
	# Script Name 	: staticpageHtml.php
	# Description 	: Page which holds the display logic for Static pages
	# Coded by 		: Anu
	# Created on	: 22-Feb-2008
	# Modified by	: ANU
	# Modified On	: 22-Feb-2008
	##########################################################################*/
	class static_Html
	{
		// Defining function to show the selected static pages
		function Show_StaticPage($row_statpage)
		{
			global 	$Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,
					$ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr,$vImage,$publickey;
			// ** Fetch any captions for product details page
			$Captions_arr['STATIC_PAGES'] 	= getCaptions('STATIC_PAGES');
			
			// ** Fetch the product details
			//$row_statpage	= $db->fetch_array($ret_statpage);
			
			// ** Check to see whether current user is logged in 
			$cust_id 	= get_session_var("ecom_login_customer");
			
			
			// ======================================================
			$captcha_code = recaptcha_get_html($publickey, $error);
			
			
		?>
		<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?=$row_statpage['title']?></div>
		<form method="post" name="frm_staticpage" id="frm_staticpage" action="" class="frm_cls">
		<input type="hidden" name="fpurpose" value="" />
		<input type="hidden" name="fproduct_id" value="<?php echo $_REQUEST['product_id']?>" />
			<table border="0" cellpadding="0" cellspacing="0" class="staticpagetable">
			
			<tr>
			  <td valign="top" class="staticpagecontent">
			  <?php 
			  //echo $_REQUEST['garraways_Vimg'];
			  $content = str_replace("[VIMAGE]","<img src=\"includes/vimg.php?size=4&pass_vname=garraways_Vimg\" border=\"0\" alt=\"Image Verification\"/><br />".$vImage->showCodBox(0,'garraways_Vimg','class="inputA_imgver"') ,$row_statpage['content']);
			  if($row_statpage['allow_auto_linker'] == 1) {
				//echo 't';
				echo auto_linker(stripslashes($content));
			      } else {
				echo str_replace('[captcha_here]',$captcha_code,stripslashes($content));
			       } 
			  //echo stripslashes($row_statpage['content'])
			  ?>
			  </td>
			  
			</tr>
			
		  </table>
 		</form>
		
				<table border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
				<tr>
				<td align="center">
						<?php 
						include ("includes/base_files/combo_middle.php");
						?>
						</td>
				</tr>
				<tr>
				<td align="center">
				<?php 
						$sql_inline = "SELECT display_order,display_component_id,display_id,display_title 
				FROM 
					display_settings a,features b 
				WHERE 
					a.sites_site_id=$ecom_siteid 
					AND a.display_position='middle' 
					AND b.feature_allowedinmiddlesection = 1  
					AND layout_code='".$default_layout."' 
					AND a.features_feature_id=b.feature_id 
					AND b.feature_modulename='mod_shelf' 
				ORDER BY 
						display_order 
						ASC";
			$ret_inline = $db->query($sql_inline); // to dispplay the advert for the category page
			if ($db->num_rows($ret_inline))
			{
				while ($row_inline = $db->fetch_array($ret_inline))
				{
					//$modname 			= $row_inline['feature_modulename'];
					$body_dispcompid	= $row_inline['display_component_id'];
					$body_dispid		= $row_inline['display_id'];
					$body_title			= $row_inline['display_title'];
					include ("includes/base_files/shelf.php");
				}
			}
		
				?>
				</td>
				</tr>	
				</table>
	<?php	
		}
		
	};	
?>

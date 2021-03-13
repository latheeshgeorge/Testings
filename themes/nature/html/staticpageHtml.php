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
					$ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr,$site_key;
			// ** Fetch any captions for product details page
			$Captions_arr['STATIC_PAGES'] 	= getCaptions('STATIC_PAGES');
			
			// ** Fetch the product details
			//$row_statpage	= $db->fetch_array($ret_statpage);
			
			// ** Check to see whether current user is logged in 
			$cust_id 	= get_session_var("ecom_login_customer");
			
		?>
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<div class="treemenu">
          <ul>
            <li><a href="<? url_link('');?>" title="<?=stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']);?>"><?=stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']);?></a> &gt;&gt; </li>
            <li><?=stripslash_normal($row_statpage['title'])?></li>
          </ul>
        </div>
		<form method="post" name="frm_staticpage" id="frm_staticpage" action="" class="frm_cls">
		<input type="hidden" name="fpurpose" value="" />
		<input type="hidden" name="fproduct_id" value="<?php echo $_REQUEST['product_id']?>" />
		 <div class="inner_contnt" >
        <div class="inner_contnt_top"></div>
		<div class="inner_contnt_middle">
		<div class="inner_contnt_hdr"><h1><?=stripslash_normal($row_statpage['title'])?></h1></div>
			<table border="0" cellpadding="0" cellspacing="0" class="statictable">
			<tr>
			  <td valign="top">
			  <?php 
			   $site_key_rep ='<div class="g-recaptcha" data-sitekey="'.$site_key.'"></div>'; 
					$sr_array  = array('rgb(0, 0, 0)','#000000','[site_key]');
				$rep_array = array('rgb(255,255,255)','#ffffff',$site_key_rep); 
				$stat_cont = str_replace($sr_array,$rep_array,stripslashes($row_statpage['content']));

			  echo stripslashes($stat_cont)?>
			  </td>
			  
			</tr>
			
		  </table>
			</div>
			<div class="inner_contnt_bottom"></div>
			</div> 
 		</form>

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

	<?php	
		}
		
	};	
?>
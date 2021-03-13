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
					$ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr;
			// ** Fetch any captions for product details page
			$Captions_arr['STATIC_PAGES'] 	= getCaptions('STATIC_PAGES');
			
			// ** Fetch the product details
			//$row_statpage	= $db->fetch_array($ret_statpage);
			
			// ** Check to see whether current user is logged in 
			$cust_id 	= get_session_var("ecom_login_customer");
			$HTML_img = $HTML_alert = $HTML_treemenu='';
			$HTML_treemenu = '<div class="tree_menu_conA">
								  <div class="tree_menu_topA"></div>
								  <div class="tree_menu_midA">
									<div class="tree_menu_content">
									   <ul class="tree_menu">
										<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a> | </li>
										 <li>'.stripslash_normal($row_statpage['title']).'</li>
										</ul>
									  </div>
								  </div>
								  <div class="tree_menu_bottomA"></div>
								</div>';
			echo $HTML_treemenu;
			//if($row_statpage['content']!='')
			{
				if($row_statpage['page_id']!=50475)
				{
		?>
		<form method="post" name="frm_staticpage" id="frm_staticpage" action="" class="frm_cls">
		<input type="hidden" name="fpurpose" value="" />
		<input type="hidden" name="fproduct_id" value="<?php echo $_REQUEST['product_id']?>" />
		 <div class="inner_contnt" >
        <div class="inner_contnt_top"></div>
		<div class="inner_contnt_middle">
			<table border="0" cellpadding="0" cellspacing="0" class="statictable">
			<tr>
			  <td valign="top">
			  <?php 
			  echo stripslashes($row_statpage['content'])?>
			  </td>
			  
			</tr>
			
		  </table>
			</div>
			<div class="inner_contnt_bottom"></div>
			</div> 
 		</form>
         <?php
			}
			else
			{
				// Get the id of the category with the name "Gallery_holder_hidden"
				$sql="SELECT category_id FROM product_categories WHERE sites_site_id = $ecom_siteid AND category_name ='Gallery_holder_hidden' LIMIT 1";
				$ret = $db->query($sql);
				$pass_type = 'image_bigcategorypath';
				if($db->num_rows($ret))
				{
					$row = $db->fetch_array($ret);
					$catimg_arr = get_imagelist('prodcat',$row['category_id'],$pass_type,0,0);
					
				
			?>
				<link href="http://<?php echo $ecom_hostname?>/images/lightbox.css" media="screen" type="text/css" rel="stylesheet" />
				<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/prototype/1.6.0.2/prototype.js"></script>
				<script type="text/javascript" src="http://<?php echo $ecom_hostname?>/images/lightbox.js"></script>			
				<script type="text/javascript" src="http://<?php echo $ecom_hostname?>/images/effects.js"></script>			
					<style type="text/css">
					.main_div1 {
						text-align:center;
						width:100%;
					}
					
					.img_div1{
						vertical-align: middle;
						padding:2px;
						height:310px;
						width:280px;
						display: table-cell;
						margin:auto;
					}
					
					.repat_div1{
						width: 175px;
						height:auto;
						float:left;
						border:1px solid #DCDCDC;
						text-align:center;
						
					}
					.helper {
						display: inline-block;
						vertical-align: middle;
					}
					.img_cap1
					{
						width:99%;
						height:50px;
						text-align:center;
						font-weight:bold;
						font-size:11px;
						background-color:#DCDCDC;
						color:#7a7a7a;
						margin:1px; 
						padding:2px 2px 2px 2px;
					}
					.img_div1  img {
						display: block;
						margin-left: auto;
						margin-right: auto;
						vertical-align: middle;
						text-align:center;
						border:1px solid #DCDCDC;
						max-width: 91%;
					}
					
					
					
					.img_div1 {
    vertical-align: middle;
    padding: 2px;
    height: 310px;
    width: 100% !important;

    margin: auto;
}


#outerImageContainer {
      position: relative;
    background-color: #000;
    border: 1px solid #999;
    width: 250px;
    height: 100% !important;
    margin: 0 auto;
    width: 89% !important;

}


#lightbox {
    position: absolute;
    left: 0;
    width: 100% !important;
    z-index: 100;
    text-align: center;
    line-height: 0;
    z-index: 9999999;
}
#imageContainer img{border: none;
vertical-align: middle;
max-width: 100% !important;
max-height:100% !important;
}
					
					
					</style>
					<center>
					<div class="main_div1">
					<?php
					foreach ($catimg_arr as $ks=>$vm)
					{
					?>
						<div class="repat_div1">
							<div class="img_div1"><a href="<?php url_root_image($vm['image_extralargepath'])?>" rel="lightbox[gallery]" title="<?php echo $vm['image_title'];?>"><img  src="<?php url_root_image($vm['image_bigcategorypath'])?>" title="<?php echo $vm['image_title'];?>"></a></div>
							<div class="img_cap1"><?php echo $vm['image_title'];?></div>
						</div>
					<?php   
					}
					?>
					</div>
					</center>
					<?php
				}
			?>	
			<?php	
			}
				
				
				
				
        
        
		} 
				
		
				?>

	<?php	
		}
		
	};	
?>

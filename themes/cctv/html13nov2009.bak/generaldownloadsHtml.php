<?php
	/*############################################################################
	# Script Name 		: generaldownloadsHtml.php
	# Description 		: Page which holds the display logic for general downloads
	# Coded by 			: Sny
	# Created on		: 25-Aug-2009
	# Modified by		: 
	# Modified On		: 
	##########################################################################*/
	class download_Html
	{
		// Defining function to show the downloads
		function Show_Downloads($title,$ret_download)
		{
			global 	$Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,
					$ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr;
			// ** Fetch any captions 
			$Captions_arr['GENERAL_DOWNLOADS'] 	= getCaptions('GENERAL_DOWNLOADS');
	?>
			<div class="tree_con">
			<div class="tree_top"></div>
				<div class="tree_middle">
					<div class="pro_det_treemenu">
						<ul>
						<li><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> &gt;&gt;</li>
						<li><?php echo stripslash_normal($Captions_arr['GENERAL_DOWNLOADS']['GENERAL_DOWNLOADS_HEAD'])?></li>
						</ul>
					</div>
				</div>
			<div class="tree_bottom"></div>
			</div>
			<div class="round_con">
			<div class="round_top"></div>
			<div class="round_middle">
		<?php 
			$sql_gen = "SELECT general_download_topcontent 
							FROM 
								general_settings_sites_common 
							WHERE 
								sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$ret_gen = $db->query($sql_gen);
			if($db->num_rows($ret_gen))
			{
				$row_gen = $db->fetch_array($ret_gen);
			} 
			if($row_gen['general_download_topcontent']!='')
			{
			?>
				<div class="faq_desc">
			<?php	
					echo stripslash_normal($row_gen['general_download_topcontent']);
			?>
				</div>
			<?php	
			}
			// Showing the questions and answers
			while ($row_download = $db->fetch_array($ret_download))
			{
				$gen = base64_encode($row_download['download_id']);
		?>	
				<ul class="faq_ans">
				<li class="faqqst"><?php echo stripslash_normal($row_download['download_title'])?></li>
				<li class="faqcontent">
				<?php echo stripslash_normal($row_download['download_desc'])?>
				</li>
				<li class="faqtop"><a href="javascript:download_general_file('<?php echo $gen?>','<?php echo $ecom_hostname?>')" title="<?php echo stripslash_normal($Captions_arr['GENERAL_DOWNLOADS']['GENERAL_DOWNLOADS_DOWNLOAD'])?>"><img src="<?php url_site_image('general_download.gif')?>" border="0" alt="<?php echo stripslash_normal($Captions_arr['GENERAL_DOWNLOADS']['GENERAL_DOWNLOADS_DOWNLOAD'])?>" title="<?php echo stripslash_normal($Captions_arr['GENERAL_DOWNLOADS']['GENERAL_DOWNLOADS_DOWNLOAD'])?>" /></a></li>
				</ul>
	<?php	
			}
	?>
		</div>
		<div class="round_bottom"></div>
		</div>
	<?php		
		}
	};	
?>
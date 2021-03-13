<?php
/*############################################################################
	# Script Name 	: advertHtml.php
	# Description 	: Page which holds the display logic for middle adverts
	# Coded by 		: Sny
	# Created on	: 28-Dec-2007
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class advert_Html
	{
		// Defining function to show the featured property
		function Show_Adverts($title,$advert_arr)
		{ 
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr;
			
			if (count($advert_arr))
			{
				
				foreach ($advert_arr as $d=>$k)
				{
					$active 	= $k['advert_activateperiodchange'];
					if($active==1)
					{
						$proceed	= validate_component_dates($k['advert_displaystartdate'],$k['advert_displayenddate']);
					}
					else
						$proceed	= true;	
					if($proceed)
					{
					if ($title and $k['advert_type']!='TXT' and $k['advert_type']!='IMG' and $k['advert_type']!='ROTATE')
					{
						?>
						<div class="advertheader" align="left">
						<?php echo $title?>
						</div>
						<?php
					}
					if ($k['advert_type']!='TXT')
					{
					?>
					<div class="adverttop" align="<?php echo ($k['advert_type']=='IMG')?'left':'center'?>">
					<?php
					}
					switch ($k['advert_type'])
					{
						case 'IMG':
							if($title!='')
								echo '<div class="mid_shelfA_name">'.$title.'</div>';
							$path = url_root_image('adverts/'.$k['advert_source'],1);
							$link = $k['advert_link'];
							if ($link!='')
							{
								?>
								<a href="<?php echo $link?>" title="<?php echo $title?>" target="<?=$k['advert_target']?>">	
								<?php
							}
							?>
							<img src="<?php echo $path?>" alt="<?php echo stripslashes($k['advert_title'])?>" title="<?php echo stripslashes($k['advert_title'])?>" border="0" />
							<?php		
							if ($link!='')
							{
								?>
								</a>
								<?php		
							}
						break;
						case 'SWF':
							$path = url_root_image('adverts/'.$k['advert_source'],1);
							$link = $k['advert_link'];
							$flash_path =  '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="518" height="95">
							<param name="movie" value='.$path.'  >
							<param name="quality" value="high" >
							<param name="BGCOLOR" value="#D6D8CB"><embed src='.$path.' type=application/x-shockwave-flash width = 518 height=95> </object>';
							$img_link=  '';
							echo  $flash_path ;
						break;
						case 'PATH':
							$path = $k['advert_source'];
							$link = $k['advert_link'];
							if ($link!='')
							{
								?>
								<a `href="<?php echo $link?>" title="<?php echo stripslashes($k['advert_title'])?>" target="<?=$k['advert_target']?>">	
								<?php
							}
							?>
							<img src="<?php echo $path?>" alt="<?php echo stripslashes($k['advert_title'])?>" title="<?php echo stripslashes($k['advert_title'])?>" border="0" />
							<?php		
							if ($link!='')
							{
								?>
								</a>
								<?php		
							}
						break;
						case 'TXT':
							$path = $k['advert_source'];
							?>
							 <table width="100%" border="0" cellpadding="0" cellspacing="0" class="mid_ad_table" >
							  <tr>
								<td class="mid_ad_top_lf">&nbsp;</td>
								<td class="mid_ad_top_mid"><?php echo $title?></td>
								<td class="mid_ad_top_rt">&nbsp;</td>
							  </tr>
							  <tr>
								<td colspan="3" class="mid_ad_mid"><div class='advert_text_middle'><?php echo stripslashes($path);?></div></td>
								</tr>
							  <tr>
								<td class="mid_ad_btm_lf">&nbsp;</td>
								<td class="mid_ad_btm_mid">&nbsp;</td>
								<td class="mid_ad_btm_rt">&nbsp;</td>
							  </tr>
							</table>
							<?php
							break;
							case 'ROTATE':   // case if ad rotate images are set
								
								$advert_ul_id = uniqid('advert_');
								// get the list of rotating images
								 $sql_rotate = "SELECT rotate_image,rotate_link, rotate_alttext  
													FROM 
														advert_rotate 
													WHERE 
														adverts_advert_id = ".$k['advert_id']." 
													ORDER BY 
														rotate_order ASC";
								$ret_rotate = $db->query($sql_rotate);
								if($db->num_rows($ret_rotate))
								{
								   $HTML_Content1 .= '<script type="text/javascript">
													jQuery.noConflict();
													var $j = jQuery;
													$j(document).ready(
														function(){
														$j(\'ul#'.$advert_ul_id.'\').innerfade({ 
															speed: 1000,
															timeout: '.($k['advert_rotate_speed']*1000).',
															type: \'sequence\',
															runningclass: \'innerfade_middle\',
															containerheight: \''.$k['advert_rotate_height'].'px\'
														});
													});
													</script>';
									$HTML_Content .= ' <div id="'.$advert_ul_id.'"><ul  class="bjqs">';
									while ($row_rotate = $db->fetch_array($ret_rotate))
									{
										if($row_rotate['rotate_alttext']!='')
										{
										  $alt_text = $row_rotate['rotate_alttext']; 
										}
										else
										{
										   $alt_text = $k['advert_title']; 
										}
										$link = trim($row_rotate['rotate_link']);
										$link_start = $link_end = '';
										if($link!='')
										{
											$link_start     = '<a href="'.$link.'" title="'.stripslashes($k['advert_title']).'">';
											$link_end       = '</a>';
										}
										$HTML_Content .= '
															<li>'.$link_start.'
															<img src="'.url_root_image('adverts/rotate/'.$row_rotate['rotate_image'],1).'" alt="'.stripslashes($alt_text).'" title="'.stripslashes($alt_text).'" />'.
															$link_end.'</li>';
									}
									$HTML_Content .='</ul></div>';
								}
								//echo $HTML_Content;
								?>
								 <script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
    <?php 
								echo "<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/scripts/bjqs-1.3.min.js",1)."\"></script>"; ?>
								<div id="container">

<?php
echo $HTML_Content;
/*
?>
      <!--  Outer wrapper for presentation only, this can be anything you like -->
      <div id="banner-fade">

        <!-- start Basic Jquery Slider -->
        <ul class="bjqs">
          <li><img src="<?php url_site_image('banner1.jpg')?>" title="padmaraj Payyur"></li>
          <li><img src="<?php url_site_image('banner2.jpg')?>" title="Automatically generated caption"></li>
          <li><img src="<?php url_site_image('banner3.jpg')?>" title="Automatically generated caption"></li>
        </ul>
        <!-- end Basic jQuery Slider -->

      </div>
      <!-- End outer wrapper -->

*/
?> 
      <script class="secret-source">
		  jQuery.noConflict();
			var $j = jQuery;
			jQuery(document).ready(function($) {

			$j('#<?php echo $advert_ul_id?>').bjqs({
			height      : 554,
			width       : 1000,
			responsive  : true
			});

			});
      </script>


    </div>

 
 

    
								<?php
								
							break;
						};
						if ($k['advert_type']!='TXT')
						{
					?></div><?
						}
					}
					else
					{
						removefrom_Display_Settings($k['advert_id'],'mod_adverts');
					}
				}				
			}
		}
	};	
?>
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
					?>
					<div class="adverts" align="right"> 
					<?php
					switch ($k['advert_type'])
					{
						case 'IMG':
							$path = url_root_image('adverts/'.$k['advert_source'],1);
							$link = $k['advert_link'];
							if ($link!='')
							{
								?>
								<a href="<?php echo $link?>" title="<?php echo $title?>" target="<?=$k['advert_target']?>">	
								<?php
							}
							?>
							<img src="<?php echo $path?>" alt="Advert" title="<?php echo $title?>" border="0" />
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
								<a href="<?php echo $link?>" title="<?php echo $title?>" target="<?=$k['advert_target']?>">	
								<?php
							}
							?>
							<img src="<?php echo $path?>" alt="Advert" title="<?php echo $title?>" border="0" />
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
							echo stripslashes($path);
						break;
						case 'ROTATE':   // case if ad rotate images are set
							$advert_ul_id = uniqid('advert_');
							// get the list of rotating images
							$sql_rotate = "SELECT rotate_image,rotate_link 
												FROM 
													advert_rotate 
												WHERE 
													adverts_advert_id = ".$k['advert_id']." 
												ORDER BY 
													rotate_order ASC";
							$ret_rotate = $db->query($sql_rotate);
							if($db->num_rows($ret_rotate))
							{
							   $HTML_Content .= '<script type="text/javascript">
												$(document).ready(
													function(){
													$(\'ul#'.$advert_ul_id.'\').innerfade({ 
														speed: 1000,
														timeout: '.($k['advert_rotate_speed']*1000).',
														type: \'sequence\',
														containerheight: \''.$k['advert_rotate_height'].'px\'
													});
												});
												</script>';
								$HTML_Content .= ' <div class="advert_middle_rotate"><ul id="'.$advert_ul_id.'">';
								while ($row_rotate = $db->fetch_array($ret_rotate))
								{
									$link = trim($row_rotate['rotate_link']);
									$link_start = $link_end = '';
									if($link!='')
									{
										$link_start     = '<a href="'.$link.'" title="'.$title.'">';
										$link_end       = '</a>';
									}
									$HTML_Content .= '
														<li>'.$link_start.'
														<img src="'.url_root_image('adverts/rotate/'.$row_rotate['rotate_image'],1).'" alt="'.$title.'" />'.
														$link_end.'</li>';
								}
								$HTML_Content .='</ul></div>';
							}
							echo $HTML_Content;
								
						break;
						};
					?></div><?
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
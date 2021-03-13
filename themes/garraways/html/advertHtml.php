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
					if ($title)
					{
						?>
						<div class="advertheader" align="left">
						<?php echo $title?>
						</div>
						<?php
					}
					?><div class="adverttop" align="center">
					<?php
					switch ($k['advert_type'])
					{
						case 'IMG':
							$path = url_root_image('adverts/'.$k['advert_source'],1);
							$link = $k['advert_link'];
							if ($link!='')
							{
								?>
								<a href="<?php echo $link?>" title="<?php echo stripslashes($k['advert_title'])?>" target="<?=$k['advert_target']?>">	
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
								<a href="<?php echo $link?>" title="<?php echo stripslashes($k['advert_title'])?>" target="<?=$k['advert_target']?>">	
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
							<div class='advert_text_middle_live'><?php echo stripslashes($path);?></div>
							<!--<table width="100%" border="0" cellpadding="0" cellspacing="0" class="mid_ad_table" >
							   <tr>
								<td class="mid_ad_top_lf">&nbsp;</td>
								<td class="mid_ad_top_mid">&nbsp;</td>
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
							</table>-->
							<?php
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
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
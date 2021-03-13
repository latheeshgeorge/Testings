<?php
/*############################################################################
	# Script Name 	: advertHtml.php
	# Description 	: Page which holds the display logic for middle adverts
	# Coded by 	: Sny
	# Created on	: 08-Feb-2010
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class advert_Html
	{
            // Defining function to show the advert
            function Show_Adverts($title,$advert_arr)
            {
                global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$special_include;
                $HTML_Content = '';
				$HTML_normaltop_outer = '<div class="home_cont">';
				$HTML_normalbottom_outer = '</div>';
				$HTML_flashtop_outer 	= '<div class="flash_mid_con">';
				
				$HTML_flashbottom_outer	 = '</div>';
				
				$HTML_texttop_outer = '<div class="home_cont_text">';
				$HTML_textbottom_outer = '</div>';
									
                if (count($advert_arr))
                {
                    foreach ($advert_arr as $d=>$k)
                    {
						if ($k['advert_id'])
						{
							$active 	= $k['advert_activateperiodchange'];
							if($active==1)
							{
								$proceed	= validate_component_dates($k['advert_displaystartdate'],$k['advert_displayenddate']);
							}
							else
								$proceed	= true;	
						}
						else
							$proceed = false;		
                        if($proceed)
                        {
                            if ($title and $k['advert_type']!='TXT' and $k['advert_type']!='IMG' and $k['advert_type']!='ROTATE')
                            {
                                $HTML_Content = '
                                                <div classs="advert_mid_header">'.
                                                $title.'
                                                </div>';
                            }                    
                            switch ($k['advert_type'])
                            {
                                case 'IMG': // Case if advert is of type image upload 
                                    $path = url_root_image('nor/img/'.$k['advert_source'],1);
                                    $link = $k['advert_link'];
                                    if ($link!='')
                                    {
                                        $HTML_Content .= '<a href="'.$link.'" title="'.stripslashes($k['advert_title']).'" target="'.$k['advert_target'].'">';
                                    }
                                    $HTML_Content .='<img src="'.$path.'" alt="'.stripslashes($k['advert_title']).'" title="'.stripslashes($k['advert_title']).'" border="0" />';
                                    if ($link!='')
                                    {
                                        $HTML_Content .='</a>';
                                    }
                                break;
                                case 'SWF': // case if advert is of type flash
                                    $path = url_root_image('nor/img/'.$k['advert_source'],1);
                                    $link = $k['advert_link'];
                                    $HTML_Content .= '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="552" height="280">
														<param name="movie" value='.$path.'  >
														<param name="quality" value="high" >
														<param name="BGCOLOR" value="#D6D8CB">
														<param name="wmode" value="transparent">
														<embed src='.$path.' type=application/x-shockwave-flash width=552 height=280 wmode="transparent">
														</object>';
								break;
                                case 'PATH': // case if image url is given as advert
                                    $path = $k['advert_source'];
                                    $link = $k['advert_link'];
                                    if ($link!='')
                                    {
                                        $HTML_Content .= '<a href="'.$link.'" title="'.stripslashes($k['advert_title']).'" target="'.$k['advert_target'].'">'; 
                                    }
                                    $HTML_Content .= '<img src="'.$path.'" alt="'.stripslashes($k['advert_title']).'" title="'.$title.'" border="0" />';
                                    if ($link!='')
                                    {
                                        $HTML_Content .= '</a>';
                                    }
                                break;
                                case 'TXT':  // case if html is set as advert
                                    $path = $k['advert_source'];
                                    $HTML_Content .= stripslash_normal($path);
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
                                       $HTML_Content .= '<script type="text/javascript">
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
                                        $HTML_Content .= '<ul id="'.$advert_ul_id.'">';
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
                                                                <img src="'.url_root_image('rot/img/'.$row_rotate['rotate_image'],1).'" alt="'.stripslashes($alt_text).'" title="'.stripslashes($alt_text).'" />'.
                                                                $link_end.'</li>';
                                        }
                                        $HTML_Content .='</ul>';
                                    }
                                break;
                            };
                            // HTML Follows 
							if($advert_arr[0]['special_include'] == true)
								echo $HTML_Content;
							else
							{  
								if($k['advert_type']=='IMG' && $k['advert_type']=='PATH' )
								{ 
									echo $HTML_normaltop_outer;  
								}
								elseif($k['advert_type']=='SWF' and $k['advert_type']=='ROTATE')	
								{
								    echo $HTML_flashtop_outer;
								}
								else if($k['advert_type']=='TXT')
									echo $HTML_texttop_outer;
                          		echo $HTML_Content;
								
								if($k['advert_type']=='IMG' && $k['advert_type']=='PATH' )
								{ 
									echo $HTML_normalbottom_outer;  
								}
								elseif($k['advert_type']=='SWF' and $k['advert_type']=='ROTATE')	
								{
								    echo $HTML_flashbottom_outer;
								}
								else if($k['advert_type']=='TXT')
									echo $HTML_normalbottom_outer;		
							}
                        }
                        else
                        {
							if($k['advert_id'])
	                            removefrom_Display_Settings($k['advert_id'],'mod_adverts');
                        }
                    }
                }
            }
	};	
?>

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
				$HTML_normaltop_outer = '<div class="'.CONTAINER_CLASS.'"><div class="">';
				$HTML_normalbottom_outer = '</div></div>';
				$HTML_flashtop_outer 	= '<div class="flash_mid_con">';
				
				$HTML_flashbottom_outer	 = '</div>';
										
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
                                    $path = url_root_image('adverts/'.$k['advert_source'],1);
                                    $link = $k['advert_link'];
                                    if ($link!='')
                                    {
                                        $HTML_Content .= '<a href="'.$link.'" title="'.stripslashes($k['advert_title']).'" target="'.$k['advert_target'].'">';
                                    }
                                    $HTML_Content .='<img src="'.$path.'" alt="'.stripslashes($k['advert_title']).'" title="'.stripslashes($k['advert_title']).'" styel="border:0;" />';
                                    if ($link!='')
                                    {
                                        $HTML_Content .='</a>';
                                    }
                                break;
                                case 'SWF': // case if advert is of type flash
                                   
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
                                case 'ROTATE1':   // case if ad rotate images are set
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
                                      
                                        $HTML_Content .= '<section id="slider" class="carousel-wrap">
															<div class="container">
															<div class="row">
															<div id="slider-carousel" class="carousel slide" data-ride="carousel">
						
						
													<div class="carousel-inner">';
													$cnt=0;
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
                                            if($cnt==0)
                                            {
												$cls = 'item active';
												
											}
											else
											{
											  $cls = 'item';
											}
                                            	
											$HTML_Content .= '<div class="'.$cls.'">
														<div class="container">
														'.$link_start.'<img src="'.url_root_image('adverts/rotate/'.$row_rotate['rotate_image'],1).'" alt="'.stripslashes($alt_text).'" title="'.stripslashes($alt_text).'">
															<button type="button" class="btn btn-default get">'.stripslashes($alt_text).'</button>
														</div>
													  </div>';
                                            $cnt++;
                                        }
                                        $HTML_Content .='</div>						
										<a href="#slider-carousel" class="left control-carousel hidden-xs" data-slide="prev">
										<i class="fa fa-angle-left"></i>
										</a>
										<a href="#slider-carousel" class="right control-carousel hidden-xs" data-slide="next">
										<i class="fa fa-angle-right"></i>
										</a>
						</div>					
				</div>
		</div>
	</section>';
                                    }
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
                                      
									$HTML_Content .= ' <header id="myCarousel" class="carousel slide">
									<!-- Indicators -->
									

									<!-- Wrapper for Slides -->
									<div class="carousel-inner">';
												$cnt=0;
									$HTML_indicator ='';
									while ($row_rotate = $db->fetch_array($ret_rotate))
									{
										if($cnt==0)
										$class_active = 'class="active"';
										else
										$class_active = '';
										$HTML_indicator .= '<li data-target="#myCarousel" data-slide-to="'.$cnt.'" '.$class_active.' ></li>';
									
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
										if($cnt==0)
										{
											$cls = 'item active';
											
										}
										else
										{
										  $cls = 'item';
										}
											
										$HTML_Content .= '<div class="'.$cls.'">
													<div class="fill">
													'.$link_start.'<img src="'.url_root_image('rot/img/'.$row_rotate['rotate_image'],1).'" alt="'.stripslashes($alt_text).'" title="'.stripslashes($alt_text).'"></div>
														<div class="carousel-caption">
									<h2>'.stripslashes($alt_text).'</h2>
									</div>
												  </div>';
										$cnt++;
									}
									if($HTML_indicator!='')
									{
									$HTML_Content .='
									<div class="owl_hideA" id="owl_hideA"> 
									<ol class="carousel-indicators">'.$HTML_indicator.'</ol></div>';
								    }
									$HTML_Content .=' </div>

									<!-- Controls -->
									<a class="left carousel-control" href="#myCarousel" data-slide="prev">
									<span class="icon-prev"></span>
									</a>
									<a class="right carousel-control" href="#myCarousel" data-slide="next">
									<span class="icon-next"></span>
									</a>

									</header>';
                                    }
                                    ?>
                                    <script>
								  $( document ).ready(function() {
                                  $('#myCarousel').carousel({
									  interval: 7000,
									  cycle: true
									}); 
								});
                                   </script> <?php
                                break;
                            };
                            // HTML Follows 
							if($advert_arr[0]['special_include'] == true)
								echo $HTML_Content;
							else
							{  
								if($k['advert_type']!='SWF' and $k['advert_type']!='ROTATE')
								{ 
									echo $HTML_normaltop_outer;  
								}	
								else
									echo $HTML_flashtop_outer;
                          		echo $HTML_Content;
								if($k['advert_type']!='SWF' and $k['advert_type']!='ROTATE')
								{ 
									echo $HTML_normalbottom_outer;  
								}	
								else
									echo $HTML_flashbottom_outer;
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

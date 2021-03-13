<table width="100%" border="0" cellspacing="0" cellpadding="0">
				 <?php
			  	if($_REQUEST['src_page']) // show the operations on images only if in normal mod
				{
				?>
					<tr>
                  	<td colspan="2" align="center" class="imageoptionstableheader">
					<br />
					<br />
					<br />
					<input type="submit" name="Submit" value="<?php echo $assign_caption?>" class="red" onclick="call_ajax_handle_assign_remote()"/>
				  	<br />
					<br />
					<br />
					
					<input type="submit" name="Submit" value="<?=$assign_back_caption ?>" class="red" onclick="goback('<? echo $goback; ?>')"/>					</td>
				  	</tr>
				<?php
				}
				else
				{
				?>
                <tr>
                  <td colspan="2" class="imageoptionstableheader">Operations on Image</td>
                </tr>
                <tr>
                  <td colspan="2" class="imageoptionscolorB">
				  <div id="move_dire_div">
				  <?php
				  show_move_directory();
				  ?>
				  </div></td>
                </tr>
                <tr>
                  <td colspan="2" class="imageoptionscolorB">
				  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="imageoptionstable">
                    <tr>
                      <td align="left" valign="top" style="border-top:1px solid #C8C8C8">&nbsp;</td>
                      <td align="left" valign="top" style="border-top:1px solid #C8C8C8">&nbsp;</td>
                    </tr>
                    <tr>
                      <td width="88%" align="left" valign="top">Assign Images to Product Category</td>
                      <td width="12%" align="left" valign="top"><input name="submit_assigncat" type="button" class="blue" value="Go" onclick="call_ajax_handle_assign_category()" /></td>
                    </tr>
                    <tr>
                      <td colspan="2" align="left" valign="top">
					  <?php
					  	$catSET_WIDTH = '220px';
					  	// Get the list of all subdirectories 
						$cats_arr = generate_category_tree(0,0,false,false,true);
						echo generateselectbox('assign_category',$cats_arr,0);
						$catSET_WIDTH = '';
					  ?>		&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMG_GAL_IMG_PRODCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>			  </td>
                    </tr>
                  </table></td>
                </tr>
				<?php
					// Check whether combo is active for current site
					//if(is_module_valid('mod_combo'))
					//{
						/*$comb_arr[0] = '-- Select --';
						// Check whether any combo deals exists in current site
						$sql_combo = "SELECT combo_id,combo_name FROM combo WHERE sites_site_id=$ecom_siteid ORDER BY combo_name";
						$ret_combo = $db->query($sql_combo);
						if ($db->num_rows($ret_combo))
						{
							while ($row_combo = $db->fetch_array($ret_combo))
							{
								$combid = $row_combo['combo_id'];
								$comb_arr[$combid] = stripslashes($row_combo['combo_name']);
							}*/
				?>
							<!--<tr>
							  <td class="imageoptionscolorB"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="imageoptionstable">
								<tr>
								  <td width="88%" align="left" valign="top">Assign Images to Combo</td>
								  <td width="12%" align="left" valign="top"><input name="Submig_comboassign" type="button" class="blue" value="Go" onclick="call_ajax_handle_assign_combo()" /></td>
								</tr>
								<tr>
								  <td colspan="2" align="left" valign="top">
								  <?php
										// Get the list of combo deals
										//echo generateselectbox('assign_combo',$comb_arr,0)
								   ?>
								  </td>
								</tr>
							  </table></td>
							</tr>-->
				<?php
						//}
					//}
					//Check whether gift wrap exists for current site
					if(is_module_valid('mod_giftwrap'))
					{
						$paper_arr[0] = '-- Select --';
						// Check whether any gift wrap paper exists in current site
						$sql_paper = "SELECT paper_id,paper_name FROM giftwrap_paper WHERE sites_site_id=$ecom_siteid AND paper_active=1 ORDER BY paper_order";
						$ret_paper = $db->query($sql_paper);
						if ($db->num_rows($ret_paper))
						{
							while ($row_paper = $db->fetch_array($ret_paper))
							{
								$paperid = $row_paper['paper_id'];
								$paper_arr[$paperid] = stripslashes($row_paper['paper_name']);
							}

				?>
					<tr>
					  <td colspan="2" class="imageoptionscolorB"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="imageoptionstable">
						 <tr>
                     		 <td align="left" valign="top" style="border-top:1px solid #C8C8C8">&nbsp;</td>
                      		<td align="left" valign="top" style="border-top:1px solid #C8C8C8">&nbsp;</td>
                    	</tr>
						<tr>
						  <td width="88%" align="left" valign="top">Assign Images to giftwrap paper</td>
						  <td width="12%" align="left" valign="top"><input name="Submit_giftpaper" type="button" class="blue" value="Go" onclick="call_ajax_handle_assign_paper()" /></td>
						</tr>
						<tr>
						  <td colspan="2" align="left" valign="top">
						  <?php
								// Get the list of giftwrap papers
								echo generateselectbox('assign_paper',$paper_arr,0)
						   ?>
						   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMG_GAL_IMG_GIFTWRAP_PAP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
						</tr>
					  </table></td>
					</tr>
				<?php
					}
					// Check whether any gift wrap card exists in current site
						$sql_card = "SELECT card_id,card_name FROM giftwrap_card WHERE sites_site_id=$ecom_siteid AND card_active=1 ORDER BY card_order";
						$ret_card = $db->query($sql_card);
						if ($db->num_rows($ret_card))
						{
							$card_arr[0] = '-- Select --';
							while ($row_card = $db->fetch_array($ret_card))
							{
								$cardid 			= $row_card['card_id'];
								$card_arr[$cardid] 	= stripslashes($row_card['card_name']);
							}

				?>
					<tr>
					  <td colspan="2" class="imageoptionscolorB"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="imageoptionstable">
						 <tr>
                      		<td align="left" valign="top" style="border-top:1px solid #C8C8C8">&nbsp;</td>
                      		<td align="left" valign="top" style="border-top:1px solid #C8C8C8">&nbsp;</td>
                   		 </tr>
						<tr>
						  <td width="88%" align="left" valign="top">Assign Images to giftwrap card</td>
						  <td width="12%" align="left" valign="top"><input name="Submit_giftcard" type="button" class="blue" value="Go" onclick="call_ajax_handle_assign_card()" /></td>
						</tr>
						<tr>
						  <td colspan="2" align="left" valign="top">
						  <?php
								// Get the list of giftwrap card
								echo generateselectbox('assign_card',$card_arr,0)
						   ?>
						   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMG_GAL_IMG_GIFTWRAP_CARD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
						</tr>
					  </table></td>
					</tr>
				<?php
					}
					// Check whether any gift wrap ribbon exists in current site
						$sql_ribbon = "SELECT ribbon_id,ribbon_name FROM giftwrap_ribbon WHERE sites_site_id=$ecom_siteid AND ribbon_active=1 ORDER BY ribbon_order";
						$ret_ribbon	= $db->query($sql_ribbon);
						if ($db->num_rows($ret_ribbon))
						{
							$ribbon_arr[0] = '-- Select --';
							while ($row_ribbon = $db->fetch_array($ret_ribbon))
							{
								$ribbonid 				= $row_ribbon['ribbon_id'];
								$ribbon_arr[$ribbonid] 	= stripslashes($row_ribbon['ribbon_name']);
							}

				?>
					<tr>
					  <td colspan="2" class="imageoptionscolorB"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="imageoptionstable">
						 <tr>
                      		<td align="left" valign="top" style="border-top:1px solid #C8C8C8">&nbsp;</td>
                      		<td align="left" valign="top" style="border-top:1px solid #C8C8C8">&nbsp;</td>
						</tr>
						<tr>
						  <td width="88%" align="left" valign="top">Assign Images to giftwrap Ribbon</td>
						  <td width="12%" align="left" valign="top"><input name="Submit_giftribbon" type="button" class="blue" value="Go" onclick="call_ajax_handle_assign_ribbon()" /></td>
						</tr>
						<tr>
						  <td colspan="2" align="left" valign="top">
						  <?php
								// Get the list of giftwrap ribbon
								echo generateselectbox('assign_ribbon',$ribbon_arr,0)
						   ?>
						  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMG_GAL_IMG_GIFTWRAP_RIBBON')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
						</tr>
					  </table></td>
					</tr>
				<?php
					}
					// Check whether any gift wrap bow exists in current site
						$sql_bow = "SELECT bow_id,bow_name FROM giftwrap_bows WHERE sites_site_id=$ecom_siteid AND bow_active=1 ORDER BY bow_order";
						$ret_bow	= $db->query($sql_bow);
						if ($db->num_rows($ret_bow))
						{
							$bow_arr[0] = '-- Select --';
							while ($row_bow = $db->fetch_array($ret_bow))
							{
								$bowid 					= $row_bow['bow_id'];
								$bow_arr[$bowid] 		= stripslashes($row_bow['bow_name']);
							}

				?>
					<tr>
					  <td colspan="2" class="imageoptionscolorB"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="imageoptionstable">
						 <tr>
                      		<td align="left" valign="top" style="border-top:1px solid #C8C8C8">&nbsp;</td>
                      		<td align="left" valign="top" style="border-top:1px solid #C8C8C8">&nbsp;</td>
                    	</tr>
						<tr>
						  <td width="88%" align="left" valign="top">Assign Images to giftwrap Bows</td>
						  <td width="12%" align="left" valign="top"><input name="Submit_giftbow" type="button" class="blue" value="Go" onclick="call_ajax_handle_assign_bow()" /></td>
						</tr>
						<tr>
						  <td colspan="2" align="left" valign="top">
						  <?php
								// Get the list of giftwrap bow
								echo generateselectbox('assign_bow',$bow_arr,0)
						   ?>
						   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMG_GAL_IMG_GIFTWRAP_BOWS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
						</tr>
					  </table></td>
					</tr>
				<?php
					}
				}	
				// Check whether any shops exists for current site
					$sql_shop = "SELECT shopbrand_id FROM product_shopbybrand WHERE sites_site_id=$ecom_siteid LIMIT 1";
					$ret_shop = $db->query($sql_shop);
					if ($db->num_rows($ret_shop))
					{
					

				?>
                <tr>
                  <td colspan="2" class="imageoptionscolorB"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="imageoptionstable">
                     <tr>
                      <td align="left" valign="top" style="border-top:1px solid #C8C8C8">&nbsp;</td>
                      <td align="left" valign="top" style="border-top:1px solid #C8C8C8">&nbsp;</td>
                    </tr>
					<tr>
                      <td width="88%" align="left" valign="top">Assign to shopbybrand</td>
                      <td width="12%" align="left" valign="top"><input name="Submit_assigntoshop" type="button" class="blue" value="Go" onclick="call_ajax_handle_assign_shop()" /></td>
                    </tr>
                    <tr>
                      <td colspan="2" align="left" valign="top">
					  <?php
					  	// Get the list of all subdirectories 
					  	$catSET_WIDTH = '220px';
						$shop_arr = generate_shop_tree(0,0,false,false,true);
						echo generateselectbox('assign_shop',$shop_arr,0);
						$catSET_WIDTH = '';
						//$cats_arr = generate_category_tree(0,0,false,false,true);
						//echo generateselectbox('sel_category',$cats_arr,0)
					  ?>
					   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMG_GAL_IMG_SHOPBYBRAND')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                    </tr>
                  </table></td>
                </tr>
				<?php
					}

				// Check whether any categories exists for current site
					$sql_cat = "SELECT category_id FROM product_categories WHERE sites_site_id=$ecom_siteid LIMIT 1";
					$ret_cat = $db->query($sql_cat);
					if ($db->num_rows($ret_cat))
					{
					

				?>
                <tr>
                  <td colspan="2" class="imageoptionscolorB"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="imageoptionstable">
                     <tr>
                      <td align="left" valign="top" style="border-top:1px solid #C8C8C8">&nbsp;</td>
                      <td align="left" valign="top" style="border-top:1px solid #C8C8C8">&nbsp;</td>
                    </tr>
					<tr>
                      <td width="88%" align="left" valign="top">Assign to Products in category</td>
                      <td width="12%" align="left" valign="top"><input name="Submit_assigntoprod" type="button" class="blue" value="Go" onclick="call_ajax_sel_product()" /></td>
                    </tr>
                    <tr>
                      <td colspan="2" align="left" valign="top">
					  <?php
					  	$catSET_WIDTH = '220px';
					  	// Get the list of all subdirectories 
						$cats_arr = generate_category_tree(0,0,false,false,true);
						echo generateselectbox('sel_category',$cats_arr,0);
						$catSET_WIDTH = '';
					  ?>
					   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMG_GAL_IMG_PROD_INCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                    </tr>
					<tr>
                      <td align="left" valign="top" style="border-bottom:1px solid #C8C8C8">&nbsp;</td>
                      <td align="left" valign="top" style="border-bottom:1px solid #C8C8C8">&nbsp;</td>
                    </tr>
                  </table></td>
                </tr>
				<?php
					}
				}
				?>
              </table>
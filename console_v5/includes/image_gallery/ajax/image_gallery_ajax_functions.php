<?php
	/*#################################################################
	# Script Name 	: image_gallery_ajax_functions.php
	# Description 	: Page to hold the functions to be called using ajax
	# Coded by 		: Sny
	# Created on	: 16-Jul-2007
	# Modified by	: Sny
	# Modified On	: 21-Jul-2007
	#################################################################*/
	
	// ###############################################################################################################
	// 				Function which holds the display logic of create new directory section
	// ###############################################################################################################
	function create_subdirectory($alert='')
	{
	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<?php
		if($alert)
		{
		?>
			<tr>
			  <td colspan="2" align="center" valign="middle" class="redtext"><?php echo $alert?></td>
			</tr>
		<?php	
		}
		?>	
		<tr>
		  <td colspan="2" align="left" valign="middle" class="subdirheader">Create Directory</td>
		</tr>
		<tr>
		  <td width="60%" align="left" valign="middle"><input name="txt_dirname" id="txt_dirname" type="text" class="textfeild_small" size="15" /></td>
		  <td width="40%" align="right" valign="middle"><input name="Submit" type="button" class="red" value="Save" onClick="call_ajax_create_dir();" /> &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMG_GAL_MAIN_SAVE_DIR')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>	</td>
		</tr>
		</table>	
	<?php	
	}
	
	// ###############################################################################################################
	// 				Function which holds the display logic of subcategory listings
	// ###############################################################################################################
	function subdirectory_listing($subdir_search='')
	{
		global $db,$ecom_siteid;
		if($subdir_search!='')
		{
			$def_img = 'down_arr.gif';
			$def_style = '';
		}
		else
		{
			$def_img = 'right_arr.gif';
			$def_style = 'none';
		}	
	?>
		<table width="100%" border="0" cellspacing="1" cellpadding="0">
		<tr>
		  <td colspan="2" class="subdirheader"><div class="imgdir_cls_new"><a href="javascript:handle_directory_search()" title="Click for Image Directory Search option">Image Directories <img src="images/<?php echo $def_img?>" id="img_dir_arr" /></a></div></td>
		</tr>
		<tr>
		  <td colspan="2"> 
		  		<table width="100%" border="0" cellspacing="1" cellpadding="0" id="search_subcategory_option" style="display:<?php echo $def_style?>">
				<tr>
				  <td colspan="2" align="right">Search root level image directories</td>
				</tr>
				<tr>
				  <td colspan="2" align="right"><input name="subdir_search" id="subdir_search" type="text" value="<?php echo $subdir_search?>" size="8"  />
				  <input name="subdir_srch_btn" value="Go" type="button" class="red" onclick="search_subdir()" /></td>
				</tr>
				</table>
			</td>
		</tr>		
		<tr>
		  <td colspan="2" align="left" valign="top" class="subdirheader">
<?php
			if($subdir_search)
				$add_condition = " AND directory_name LIKE '%".mysql_escape_string($subdir_search)."%' ";
			else
				$add_condition = '';
			// Get the list of subdirectories in root level
			$sql_dir = "SELECT directory_id,directory_name 
							FROM 
								images_directory 
							WHERE 
								sites_site_id=$ecom_siteid 
								AND parent_id=0 
								$add_condition 
							ORDER BY 
								directory_name";
			$ret_dir = $db->query($sql_dir);
			if ($db->num_rows($ret_dir))
			{
?>
				<div class="exp_coll_div"><a href="javascript:ddtreemenu.flatten('treemenu1', 'expand')" class="treenode_head">Expand All</a> | <a href="javascript:ddtreemenu.flatten('treemenu1', 'contact')" class="treenode_head">Collapse All</a></div>
<?php				
				$mnu_str = '';
				while ($row_dir = $db->fetch_array($ret_dir))
				{
					$mnu_str .= "<li><a href='javascript:call_ajax_handle_subdirclick(\"".$row_dir['directory_id']."\");' class='treenode'>".$row_dir['directory_name']."</a>";
					$mnu_str .= getgallerysubdirectorymenu($row_dir['directory_id']);
				}
?>
			<ul id="treemenu1" class="treeview">
				<li><a href="javascript:call_ajax_handle_subdirclick('0');" class="treenode">Root Directory</a>
				<ul>
					<?php echo $mnu_str;?>	
				</ul>
				</li>
			</ul>	
				
<?php
			}
			else
				echo "<center><br><br><span class='redtext'>-- No Directories Found --</span></center>";
?>		  </td>
		</tr>
</table>
	<?php
	}
	
	// ###############################################################################################################
	// 				Function which holds the display logic of images listing
	// ###############################################################################################################
	function image_listing($dirid,$search_caption='',$search_option,$record_per_page='',$pg=0,$alert='',$mod='',$selprods='',$src_click=0)
	{
		$record_per_page=($record_per_page>0)?intval($record_per_page):10;
		global $db,$ecom_siteid,$ecom_hostname,$assign_deactive;
		
		if($assign_deactive=='')
			$assign_deactive = true;
		if(!$pg)
			$pg = 0;
		if(!$dirid)
			$dirid = 0;	

	?>
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<?php
			if ($alert)
			{
		?>
		<tr>
        	<td align="center" class="errormsg" id="img_listing_err_id"><?php echo $alert?>
			</td>
		</tr>
		<?php
			}
		?>		  
		<tr>
			  <td align="left" class="imagelistnav">
			  <div class="image_innernewdiv">
			<?php 
					if ($dirid)
					{
						// Get the name of current subdirectory
						$sql_dir = "SELECT directory_name FROM images_directory WHERE directory_id=$dirid";
						$ret_dir = $db->query($sql_dir);
						if ($db->num_rows($ret_dir))
						{	
							$row_dir = $db->fetch_array($ret_dir);
			?>
							&nbsp;<strong>Currently in :</strong> <input name="txt_curdirname" id="txt_curdirname" type="text" class="textfeild"  value="<?php echo stripslashes($row_dir['directory_name'])?>"/>
							&nbsp;<input name="Submit" type="button" class="red" value="Update Name" onClick="call_ajax_update_directory()" />
			<?php		
							// Check subdirectories exists for current directory
							$sql_sub = "SELECT directory_id FROM images_directory WHERE parent_id=$dirid LIMIT 1";
							$ret_sub = $db->query($sql_sub);
							if ($db->num_rows($ret_sub)==0)// case no subdirectories
							{
								// Check whether any images exists under current subdirectory
								$sql_img = "SELECT image_id FROM images WHERE images_directory_directory_id=$dirid LIMIT 1";
								$ret_img = $db->query($sql_img);
								if ($db->num_rows($ret_img)==0)// case of no images under current directory
								{
									// Showing the link to delete the sub directory
								?>
								&nbsp;<a href="#" onClick="call_ajax_delete_directory()"><img src="images/del.gif" border="0" title="Delete current directory" align="Delete" /></a>							
								<?php
								}
							}
						}
					}
					else
					{
						echo "&nbsp;<strong>Currently in :</strong> Root Directory.";
					}
			?>
			<div class="upload_delete_class" style="float:right">
			<?php
			if(!$_REQUEST['src_id'])
			{
			?>
				<input name="sub_delete_image" type="button" id="sub_delete_image" value="Delete Images" class="red" onclick="call_ajax_handle_delete_image()" />
				&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMG_GAL_DEL_IMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			<?php
			}
			?>
			&nbsp;&nbsp;
			<input name="upload_up" type="button" class="red" value="Upload Images" onclick="call_ajax_upload_images('normal')" />&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMG_GAL_UPLOAD_IMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>			
			</div>	
			</div>	
		  </td>
		  </tr>		
				<?php
					//#Select condition for getting total count
					if ($search_caption)
						$add_condition = " AND image_title LIKE '%".add_slash($search_caption)."%' ";
						
					if ($src_click==1) // case of coming by clicking the search button
					{
						if($search_option != 'all')
						{
							$add_condition .= " AND images_directory_directory_id=".$dirid;
						}
					}
					else
					{
						if($search_option != 'all')
						{
							$add_condition .= " AND images_directory_directory_id=".$dirid;		
						}	
					}
					$sql_count = "SELECT count(*) as cnt FROM images WHERE sites_site_id = $ecom_siteid $add_condition";
					$res_count = $db->query($sql_count);
					list($numcount) = $db->fetch_array($res_count);//#Getting total count of records
					
					
					/////////////////////////////////For paging///////////////////////////////////////////
					$records_per_page = (is_numeric($record_per_page) and $record_per_page)?$record_per_page:6;#Total records shown in a page

				//	$records_per_page = ($record_per_page)?$record_per_page:6;#Total records shown in a page
					if (!($pg > 0) || $pg == 0) { $pg = 1; }
					
					$pages = ceil($numcount / $records_per_page);//#Getting the total pages

					if($pg > $pages) {
						$pg = $pages;
					}
					$startrec = ($pg - 1) * $records_per_page;//#Starting record.
					$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
					/////////////////////////////////////////////////////////////////////////////////////	
						
				if($numcount>0)
				{			
				?>	
                <tr>
                  <td align="center">
				  <div class="page_div_new">
					  <table width="80%" border="0" cellpadding="0" cellspacing="0" class="listnavtdtable">
						<tr>
						  <td width="47%" align="right"><?php echo $numcount?> Image(s) found - </td>
						  <td width="23%"> Page <?php echo $pg?> of <?php echo $pages?>
						 
						  <?php
						  	for($i=1;$i<=$pages;$i++)
							{
								$option_values[$i] = ($i); 
							}
						  	echo generateselectbox('pgs1',$option_values,$pg,'',$onchange='call_ajax_onchange_page(this.value)')
						  ?>
						  </td>
						</tr>
					  </table>
					  </div>
				  </td>
                </tr>
				<?php
				}
					if($numcount>0)
					{
						// Get the list of images which satisfy the current critera from the images table
						$sql_img = "SELECT image_id,image_title,images_directory_directory_id,image_thumbpath,image_extralargepath,image_gallerythumbpath FROM images WHERE 
									sites_site_id = $ecom_siteid $add_condition ORDER BY image_title LIMIT $startrec, $records_per_page";	
						$ret_img = $db->query($sql_img);
						if($db->num_rows($ret_img))
						{	
				?>
							<tr>
							  <td>
									<table width="100%" border="0" cellpadding="0" cellspacing="8" class="imagelisttable">
									<tr>
<?php
										$max_cols 	= 6;
										$cur_col	= 0;
										$sel_ids	= explode("~",$selprods);
										if (!is_array($sel_ids))
											$sel_ids[0] = 0;
										 if(check_IndividualSslActive())
											{
												$http = 'https://';
											}
											else
											{
												$http = 'http://';
											}	
										while ($row_img = $db->fetch_array($ret_img))
										{
											// Check whether current image is selected or not
											if(in_array($row_img['image_id'],$sel_ids))
												$show_cls = 'imagelisttabletd_sel';
											else
												$show_cls = 'imagelisttabletd';
?>
											  <td align="center" valign="middle" class="<?php echo $show_cls?>" onclick="handle_imagesel(this,'<?php echo $row_img['image_id']?>')" <?php if ($assign_deactive==true) { ?>ondblclick="handle_imageedit('<?php echo $row_img['image_id']?>')" <?php } ?>>
												  <table width="100%" border="0" cellpadding="1" cellspacing="1" class="imagelist_imgtable">
												  <tr>
												  <td align="center" class="imagelist_imgtd">Id : <?php echo $row_img['image_id']?><br />
												  <img src="<?php echo $http.$ecom_hostname."/images/$ecom_hostname/".$row_img['image_gallerythumbpath']?>"/>
												  </td>
												  </tr>
												  <tr>
												  <td align="center" valign="middle">
													<div class="imgtitle_div"><?php echo stripslashes($row_img['image_title']);?></div>
												  </td>
												  </tr>
												  </table>
											  </td>
<?php
											$cur_col++;
											if($cur_col>=$max_cols)
											{
												$cur_col = 0;
												echo "</tr><tr>";
											}
										}
										if ($curcol<$max_cols)
										{
											echo "<td colspan='".($maxcols-$curcol)."'>&nbsp;</td>";
										}
?>		  
									</tr>
								  </table>
							  </td>
							</tr>
<?php
						}
					}
					else
					{
?>
						<tr>
							  <td align="center" class="redtext"> No Images found in current directory
							  </td>
						</tr>	  
<?php	
					}
					if($numcount>0)
					{
?>
						<tr>
						  <td align="center">
							  <table width="80%" border="0" cellpadding="0" cellspacing="0" class="listnavtdtable">
								<tr>
								  <td width="47%" align="right"><strong><?php echo $numcount?> Image(s) found -</strong></td>
								  <td width="23%" align="left"> <strong>Page <?php echo $pg?> of <?php echo $pages?>
								  <?php
									echo generateselectbox('pgs1',$option_values,$pg,'',$onchange='call_ajax_onchange_page(this.value)')
								  ?></strong>
								  </td>
								</tr>
							  </table>
						  </td>
						</tr>
<?php
				}
?>		
</table>
			  <?php
			  	if($mod=='del') // done to make the curid field in image listing section to the id of the parent of current directory.
				{
			  ?>
			  	<input type="hidden" name="retcurid" id="retcurid" value="<?php echo $dirid?>" />
	<?php	
				}
	}
	
	// ###############################################################################################################
	// 				Function which holds the display logic of images edit general tab
	// ###############################################################################################################
	function imageedit_general($edit_id,$alert='')
	{
		global $db,$ecom_siteid,$ecom_hostname;
		$sql_image = "SELECT * FROM images WHERE image_id=".$_REQUEST['edit_id'];
		$ret_image = $db->query($sql_image);
		
		 if(check_IndividualSslActive())
		{
			$http = 'https://';
		}
		else
		{
			$http = 'http://';
		}

		if ($db->num_rows($ret_image))
		{
			$row_image = $db->fetch_array($ret_image);
			if ($row_image['images_directory_directory_id']!=0)
			{
				// Get the directory name for current image
				$sql_dir = "SELECT b.images_directory_directory_id,directory_name FROM images_directory a,images b WHERE b.image_id=$edit_id AND 
							a.directory_id =b.images_directory_directory_id";
				$ret_dir = $db->query($sql_dir);
				if ($db->num_rows($ret_dir))
				{
					$row_dir = $db->fetch_array($ret_dir);
					if ($row_dir['images_directory_directory_id']==0)
						$showdirname = 'Root Directory';
					else
						$showdirname = stripslashes($row_dir['directory_name']);
				}
			}	
			else
			{
				$showdirname = 'Root Directory';
			}
		}
	?>
		 <div class="editarea_div">
		<table width="100%" cellpadding="1" cellspacing="1" border="0" class="maintable">
		<?php
			if($alert)
			{
		?>
				<tr>
				  <td colspan="4" align="center" class="errormsg"><?php echo $alert?></td>
				</tr>
		<?php
			}
		?>
		<tr>
		  <td width="40%" align="left" class="imageedit_normal" colspan="2">Caption: <input type="text" name="txt_caption" id="txt_caption" value="<?php echo stripslashes($row_image['image_title'])?>" /> </td>
		  <td width="60%" align="left" class="imageedit_normal" colspan="2"><strong>Currently In Directory :</strong> <?php echo $showdirname?></td>
		</tr>
		<tr>
		  <td align="center" class="imageedit_normal" colspan="3">&nbsp;</td>
		  <td  align="right" class="imageedit_normal"><input type="button" name="submit_gensave" value="Save" class="red" onclick="call_ajax_save_general()" />
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMG_GAL_IMG_SAVECHANGE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  </tr>
		<tr>
		  <td colspan="4" align="left" class="seperationtd">Use the following section in case if current Images are to be modified </td>
		  </tr>
		
		<tr>
		  <td colspan="4" align="left" class="imageedit_normal"><table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td colspan="3" class="seperationtd"><table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td width="35%">Small Image and its other instances</td>
    <td width="65%" align="right">Change Small Image 
      <input type="file" name="file_thumb" id="file_thumb" />
      <input name="resize_thumb" type="checkbox" value="1" checked="checked" />
Resize to fit </td>
  </tr>
</table></td>
            </tr>
            <tr>
              <td width="88%" colspan="3" align="left" class="">
              <table width="40%" cellpadding="1" cellspacing="1" border="0">
	          <tr>
              <td align="center" valign="bottom" width="50%" class="listingtablestyleB" >
            	<img src="<?php echo $http.$ecom_hostname."/images/$ecom_hostname/".$row_image['image_thumbpath']?>" alt="thumb" />
              </td>
              <td align="center" valign="bottom" width="50%" class="listingtablestyleB" >
              <img src="<?php echo $http.$ecom_hostname."/images/$ecom_hostname/".$row_image['image_gallerythumbpath']?>" alt="Medium" />
              </td>
              </tr>
              <tr>
              <td align="center" class="listingtablestyleB">
              <b>Small</b>
              </td>
              <td align="center" class="listingtablestyleB">
              <b>Gallery Thumb</b>
              </td>
              </tr>
              </table>
              
              </td>
            </tr>
            <tr>
              <td colspan="3" class="seperationtd"><table width="100%" border="0" cellspacing="1" cellpadding="1">
                <tr>
                  <td width="35%">Big Image and its other instances </td>
                  <td width="60%" align="right">Change Big Image
                    <input type="file" name="file_big" id="file_big" />
                    <input name="resize_full" type="checkbox" value="1" checked="checked" />
Resize to fit </td>
                </tr>
              </table></td>
            </tr>
            
            <tr>
              <td colspan="4" align="left">
              <table width="60%" cellpadding="1" cellspacing="1" border="0">
	          <tr>
              <td align="center" valign="bottom" width="25%" class="listingtablestyleB" >
              <img src="<?php echo $http.$ecom_hostname."/images/$ecom_hostname/".$row_image['image_bigpath']?>" alt="big" />
              </td>
              <td align="center" valign="bottom" width="25%" class="listingtablestyleB">
              <img src="<?php echo $http.$ecom_hostname."/images/$ecom_hostname/".$row_image['image_thumbcategorypath']?>" alt="Medium" />
              </td>
               <td align="center" valign="bottom" width="25%" class="listingtablestyleB">
              <img src="<?php echo $http.$ecom_hostname."/images/$ecom_hostname/".$row_image['image_bigcategorypath']?>" alt="Category Main" />
              </td>
              <td align="center" valign="bottom" width="25%" class="listingtablestyleB">
              <img src="<?php echo $http.$ecom_hostname."/images/$ecom_hostname/".$row_image['image_iconpath']?>" alt="Icon" />
              </td>
              </tr>
              <tr>
              <td align="center" class="listingtablestyleB">
              <b>Big</b>
              </td>
              <td align="center" class="listingtablestyleB">
              <b>Medium</b>
              </td>
               <td align="center" class="listingtablestyleB">
              <b>Category Main</b>
              </td>
              <td align="center" class="listingtablestyleB">
            	<b>Icon</b>
              </td>
              </tr>
              </table>
              </td>
            </tr>
          </table></td>
		  </tr>
		   <tr>
              <td colspan="4" class="seperationtd"><table width="100%" border="0" cellspacing="1" cellpadding="1">
                <tr>
                  <td width="40%">Full Size Image </td>
                  <td width="60%" align="right">Change Full Size Image
                    <input type="file" name="file_extra" id="file_extra" />
                  </td>
                </tr>
              </table></td>
          </tr>
			 <tr>
              <td colspan="4" align="left" class="listingtablestyleB">
			  <a href="<?php echo $http.$ecom_hostname."/images/$ecom_hostname/".$row_image['image_extralargepath']?>" title="Click here" class="edittextlink" target="_blank">Click here</a> to view the full size image
			  </td>
		  </tr>
		<tr>
		  <td colspan="4" align="left" class="imageedit_normal">&nbsp;</td>
		  </tr>
		 </table>
		 </div>
		  <div class="editarea_div">
		 <table width="100%" cellpadding="0" cellspacing="0" border="0"> 
		  <tr>
		  <td colspan="4" align="right" class="imageedit_normal"><input type="button" name="submit_gensave" value="Save" class="red" onclick="call_ajax_save_general()" />
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMG_GAL_IMG_SAVECHANGE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  </tr>
		  <tr>
		  <td colspan="4" align="left" class="imageedit_normal">&nbsp;</td>
		  </tr>
		</table>
		</div>
			
	<?php	
	}
	
	// ###############################################################################################################
	// 				Function which holds the display logic of images edit operation tab
	// ###############################################################################################################
	function imageedit_operation($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
		$sql_image = "SELECT * FROM images WHERE image_id=".$edit_id;
		$ret_image = $db->query($sql_image);
		if ($db->num_rows($ret_image))
		{
			$row_image = $db->fetch_array($ret_image);
			if ($row_image['images_directory_directory_id']!=0)
			{
				// Get the directory name for current image
				$sql_dir = "SELECT b.images_directory_directory_id,directory_name FROM images_directory a,images b WHERE b.image_id=$edit_id AND 
							a.directory_id =b.images_directory_directory_id";
				$ret_dir = $db->query($sql_dir);
				if ($db->num_rows($ret_dir))
				{
					$row_dir = $db->fetch_array($ret_dir);
					if ($row_dir['images_directory_directory_id']==0)
						$showdirname = 'Root Directory';
					else
						$showdirname = stripslashes($row_dir['directory_name']);
				}
			}	
			else
			{
				$showdirname = 'Root Directory';
			}
		}
	?>
	<div class="editarea_div">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<?php 
		if($alert)
		{
	?>
			<tr>
				<td class="errormsg" align="center"><?php echo $alert?>
				</td>
			</tr>
	<?php
		}
	?>	
      <tr>
        <td class="imageoptionscolorA">
		 
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="imageoptionstable">
            <tr>
              <td width="29%" align="left" valign="top">Move Current Image to directory</td>
              <td width="28%" align="left" valign="top"><?php
					  	// Get the list of all subdirectories 
						$dir_arr = generate_directory_tree(0,0,false);
						echo generateselectbox('change_subdir',$dir_arr,0)
					  ?></td>
              <td width="43%" align="left" valign="top"><input name="submit_changedir" type="button" class="blue" value="Go" onclick="call_ajax_handle_change_directory()" />
			  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMG_GAL_IMG_DIR')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
            
        </table></td>
      </tr>
      <tr>
        <td class="imageoptionscolorA"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="imageoptionstable">
            <tr>
              <td width="29%" align="left" valign="top">Assign Current Image to Category</td>
              <td width="28%" align="left" valign="top"><?php
					  	// Get the list of all subdirectories 
						$cats_arr = generate_category_tree(0,0,false,false,true);
						echo generateselectbox('assign_category',$cats_arr,0)
					  ?></td>
              <td width="43%" align="left" valign="top"><input name="submit_assigncat" type="button" class="blue" value="Go" onclick="call_ajax_handle_assign_category()" />
			   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMG_GAL_IMG_PRODCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
            
        </table></td>
      </tr>
      <?php
					// Check whether any combo deals exists in current site
					/*$sql_combo = "SELECT combo_id,combo_name FROM combo WHERE sites_site_id=$ecom_siteid ORDER BY combo_name";
					$ret_combo = $db->query($sql_combo);
					if ($db->num_rows($ret_combo))
					{
						$comb_arr[0] = '-- Select --';
						while ($row_combo = $db->fetch_array($ret_combo))
						{
							$combid = $row_combo['combo_id'];
							$comb_arr[$combid] = stripslashes($row_combo['combo_name']);
						}*/
				?>
     <!-- <tr>
        <td class="imageoptionscolorB"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="imageoptionstable">
            <tr>
              <td width="29%" align="left" valign="top">Assign Current Image to Combo</td>
              <td width="28%" align="left" valign="top"><?php
									// Get the list of combo deals
									//echo generateselectbox('assign_combo',$comb_arr,0)
							   ?></td>
              <td width="43%" align="left" valign="top"><input name="Submig_comboassign" type="button" class="blue" value="Go" onclick="call_ajax_handle_assign_combo()" /></td>
            </tr>
            
        </table></td>
      </tr>-->
      <?php
					//}
					// Check whether any gift wrap paper exists in current site
					$sql_paper = "SELECT paper_id,paper_name FROM giftwrap_paper WHERE sites_site_id=$ecom_siteid ORDER BY paper_order";
					$ret_paper = $db->query($sql_paper);
					if ($db->num_rows($ret_paper))
					{
						$paper_arr[0] = '-- Select --';
						while ($row_paper = $db->fetch_array($ret_paper))
						{
							$paperid = $row_paper['paper_id'];
							$paper_arr[$paperid] = stripslashes($row_paper['paper_name']);
						}

				?>
      <tr>
        <td class="imageoptionscolorA"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="imageoptionstable">
            <tr>
              <td width="29%" align="left" valign="top">Assign Current Image to giftwrap paper</td>
              <td width="28%" align="left" valign="top"><?php
							// Get the list of giftwrap papers
							echo generateselectbox('assign_paper',$paper_arr,0)
					   ?></td>
              <td width="43%" align="left" valign="top"><input name="Submit_giftpaper" type="button" class="blue" value="Go" onclick="call_ajax_handle_assign_paper()" />
			  
			   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMG_GAL_IMG_GIFTWRAP_PAP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
            
        </table></td>
      </tr>
      <?php
				}
				// Check whether any gift wrap card exists in current site
					$sql_card = "SELECT card_id,card_name FROM giftwrap_card WHERE sites_site_id=$ecom_siteid ORDER BY card_order";
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
        <td class="imageoptionscolorA"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="imageoptionstable">
            <tr>
              <td width="29%" align="left" valign="top">Assign Current Image to giftwrap card</td>
              <td width="28%" align="left" valign="top"><?php
							// Get the list of giftwrap card
							echo generateselectbox('assign_card',$card_arr,0)
					   ?></td>
              <td width="43%" align="left" valign="top"><input name="Submit_giftcard" type="button" class="blue" value="Go" onclick="call_ajax_handle_assign_card()" />
			  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMG_GAL_IMG_GIFTWRAP_CARD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
            
        </table></td>
      </tr>
      <?php
				}
				// Check whether any gift wrap ribbon exists in current site
					$sql_ribbon = "SELECT ribbon_id,ribbon_name FROM giftwrap_ribbon WHERE sites_site_id=$ecom_siteid ORDER BY ribbon_order";
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
        <td class="imageoptionscolorA"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="imageoptionstable">
            <tr>
              <td width="29%" align="left" valign="top">Assign Current Image to giftwrap Ribbon</td>
              <td width="28%" align="left" valign="top"><?php
							// Get the list of giftwrap ribbon
							echo generateselectbox('assign_ribbon',$ribbon_arr,0)
					   ?></td>
              <td width="43%" align="left" valign="top"><input name="Submit_giftribbon" type="button" class="blue" value="Go" onclick="call_ajax_handle_assign_ribbon()" />
			   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMG_GAL_IMG_GIFTWRAP_RIBBON')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
            
        </table></td>
      </tr>
	  <?php
	  			}
	  // Check whether any gift wrap bows exists in current site
					$sql_bow = "SELECT bow_id,bow_name FROM giftwrap_bows WHERE sites_site_id=$ecom_siteid ORDER BY bow_order";
					$ret_bow	= $db->query($sql_bow);
					if ($db->num_rows($ret_bow))
					{
						$bow_arr[0] = '-- Select --';
						while ($row_bow = $db->fetch_array($ret_bow))
						{
							$bowid 				= $row_bow['bow_id'];
							$bow_arr[$bowid] 	= stripslashes($row_bow['bow_name']);
						}

				?>
      <tr>
        <td class="imageoptionscolorA"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="imageoptionstable">
            <tr>
              <td width="29%" align="left" valign="top">Assign Current Image to giftwrap Bow</td>
              <td width="28%" align="left" valign="top"><?php
							// Get the list of giftwrap ribbon
							echo generateselectbox('assign_bow',$bow_arr,0)
					   ?></td>
              <td width="43%" align="left" valign="top"><input name="Submit_giftbow" type="button" class="blue" value="Go" onclick="call_ajax_handle_assign_bow()" />
			  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMG_GAL_IMG_GIFTWRAP_BOWS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
            
        </table></td>
      </tr>
      <?php
				}

				// Check whether any categories exists for current site
					$sql_cat = "SELECT category_id FROM product_categories WHERE sites_site_id=$ecom_siteid LIMIT 1";
					$ret_cat	= $db->query($sql_cat);
					if ($db->num_rows($ret_cat))
					{
				?>
      <tr>
        <td class="imageoptionscolorA"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="imageoptionstable">
            <tr>
              <td width="29%" align="left" valign="top">Assign Current Image to Products in category</td>
              <td width="28%" align="left" valign="top"><?php
					  	// Get the list of all subdirectories 
						$cats_arr = generate_category_tree(0,0,false,false,true);
						echo generateselectbox('sel_category',$cats_arr,0)
					  ?></td>
              <td width="43%" align="left" valign="top"><input name="Submit_assigntoprod" type="button" class="blue" value="Go" onclick="call_ajax_sel_product()" />
			  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMG_GAL_IMG_PROD_INCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
        </table></td>
      </tr>
      <?php
				}
				
				// Check whether any Shops exists for current site
					$sql_shop = "SELECT shopbrand_id FROM product_shopbybrand WHERE sites_site_id=$ecom_siteid LIMIT 1";
					$ret_shop = $db->query($sql_shop);
					if ($db->num_rows($ret_shop))
					{
				?>
      <tr>
        <td class="imageoptionscolorA"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="imageoptionstable">
            <tr>
              <td width="29%" align="left" valign="top">Assign Current Image to Shop</td>
              <td width="28%" align="left" valign="top"><?php
					  	// Get the list of all subdirectories 
						$shop_arr = generate_shop_tree(0,0,false,false,true);
						echo generateselectbox('assign_shop',$shop_arr,0);
					  ?></td>
              <td width="43%" align="left" valign="top"><input name="Submit_assigntoshop" type="button" class="blue" value="Go" onclick="call_ajax_handle_assign_shop()" />
			  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMG_GAL_IMG_SHOPBYBRAND')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
            <tr>
              <td colspan="3" align="left" valign="top">&nbsp;</td>
            </tr>
        </table></td>
      </tr>
      <?php
				}
				?>
    </table>
	</div>
	<?php	
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of images edit "assigned to" tab
	// ###############################################################################################################
	function imageedit_assigned($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
		$sql_image = "SELECT * FROM images WHERE image_id=".$_REQUEST['edit_id'];
		$ret_image = $db->query($sql_image);
		if ($db->num_rows($ret_image))
		{
			$row_image = $db->fetch_array($ret_image);
			if ($row_image['images_directory_directory_id']!=0)
			{
				// Get the directory name for current image
				$sql_dir = "SELECT b.images_directory_directory_id,directory_name FROM images_directory a,images b WHERE b.image_id=$edit_id AND 
							a.directory_id =b.images_directory_directory_id";
				$ret_dir = $db->query($sql_dir);
				if ($db->num_rows($ret_dir))
				{
					$row_dir = $db->fetch_array($ret_dir);
					if ($row_dir['images_directory_directory_id']==0)
						$showdirname = 'Root Directory';
					else
						$showdirname = stripslashes($row_dir['directory_name']);
				}
			}	
			else
			{
				$showdirname = 'Root Directory';
			}
		}
	?>
	<div class="editarea_div">
		<table width="100%" cellpadding="1" cellspacing="1" border="0" class="maintable">
		<?php
			if($alert)
			{
		?>
				<tr>
				  <td colspan="4" align="center" class="errormsg"><?php echo $alert?></td>
				</tr>
		<?php
			}
		?>
			<tr>
		  		<td width="15%" align="left" class="imageedit_normal">
				<table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="3%" class="seperationtd"><img id="cat_imgtag" src="images/plus.gif" border="0" onclick="handle_expansionall(this,'prodcat')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd">Product Categories</td>
            </tr>
			   <?php
		 // Check whether this image is assigned to any of the product categories
			$sql_cat = "SELECT id FROM images_product_category 
						 WHERE images_image_id=$edit_id";
			$ret_cat = $db->query($sql_cat);
		 ?>
		 <tr>
		  <td align="right" colspan="2" class="tdcolorgray_buttons" id="prodcatunassign_div" style="display:none">
				<?php
				if ($db->num_rows($ret_cat))
				{
				?>
					<div class="unassign_div">
					<input name="cat_unassign" type="button" class="red" id="cat_unassign" value="Unassign" onclick="call_ajax_unassignall('prodcat','checkboxprodcat[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_IMG_GAL_UNASS_CATIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
				<?php
				}				
				?>		  </td>
        </tr>
		<tr id="prodcat_tr" style="display:none">
			<td align="right" colspan="2" class="tdcolorgray_buttons">
			<div id="prodcatassign_div" style="text-align:center"></div>			</td>
		</tr>	
          </table>
				</td>
		  	</tr>
			<tr>
		  		<td width="15%" align="left" class="imageedit_normal">
				<table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="3%" class="seperationtd"><img id="cat_imgtag" src="images/plus.gif" border="0" onclick="handle_expansionall(this,'prodshop')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd">Product Shopbybrand</td>
            </tr>
			   <?php
		 // Check whether this image is assigned to any of the product categories
			$sql_shop = "SELECT id FROM images_shopbybrand 
						 WHERE images_image_id=$edit_id";
			$ret_shop = $db->query($sql_shop);
		 ?>
		 <tr>
		  <td align="right" colspan="2" class="tdcolorgray_buttons" id="prodshopunassign_div" style="display:none">
				<?php
				if ($db->num_rows($ret_shop))
				{
				?>
					<div class="unassign_div">
					<input name="shop_unassign" type="button" class="red" id="shop_unassign" value="Unassign" onclick="call_ajax_unassignall('prodshop','checkboxprodshop[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_IMG_GAL_UNASS_SHOPIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
				<?php
				}				
				?>		  </td>
        </tr>
		<tr id="prodshop_tr" style="display:none">
			<td align="right" colspan="2" class="tdcolorgray_buttons">
			<div id="prodshopassign_div" style="text-align:center"></div>			</td>
		</tr>	
          </table>
				</td>
		  	</tr>
			<tr>
		  		<td width="15%" align="left" class="imageedit_normal">
				<table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="3%" class="seperationtd"><img id="cat_imgtag" src="images/plus.gif" border="0" onclick="handle_expansionall(this,'prods')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd">Products</td>
            </tr>
		<?php
			 // Check whether this image is assigned to any of the products
				$sql_prod = "SELECT id FROM images_product 
						 WHERE images_image_id=$edit_id";
				$ret_prod = $db->query($sql_prod);
		 ?>
		 <tr>
		  <td align="right" colspan="2" class="tdcolorgray_buttons" id="prodsunassign_div" style="display:none">
				<?php
				if ($db->num_rows($ret_prod))
				{
				?>
					<div class="unassign_div">
					<input name="prods_unassign" type="button" class="red" id="prods_unassign" value="Unassign" onclick="call_ajax_unassignall('prods','checkboxprods[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_IMG_GAL_UNASS_PRODIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
				<?php
				}				
				?>		  </td>
        </tr>
		<tr id="prods_tr" style="display:none">
			<td align="right" colspan="2" class="tdcolorgray_buttons">
			<div id="prodsassign_div" style="text-align:center"></div>			</td>
		</tr>	
          </table>
				</td>
		  	</tr>
		<?php
			// Check whether giftwrap is active in current site
			if(is_module_valid('mod_giftwrap'))
			{
		?>	
				<tr>
					<td width="15%" align="left" class="imageedit_normal">
					<table width="100%" border="0" cellspacing="1" cellpadding="1">
				<tr>
				  <td width="3%" class="seperationtd"><img id="cat_imgtag" src="images/plus.gif" border="0" onclick="handle_expansionall(this,'paper')" title="Click"/></td>
				  <td width="97%" align="left" class="seperationtd">Giftwrap Paper</td>
				</tr>
			<?php
				 // Check whether this image is assigned to any of the combo deals
					$sql_combo = "SELECT id FROM images_giftwrap_paper
							 WHERE images_image_id=$edit_id";
					$ret_combo = $db->query($sql_combo);
			 ?>
			 <tr>
			  <td align="right" colspan="2" class="tdcolorgray_buttons" id="paperunassign_div" style="display:none">
					<?php
					if ($db->num_rows($ret_combo))
					{
					?>
						<div class="unassign_div" >
						<input name="paper_unassign" type="button" class="red" id="paper_unassign" value="Unassign" onclick="call_ajax_unassignall('paper','checkboxpaper[]')" />
						<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_IMG_GAL_UNASS_WRAPPAPERIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
					<?php
					}				
					?>		  </td>
			</tr>
			<tr id="paper_tr" style="display:none">
				<td align="right" colspan="2" class="tdcolorgray_buttons">
					<div id="paperassign_div" style="text-align:center"></div>
				</td>
			</tr>	
			  </table>
					</td>
				</tr>
				<tr>
					<td width="15%" align="left" class="imageedit_normal">
					<table width="100%" border="0" cellspacing="1" cellpadding="1">
				<tr>
				  <td width="3%" class="seperationtd"><img id="cat_imgtag" src="images/plus.gif" border="0" onclick="handle_expansionall(this,'card')" title="Click"/></td>
				  <td width="97%" align="left" class="seperationtd">Giftwrap Card</td>
				</tr>
			<?php
				 // Check whether this image is assigned to any of the combo deals
					$sql_card = "SELECT id FROM images_giftwrap_card
							 WHERE images_image_id=$edit_id";
					$ret_card = $db->query($sql_card);
			 ?>
			 <tr>
			  <td align="right" colspan="2" class="tdcolorgray_buttons" id="cardunassign_div" style="display:none">
					<?php
					if ($db->num_rows($ret_card))
					{
					?>
						<div  class="unassign_div">
						<input name="card_unassign" type="button" class="red" id="card_unassign" value="Unassign" onclick="call_ajax_unassignall('card','checkboxcard[]')" />
						<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_IMG_GAL_UNASS_WRAPCARDIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
					<?php
					}				
					?>		  </td>
			</tr>
			<tr id="card_tr" style="display:none">
				<td align="right" colspan="2" class="tdcolorgray_buttons">
					<div id="cardassign_div" style="text-align:center"></div>
				</td>
			</tr>	
			</table>	
				</td>
				</tr>
				<tr>
					<td width="15%" align="left" class="imageedit_normal">
					<table width="100%" border="0" cellspacing="1" cellpadding="1">
				<tr>
				  <td width="3%" class="seperationtd"><img id="cat_imgtag" src="images/plus.gif" border="0" onclick="handle_expansionall(this,'ribbon')" title="Click"/></td>
				  <td width="97%" align="left" class="seperationtd">Giftwrap Ribbons</td>
				</tr>
			<?php
				 // Check whether this image is assigned to any of the giftwrap ribbon
					$sql_ribbon = "SELECT id FROM images_giftwrap_ribbon 
							 WHERE images_image_id=$edit_id";
					$ret_ribbon = $db->query($sql_ribbon);
			 ?>
			 <tr>
			  <td align="right" colspan="2" class="tdcolorgray_buttons" id="ribbonunassign_div" style="display:none">
					<?php
					if ($db->num_rows($ret_ribbon))
					{
					?>
						<div  class="unassign_div">
						<input name="ribbon_unassign" type="button" class="red" id="card_unassign" value="Unassign" onclick="call_ajax_unassignall('ribbon','checkboxribbon[]')" />
						<a href="#" onmouseover ="ddrivetip(<?=get_help_messages('EDIT_IMG_GAL_UNASS_WRAPRIBBIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
					<?php
					}				
					?>		  </td>
			</tr>
			<tr id="ribbon_tr" style="display:none">
				<td align="right" colspan="2" class="tdcolorgray_buttons">
					<div id="ribbonassign_div" style="text-align:center"></div>
				</td>
			</tr>	
			</table>	
				</td>
				</tr>
				<tr>
					<td width="15%" align="left" class="imageedit_normal">
					<table width="100%" border="0" cellspacing="1" cellpadding="1">
				<tr>
				  <td width="3%" class="seperationtd"><img id="cat_imgtag" src="images/plus.gif" border="0" onclick="handle_expansionall(this,'bow')" title="Click"/></td>
				  <td width="97%" align="left" class="seperationtd">Giftwrap Bows</td>
				</tr>
			<?php
				 // Check whether this image is assigned to any of the giftwrap bow
					$sql_bow = "SELECT id FROM images_giftwrap_bow
							 WHERE images_image_id=$edit_id";
					$ret_bow = $db->query($sql_bow);
			 ?>
			 <tr>
			  <td align="right" colspan="2" class="tdcolorgray_buttons" id="bowunassign_div" style="display:none">
					<?php
					if ($db->num_rows($ret_bow))
					{
					?>
						<div  class="unassign_div" >
						<input name="bow_unassign" type="button" class="red" id="bow_unassign" value="Unassign" onclick="call_ajax_unassignall('bow','checkboxbow[]')" />
						<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_IMG_GAL_UNASS_GIFTWRAPIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
					<?php
					}				
					?>		  </td>
			</tr>
			<tr id="bow_tr" style="display:none">
				<td align="right" colspan="2" class="tdcolorgray_buttons">
					<div id="bowassign_div" style="text-align:center"></div>
				</td>
			</tr>	
			</table>	
				</td>
				</tr>
		<?php
			}
		?>	
</table>
		</div>
	<?php	
	}
	// ###############################################################################################################
	// 	Function which holds the display logic of product categories to which the current image is assigned to
	// ###############################################################################################################
	function show_imageassign_to_productcategory_list($image_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of variables added for this product
				$sql_prop = "SELECT b.id,a.category_name,b.product_categories_category_id,a.category_id FROM 
							 product_categories a,images_product_category b   
							 WHERE b.images_image_id=$image_id AND a.category_id=b.product_categories_category_id
							  ORDER BY b.image_order";
				$ret_prop = $db->query($sql_prop);
	?>
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="5" align="center" class="errormsg" id='alert_prodcat'><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_prop))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditImage,\'checkboxprodcat[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditImage,\'checkboxprodcat[]\')"/>','Slno.','Product Category');
							$header_positions=array('center','center','left');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_prop = $db->fetch_array($ret_prop))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxprodcat[]" value="<?php echo $row_prop['id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<? echo $row_prop['category_id']?>" class="edittextlink"><?php echo stripslashes($row_prop['category_name']);?></a></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="prodcat_norec" id="prodcat_norec" value="1" />
								  Not assigned to any product categories.</td>
								</tr>
						<?php
						}
						?>	
				</table>	
	<?php	
	}
	// ###############################################################################################################
	// 	Function which holds the display logic of shops to which the current image is assigned to
	// ###############################################################################################################
	function show_imageassign_to_shop_list($image_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of variables added for this product
				$sql_prop = "SELECT b.id,a.shopbrand_name,b.product_shopbybrand_shopbrand_id,a.shopbrand_id FROM 
							 product_shopbybrand a,images_shopbybrand b   
							 WHERE b.images_image_id=$image_id AND a.shopbrand_id=b.product_shopbybrand_shopbrand_id
							  ORDER BY b.image_order";
				$ret_prop = $db->query($sql_prop);
	?>
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="5" align="center" class="errormsg" id='alert_prodcat'><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_prop))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditImage,\'checkboxprodshop[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditImage,\'checkboxprodshop[]\')"/>','Slno.','Shop Name');
							$header_positions=array('center','center','left');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_prop = $db->fetch_array($ret_prop))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxprodshop[]" value="<?php echo $row_prop['id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=shopbybrand&fpurpose=edit&checkbox[0]=<? echo $row_prop['shopbrand_id']?>" class="edittextlink"><?php echo stripslashes($row_prop['shopbrand_name']);?></a></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="prodshop_norec" id="prodshop_norec" value="1" />
								  Not assigned to any product Shops.</td>
								</tr>
						<?php
						}
						?>	
				</table>	
	<?php	
	}
	
	// ###############################################################################################################
	// 	Function which holds the display logic of products to which the current image is assigned to
	// ###############################################################################################################
	function show_imageassign_to_products_list($image_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of variables added for this product
				$sql_prop = "SELECT b.id,a.product_name,b.products_product_id,a.product_id FROM 
							 products a,images_product b   
							 WHERE b.images_image_id=$image_id AND a.product_id=b.products_product_id
							  ORDER BY b.image_order";
				$ret_prop = $db->query($sql_prop);
	?>
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="5" align="center" class="errormsg" id='alert_prods'><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_prop))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditImage,\'checkboxprods[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditImage,\'checkboxprods[]\')"/>','Slno.','Products');
							$header_positions=array('center','center','left');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_prop = $db->fetch_array($ret_prop))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxprods[]" value="<?php echo $row_prop['id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<? echo $row_prop['product_id']?>" class="edittextlink"><?php echo stripslashes($row_prop['product_name']);?></a></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="prods_norec" id="prods_norec" value="1" />
								  Not assigned to any products.</td>
								</tr>
						<?php
						}
						?>	
				</table>	
	<?php	
	}
	// ###############################################################################################################
	// 	Function which holds the display logic of combo deals to which the current image is assigned to
	// ###############################################################################################################
	function show_imageassign_to_combo_list($image_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of variables added for this product
				$sql_prop = "SELECT b.id,a.combo_name,b.combo_combo_id,a.combo_id FROM 
							 combo a,images_combo b   
							 WHERE b.images_image_id=$image_id AND a.combo_id=b.combo_combo_id
							  ORDER BY b.image_order";
				$ret_prop = $db->query($sql_prop);
	?>
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="5" align="center" class="errormsg" id='alert_combo'><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_prop))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditImage,\'checkboxcombo[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditImage,\'checkboxcombo[]\')"/>','Slno.','Combo Deal');
							$header_positions=array('center','center','left');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_prop = $db->fetch_array($ret_prop))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxcombo[]" value="<?php echo $row_prop['id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=combo&fpurpose=edit&checkbox[0]=<? echo $row_prop['combo_id']?>" class="edittextlink"><?php echo stripslashes($row_prop['combo_name']);?></a></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="combo_norec" id="combo_norec" value="1" />
								  Not assigned to any Combo deals.</td>
								</tr>
						<?php
						}
						?>	
				</table>	
	<?php	
	}
	// ###############################################################################################################
	// 	Function which holds the display logic of giftwrap paper to which the current image is assigned to
	// ###############################################################################################################
	function show_imageassign_to_paper_list($image_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of variables added for this product
				$sql_prop = "SELECT b.id,a.paper_name,b.giftwrap_paper_paper_id,a.paper_id FROM 
							 giftwrap_paper a,images_giftwrap_paper b   
							 WHERE b.images_image_id=$image_id AND a.paper_id=b.giftwrap_paper_paper_id
							  ORDER BY b.image_order";
				$ret_prop = $db->query($sql_prop);
	?>
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="5" align="center" class="errormsg" id='alert_paper'><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_prop))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditImage,\'checkboxpaper[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditImage,\'checkboxpaper[]\')"/>','Slno.','Paper Name');
							$header_positions=array('center','center','left');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_prop = $db->fetch_array($ret_prop))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxpaper[]" value="<?php echo $row_prop['id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=giftwrap_papers&fpurpose=edit&checkbox[0]=<? echo $row_prop['paper_id'] ?>" class="edittextlink"><?php echo stripslashes($row_prop['paper_name']);?></a></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="paper_norec" id="paper_norec" value="1" />
								  Not assigned to any Giftwrap Papers.</td>
								</tr>
						<?php
						}
						?>	
				</table>	
	<?php	
	}
	// ###############################################################################################################
	// 	Function which holds the display logic of giftwrap card to which the current image is assigned to
	// ###############################################################################################################
	function show_imageassign_to_card_list($image_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of variables added for this product
				$sql_prop = "SELECT b.id,a.card_name,b.giftwrap_card_card_id,a.card_id FROM 
							 giftwrap_card a,images_giftwrap_card b   
							 WHERE b.images_image_id=$image_id AND a.card_id=b.giftwrap_card_card_id
							  ORDER BY b.image_order";
				$ret_prop = $db->query($sql_prop);
	?>
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="5" align="center" class="errormsg" id='alert_card'><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_prop))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditImage,\'checkboxcard[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditImage,\'checkboxcard[]\')"/>','Slno.','Paper Name');
							$header_positions=array('center','center','left');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_prop = $db->fetch_array($ret_prop))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxcard[]" value="<?php echo $row_prop['id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=giftwrap_cards&fpurpose=edit&checkbox[0]=<? echo $row_prop['card_id']?>" class="edittextlink"><?php echo stripslashes($row_prop['card_name']);?></a></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="card_norec" id="card_norec" value="1" />
								  Not assigned to any Giftwrap Cards.</td>
								</tr>
						<?php
						}
						?>	
				</table>	
	<?php	
	}
	// ###############################################################################################################
	// 	Function which holds the display logic of giftwrap ribbon to which the current image is assigned to
	// ###############################################################################################################
	function show_imageassign_to_ribbon_list($image_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of variables added for this product
				$sql_prop = "SELECT b.id,a.ribbon_name,b.giftwrap_ribbon_ribbon_id,a.ribbon_id FROM 
							 giftwrap_ribbon a,images_giftwrap_ribbon b   
							 WHERE b.images_image_id=$image_id AND a.ribbon_id=b.giftwrap_ribbon_ribbon_id
							  ORDER BY b.image_order";
				$ret_prop = $db->query($sql_prop);
	?>
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="5" align="center" class="errormsg" id='alert_ribbon'><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_prop))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditImage,\'checkboxribbon[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditImage,\'checkboxribbon[]\')"/>','Slno.','Ribbon Name');
							$header_positions=array('center','center','left');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_prop = $db->fetch_array($ret_prop))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxribbon[]" value="<?php echo $row_prop['id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=giftwrap_ribbons&fpurpose=edit&checkbox[0]=<? echo $row_prop['ribbon_id']?>" class="edittextlink"><?php echo stripslashes($row_prop['ribbon_name']);?></a></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="3" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="ribbon_norec" id="ribbon_norec" value="1" />
								  Not assigned to any Giftwrap Ribbons.</td>
								</tr>
						<?php
						}
						?>	
				</table>	
	<?php	
	}
	// ###############################################################################################################
	// 	Function which holds the display logic of giftwrap bows to which the current image is assigned to
	// ###############################################################################################################
	function show_imageassign_to_bow_list($image_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of variables added for this product
				$sql_prop = "SELECT b.id,a.bow_name,b.giftwrap_bows_bow_id,a.bow_id FROM 
							 giftwrap_bows a,images_giftwrap_bow b   
							 WHERE b.images_image_id=$image_id AND a.bow_id=b.giftwrap_bows_bow_id
							  ORDER BY b.image_order";
				$ret_prop = $db->query($sql_prop);
	?>
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="5" align="center" class="errormsg" id='alert_bow'><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_prop))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditImage,\'checkboxbow[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditImage,\'checkboxbow[]\')"/>','Slno.','Bow Name');
							$header_positions=array('center','center','left');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_prop = $db->fetch_array($ret_prop))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxbow[]" value="<?php echo $row_prop['id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=giftwrap_bows&fpurpose=edit&checkbox[0]=<? echo $row_prop['bow_id'] ?>" class="edittextlink"><?php echo stripslashes($row_prop['bow_name']);?></a></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="bow_norec" id="bow_norec" value="1" />
								  Not assigned to any Giftwrap Bows.</td>
								</tr>
						<?php
						}
						?>	
				</table>	
	<?php	
	}
	function show_move_directory()
	{
		global $db;
	?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="imageoptionstable">
		<tr>
		<td width="25%" align="left" valign="top">Move  Images to directory</td>
		<td align="left" valign="top">
		<?php
		// Get the list of all subdirectories 
		$dir_arr = generate_directory_tree(0,0,false);
		$catSET_WIDTH = '220px';
		echo generateselectbox('change_subdir',$dir_arr,0);
		$catSET_WIDTH = '';
		?>
		&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMG_GAL_IMG_DIR')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		<td width="12%" align="left" valign="top">
<input name="submit_changedir" type="button" class="blue" value="Go" onclick="call_ajax_handle_change_directory()" />
		</td>
		
		</tr>
		</table>
	<?php	
	}
?>

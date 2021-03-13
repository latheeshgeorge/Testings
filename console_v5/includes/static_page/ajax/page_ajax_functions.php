<?php
	function show_page_maininfo($page_id,$alert='')
	{
		global $db,$ecom_siteid,$ecom_themeid,$ecom_hostname;
		$sql	=	"SELECT
									page_id,title,content,hide,pname,page_type,page_link,
									page_link_newwindow,allow_auto_linker,in_mobile_api_sites
							FROM
									static_pages
							WHERE
									sites_site_id=$ecom_siteid
							AND
									page_id=".$page_id;
		$res=$db->query($sql);
		if($db->num_rows($res)==0)  { echo " <font color='red'> You Are Not Authorised  </a>"; exit; }
		$row=$db->fetch_array($res);
?>	
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
	<td>
	<div class="editarea_div">
	<div class="editarea_url">
		<table width="100%" cellpadding="1" cellspacing="0">			
          <tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray_url_left" >Website URL</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray_url" >: <?php url_static_page($row['page_id'],$row['title']);?></td>
          </tr>
		</table>
		</div>
	<table width="100%" cellpadding="0" cellspacing="0">
		<tr><td>
		
		<table cellpadding="0" cellspacing="0" width="100%" border="0">
        <tr>
          <td width="21%" align="left" valign="middle" class="tdcolorgray" >Page Title <span class="redtext">*</span> </td>
          <td width="79%" align="left" valign="middle" class="tdcolorgray">
		  <?
		  if($row['title']=='Home')
		  {
		  echo $row['title'];
		  ?>
		  <input type="hidden" name="title" value="<?=$row['title']?>" />
		  <?
		  }
		  else
		  {?>
		  <input class="input" type="text" name="title" value="<?=stripslashes($row['title'])?>"  maxlength="100" />
		  <? }?>		  </td>
        </tr>
		<?php /*?><tr> commented for not to edit the pname. This is be used only for internal purpose
	<td width="32%" align="left" valign="middle" class="tdcolorgray" >Page Name  </td>
          <td width="68%" align="left" valign="middle" class="tdcolorgray">
		<input class="input" type="text" name="pname" value="<?=$row['pname']?>"  /> 
		  </td>
		       
	</tr> <?php */?>
	    <tr>
	  <td width="21%" align="left" valign="middle" class="tdcolorgray" >Hide Page</td>
          <td width="79%" align="left" valign="middle" class="tdcolorgray"><input type="radio" name="hide" value="1" <? if($row['hide']==1) echo "checked";?> />&nbsp;Yes&nbsp;<input type="radio" name="hide"  value="0" <? if($row['hide']==0) echo "checked";?>  />&nbsp;No
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STAT_PAGE_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	</tr>
	 <?php
		// Check whether webclinic is active for current website
		$sql_site = "SELECT in_web_clinic FROM sites WHERE site_id=$ecom_siteid LIMIT 1";
		$ret_site = $db->query($sql_site);
		$row_site = $db->fetch_array($ret_site);
		// Check whether webclinic is active for current website
		if($row_site['in_web_clinic']==1)
		{
	 ?>
			<tr>
			<td align="left" valign="middle" class="tdcolorgray" >Allow Auto linker</td>
			<td align="left" valign="middle" class="tdcolorgray"><input type="checkbox" name="allow_auto_linker" id="allow_auto_linker" value="1" <? if($row['allow_auto_linker']==1) echo "checked";?> /></td>
			</tr>
	 <?php
		}
	 ?> 
	<tr>
	<td width="21%" align="left" valign="middle" class="tdcolorgray" >Page Type  </td>
          <td align="left" valign="middle" class="tdcolorgray" colspan="3">
		  <input type="radio" id="page_type" name="page_type" value="Page" <? if($row['page_type']=='Page') echo "checked";?>  onclick="changePageType()" />&nbsp;Page&nbsp;
		  <input type="radio" id="page_type" name="page_type" value="Link" <? if($row['page_type']=='Link') echo "checked";?> onclick="changePageType()" />&nbsp;Link&nbsp;
		   <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STAT_PAGE_PTYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	</tr>
	</table>	</td>
	<td width="50%" class="tdcolorgray" valign="top" colspan="5">
		
	<table width="100%" cellspacing="0" cellpadding="0">
		<?php
					if($ecom_site_mobile_api==1)
					{ //$row['in_moile_api_sites'] =1;
					?>
					<tr>
					<td width="25%" align="left" valign="middle" class="tdcolorgray" >In Mobile Application</td>
					<td><input type="checkbox" value="1" id="in_mobile_api_sites" name="in_mobile_api_sites" <?php if($row['in_mobile_api_sites']==1) echo "checked"; ?>></td>
					</tr>
					<tr>
					<td colspan="2" >&nbsp;</td>
					</tr>
					<?php
				    }
	 $sql_group="SELECT group_id,group_name FROM static_pagegroup WHERE sites_site_id=$ecom_siteid AND group_hide=0";
		  $res_group = $db->query($sql_group);
		 if($db->num_rows($res_group)>0){
	?>
	<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Page Menu </td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray" >
		  <select name="page_group[]" multiple="multiple">
		 
		  <?
		  #Getting page grousps  of the current page
		  $sql_page_group="SELECT static_pagegroup_group_id FROM static_pagegroup_static_page_map WHERE static_pages_page_id=".$page_id;
		  $res_page_group=$db->query($sql_page_group);
		 if($db->num_rows($res_page_group))
		 {
		 	 	$arr_page_group=array();
				while($row_page_group=$db->fetch_array($res_page_group))
				{
					$arr_page_group[]=$row_page_group['static_pagegroup_group_id'];
				}
		  }	
		  
		   #Getting position values of the site theme
		 
		  while($row_group = $db->fetch_array($res_group))
		  {
		  	$val_group=$row_group['group_name'];
			$id_group=$row_group['group_id'];
			$selected='';
			if(in_array($id_group,$arr_page_group))
			{
				$selected='selected';
			}
		  	echo "<option value=$id_group $selected>".stripslashes($val_group)."</option>";
		  }
	   	  ?>
		  </select>
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STAT_PAGE_PGROUP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		  <? }?>
	</table>	
	<p>&nbsp;</p></td>
	</tr>
	</table>
	</div>
	</td>
</tr>
<tr>
	<td>
	
	</td>
</tr>
    
      <?
		if($row['page_type']=='Page')
		{
			$content_tr_display='';
			$link_tr_display='none';
		}
		else
		{
			$content_tr_display='none';
			$link_tr_display='';
		}
		?>
		<tr>
		<td class="tdcolorgray" colspan="6" valign="middle">
		<div class="editarea_div">
			<table cellpadding="0" cellspacing="0" width="100%">
					<tr id="show_content_tr" style="display:<?=$content_tr_display?>">
						  <td width="10%"  align="left" valign="top" class="tdcolorgray" >Content </td>
						  <td colspan="3"  align="left" valign="top" class="tdcolorgray" style="list-style:upper:roman;">
						  <?php
							$editor_elements = "content";
							include_once(ORG_DOCROOT."/console_v5/js/tinymce.php");
							// Replace all </textarea> tag with <~~textarea>
							$pgcontent = str_ireplace('</textarea>','<~~textarea>',stripslashes($row['content']));
							?>						  
							<textarea style="height:500px; width:700px" id="content" name="content"><?php echo $pgcontent?></textarea>
							<?php // Replacing <~~textarea> with </textarea> using javascript?>
							<script type="text/javascript">
							document.getElementById('content').value = document.getElementById('content').value.replace(/<~~textarea>/gi, "</textarea>");
							</script>
					  </td>
					</tr>
					<tr id="show_link_tr" style="display:<?=$link_tr_display?>">
					  <td  align="left" valign="middle" class="tdcolorgray" >Page Link </td>
					  <td width="36%" align="left" valign="middle"  class="tdcolorgray" ><input name="page_link" type="text" class="input" value="<?=$row['page_link']?>" size="40"  />					  </td>
					  <td width="14%" align="left" valign="middle"  class="tdcolorgray" >Open Link in				      </td>
					  <td width="30%" align="left" valign="middle"  class="tdcolorgray" ><select name="page_link_newwindow" id="page_link_newwindow">
                        <option value="1" <?php echo ($row['page_link_newwindow']==1)?'selected="selected"':''?>>New Window</option>
                        <option value="0" <?php echo ($row['page_link_newwindow']==0)?'selected="selected"':''?>>Same Window</option>
                      </select></td>
					</tr>
			</table>
			</div></td></tr></table>
<?php
	}
	/* SEO tab in static page starts here */
	function show_page_seoinfo($page_id,$page_name,$alert='')
	{
		global $db,$ecom_siteid,$ecom_themeid,$ecom_hostname;
		if(trim($page_name) != 'Home')
		{
			$sql_title	=	"SELECT
											title,meta_description
									FROM
											se_static_title
									WHERE
											sites_site_id=$ecom_siteid
									AND
											static_pages_page_id=".$page_id;
											
			$sql_keys	=	"SELECT
											keywd.keyword_keyword,skey.se_keywords_keyword_id
									FROM
											se_keywords keywd,se_static_keywords skey
									WHERE
											skey.static_pages_page_id = ".$page_id."
									AND
											skey.se_keywords_keyword_id = keywd.keyword_id
									AND
											keywd.sites_site_id = ".$ecom_siteid;
		}
		else
		{
			$sql_title	=	"SELECT
											title,meta_description
									FROM
											se_home_title
									WHERE
											sites_site_id=$ecom_siteid";
											
			$sql_keys	=	"SELECT
											keywd.keyword_keyword,hkey.se_keywords_keyword_id
									FROM
											se_keywords keywd,se_home_keywords hkey
									WHERE
											hkey.sites_site_id = ".$ecom_siteid."
									AND
											hkey.se_keywords_keyword_id = keywd.keyword_id
									AND
											keywd.sites_site_id = ".$ecom_siteid;
		}
		
		$res_title = $db->query($sql_title);
		if($db->num_rows($res_title)>0) 
		{
			$row_title = $db->fetch_array($res_title);
		}
		else
		{
			$row_title['title']	=	"";
			$row_title['meta_description']	=	"";
		}
		//echo $row_title['title'];echo "<br>";
		$res_keys = $db->query($sql_keys);
		if($db->num_rows($res_keys)>0) 
		{
			$field_cnt	=	1;
			$field_values	=	array();
			while($row_keys = $db->fetch_array($res_keys))
			{
				$field_values[$field_cnt]	=	$row_keys['keyword_keyword'];
				$field_cnt++;
			}
		}
		//echo $sql_keys;
?><div class="editarea_div">
		<table width="100%" border="0">
		<tr>
			<td class="tdcolorgray" align="left"><b>Title:</b></td>
			<td align="left"><input type="text" name="page_title" value="<?php echo $row_title['title'];?>" size="84"/></td>
		</tr>
		<tr>
			<td class="tdcolorgray" align="left"><b>Meta description:</b></td>
			<td align="left"><textarea  name="page_meta"cols="63" rows="2"><?php echo $row_title['meta_description'];?></textarea></td>
		</tr>
		<tr>
			<td class="tdcolorgray" align="left"><b>Keyword #1:</b></td>
			<td align="left">
				<input type="text" name="keyword_1" id="keyword_1" value="<?php echo $field_values[1];?>"  size="50"/>&nbsp;&nbsp;&nbsp;
				
			</td>
		  </tr>
		  <tr>
			<td class="tdcolorgray" align="left"><b>Keyword #2:</b></td>
			<td align="left">
				<input type="text" name="keyword_2" id="keyword_2" value="<?php echo $field_values[2];?>" size="50" />&nbsp;&nbsp;&nbsp;
				
			</td>
		  </tr>
		  <tr>
			<td class="tdcolorgray" align="left"><b>Keyword #3:</b></td>
			<td align="left">
				<input type="text" name="keyword_3" id="keyword_3" value="<?php echo $field_values[3];?>"  size="50"/>&nbsp;&nbsp;&nbsp;
				
			</td>
		  </tr>
		  <tr>
			<td class="tdcolorgray" align="left"><b>Keyword #4:</b></td>
			<td align="left">
				<input type="text" name="keyword_4" id="keyword_4" value="<?php echo $field_values[4];?>"  size="50"/>&nbsp;&nbsp;&nbsp;
			</td>
		  </tr>
		  <tr>
			<td class="tdcolorgray" align="left"><b>Keyword #5:</b></td>
			<td align="left">
				<input type="text" name="keyword_5" id="keyword_5" value="<?php echo $field_values[5];?>"  size="50"/>&nbsp;&nbsp;&nbsp;
			</td>
		  </tr>
		</table></div>
		
<?php
	}	
	/* SEO tab in static page ends here */
?>

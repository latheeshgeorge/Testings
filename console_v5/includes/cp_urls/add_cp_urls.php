<?php
/*#################################################################
# Script Name 	: list_summary.php
# Description 		: Page for listing cost per click summary report
# Coded by 		: SNY
# Created on		: 31-Oct-2008
#################################################################*/
//Define constants for this page
$table_name='costperclick_adverturl';
$page_type='Cost Per Clicks';
$help_msg = get_help_messages('LIST_COMPANY_TYPES_MESS1');

$url_total_clicks = 0;
if($ftype=='Edit') 
{
	//#Select condition for getting total count
	$sql = "SELECT costperclick_keywords_keyword_id, costperclick_adverplaced_on_advertplace_id,
						 url_mypage, url_hidden, url_setting_noofclicks, url_setting_days, url_setting_rateperclick, url_total_clicks	 
							 FROM $table_name  
							 	WHERE sites_site_id='".$ecom_siteid."' AND url_id='".$_REQUEST['url_id']."'";
	$res = $db->query($sql);
	if($db->num_rows($res)==0) { echo " <font color='red'> You Are Not Authorised  </a>"; exit; }
	$row = $db->fetch_array($res);
	
	$cbo_keyword = $row['costperclick_keywords_keyword_id'];
	$cbo_advertlocation = $row['costperclick_adverplaced_on_advertplace_id'];
	$url_mypage = $row['url_mypage'];
	$url_hidden = $row['url_hidden'];
	$url_setting_noofclicks = $row['url_setting_noofclicks'];	
	$url_setting_days = $row['url_setting_days'];	
	$url_setting_rateperclick = $row['url_setting_rateperclick'];	
	$url_total_clicks = $row['url_total_clicks'];
}	
/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = (is_numeric($_REQUEST['records_per_page']) and $_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:10;#Total records shown in a page
$pg = ($_REQUEST['search_click'])?1:$_REQUEST['pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
$pages = ceil($numcount / $records_per_page);#Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}
$start = ($pg - 1) * $records_per_page;#Starting record.
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
/////////////////////////////////////////////////////////////////////////////////////
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=costperclick_report&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start";
?>
<script language="javascript">
	function advertother() 
	{
		frm = document.frmlisturls;
		
		if(frm.cbo_advertlocation.value=='-other-') {
			document.getElementById('advertOther').style.display = '';
		} else {
			document.getElementById('advertOther').style.display = 'none';	
		}	
		
	}
	function keywordother() 
	{
		frm = document.frmlisturls;
		
		if(frm.cbo_keyword.value=='-other-') {
			document.getElementById('keywordOther').style.display = '';
		} else {
			document.getElementById('keywordOther').style.display = 'none';	
		}	
		
	}	
function validate() 
	{
		frm = document.frmlisturls;
		 
		if(frm.keyword_other.value == "" && frm.cbo_keyword.value=='-other-') {
			alert("Please Enter Other Keyword");
			frm.keyword_other.focus();
			return false;
		} else if(frm.advert_other.value=="" && frm.cbo_advertlocation.value=='-other-') {
			alert("Please Enter Other Adverts location");
			frm.advert_other.focus();
			return false;
		} else {
			return true;
		}
		
	}
	
</script>
<form name="frmlisturls" action="home.php" method="post" onsubmit="return validate()" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="costperclick_urls" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td width="100%" align="left" valign="middle" class="treemenutd">
		  <div class="treemenutd_div">
		   <a href="home.php?request=costperclick_urls&&search_name=<?php echo $_REQUEST['search_name']?>&cbo_keyword=<?php echo $_REQUEST['cbo_keyword']?>&cbo_advertlocation=<?php echo $_REQUEST['cbo_advertlocation']?>&start=<?php echo $_REQUEST['start']?>&pg=<?php echo $_REQUEST['pg']?>&sort_by=<?php echo $_REQUEST['sort_by']?>&sort_order=<?php echo $_REQUEST['sort_order']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>" class="edittextlinks">Cost Per Clicks</a><span><?php if($ftype=='Edit') echo "Edit" ; else echo "Add"?> Cost Per Click </span></div></td>
    </tr>
	<tr>
		  <td align="left" valign="middle" class="helpmsgtd_main">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
	<?php 
			if($alert)
			{			
		?>
        		<tr>
          			<td align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
          		</tr>
		 <?php
		 	}
		 ?> 
    <tr>
      <td height="48" class="sorttd" >
		  
		  		  <div class="editarea_div">

		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="20%" valign="top">&nbsp;</td>
          <td colspan="2" valign="top">&nbsp;</td>
        </tr>
         <tr>
          <td width="20%" align="left" valign="middle" class="tdcolorgray" >Keyword</td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray">
		   <?php
			$kw_disp = 'none';
			$loc_disp = 'none';
		   if($url_total_clicks == 0) 
		   {
			  	// Get the list of keywords set for current site
				$sql_kw = "SELECT keyword_id,keyword_word 
										FROM 
											costperclick_keywords  
										WHERE 
											sites_site_id = $ecom_siteid 
										ORDER BY 
											keyword_word";
				$ret_kw = $db->query($sql_kw);
				$kw_arr['-other-']  = "- Other - ";
				if ($db->num_rows($ret_kw))
				{
					while ($row_kw = $db->fetch_array($ret_kw))
					{
						$kw_arr[$row_kw['keyword_id']] = stripslashes($row_kw['keyword_word']);
					}
				}
				
				if(count($kw_arr)==1 or $ftype!='Edit')
					$kw_disp = '';

				
				$onchange = "javascript:keywordother()";
				echo generateselectbox('cbo_keyword',$kw_arr,$cbo_keyword,'',$onchange);
				if($cbo_keyword)
				{
					$sql_kw = "SELECT keyword_id,keyword_word 
										FROM 
											costperclick_keywords  
										WHERE 
											sites_site_id = $ecom_siteid AND keyword_id ='".$cbo_keyword ."'
										LIMIT 1";
					$ret_kw = $db->query($sql_kw);
					$ret_krow = $db->fetch_array($ret_kw);
					$keyword = $ret_krow['keyword_word']; 
				}
			} else {
				$sql_kw = "SELECT keyword_id,keyword_word 
										FROM 
											costperclick_keywords  
										WHERE 
											sites_site_id = $ecom_siteid AND keyword_id ='".$cbo_keyword ."'
										ORDER BY 
											keyword_word";
				$ret_kw = $db->query($sql_kw);
				$ret_krow = $db->fetch_array($ret_kw);
				echo $ret_krow['keyword_word'];
				$keyword = $ret_krow['keyword_word']; 
			}	
			  ?><span id="keywordOther" style="display:<?php echo $kw_disp?>;"> 
		   <input type="text" name="keyword_other" size="40" /></span></td>
        </tr>
        <tr>
          <td valign="top" class="tdcolorgray" ><div align="left">Third party site  </div></td>
          <td colspan="2" valign="top" class="tdcolorgray" > <div align="left">
            <?php
		  if($url_total_clicks == 0) 
		   {	
				// Get the list of advertised locations set for current site
				$sql_adv = "SELECT advertplace_id,advertplace_name 
										FROM 
											costperclick_advertplacedon  
										WHERE 
											sites_site_id = $ecom_siteid 
										ORDER BY 
											advertplace_name";
				$ret_adv = $db->query($sql_adv);
				$adv_arr['-other-'] = "- Other -";
				if ($db->num_rows($ret_adv))
				{
					while ($row_adv = $db->fetch_array($ret_adv))
					{
						$adv_arr[$row_adv['advertplace_id']] = stripslashes($row_adv['advertplace_name']);
					}
				}
				
				if (count($adv_arr)==1 or $ftype!='Edit')
					$loc_disp = '';
				$onchange = "javascript:advertother()";
				echo generateselectbox('cbo_advertlocation',$adv_arr,$cbo_advertlocation,'',$onchange);
			}	else {
				$sql_adv = "SELECT advertplace_id,advertplace_name 
										FROM 
											costperclick_advertplacedon  
										WHERE 
											sites_site_id = $ecom_siteid AND advertplace_id ='".$cbo_advertlocation."' 
										ORDER BY 
											advertplace_name";
				$ret_adv = $db->query($sql_adv); 
				$ret_krow = $db->fetch_array($ret_adv);
				echo $ret_krow['advertplace_name'];
				$adverts = $ret_krow['advertplace_name'];				
			}	
			  ?>
          <span id="advertOther" style="display:<?php echo $loc_disp?>;"> <input type="text" name="advert_other" size="40" />
          </span> (e.g. www.google.com) </div></td>
        </tr>
        <tr>
          <td valign="top" class="tdcolorgray" ><div align="left"> My Page Url</div></td>
          <td colspan="2" valign="top" class="tdcolorgray" ><div align="left">
            <input type="text" name="url_mypage" size="40" value="<? echo $url_mypage; ?>" />
          <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('MY_PAGE_URL_HELP')?>')"; onMouseOut="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> (it can be url of any of the pages in your site) </div></td>
        </tr>
        <tr>
          <td valign="top" class="tdcolorgray" ><div align="left">Cost Per Click </div></td>
          <td colspan="2" valign="top" class="tdcolorgray" ><div align="left">
            <input type="text" name="url_setting_rateperclick" size="5" value="<?=$url_setting_rateperclick?>" />
           <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('COST_PER_CLICK_HELP')?>')"; onMouseOut="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></div></td>
        </tr>
        <tr>
          <td valign="top" class="tdcolorgray" ><div align="left">Hidden</div></td>
          <td colspan="2" valign="top" class="tdcolorgray" ><div align="left">
                <input type="radio" name="url_hidden" value="1" <? if($url_hidden==1) echo "checked";?> />
            Yes
              <input name="url_hidden" type="radio" value="0" <? if($url_hidden==0) echo "checked";?> />
          No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('COST_PERCLICK_URL_HIDE')?>')"; onMouseOut="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></div></td>
        </tr>
        <tr>
          <td valign="top" class="tdcolorgray" ><div align="left"><strong>Fraud Click Rules</strong></div></td>
          <td width="31%" valign="top" class="tdcolorgray" >&nbsp;</td>
          <td width="49%" valign="top" class="tdcolorgray" align="left">
		  <? 
		  if($ftype=='Edit') 
		  {
		  		
				$keyword =  str_replace(" ", "-", $keyword);

				$urlplaced =  "http://".$ecom_hostname."/".$keyword."-cpc".$_REQUEST['url_id'].".html";
		  ?>
		  <strong> Link to be Placed in Third Party Site </strong>
		  <? } ?>
		  </td>
        </tr>
        <tr>
          <td colspan="2" valign="top" class="tdcolorgray" style="padding-left:10px;" > <div align="left">Allow 
            <input type="text" name="url_setting_noofclicks" size="5" value="<?=$url_setting_noofclicks?>" /> 
            Clicks In <span class="tdcolorgray" style="padding-left:10px;">
              <input type="text" name="url_setting_days" size="5" value="<?=$url_setting_days?>" />
            </span>Days from same IP Address </div></td>
          <td valign="top" class="tdcolorgray" >
		   <? 
		  if($ftype=='Edit') 
		  { ?>
		  <?php /*?><textarea name="textarea" rows="6" cols="40"><?php */?><?=$urlplaced?><?php /*?></textarea><?php */?><? } ?>
		  </td>
        </tr>
        <tr>
          <td valign="top" class="tdcolorgray" >&nbsp;</td>
          <td valign="top" class="tdcolorgray" >&nbsp;</td>
          <td valign="top" class="tdcolorgray" >&nbsp;</td>
        </tr>
        
      </table>
      </div>
      </td>
    </tr>
     
    <tr>
      <td class="listingarea1" align="center">
		   <div class="editarea_div">

		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="3" valign="top" class="tdcolorgray" align="center" ><input name="Submit" type="submit" class="red" value=" Save " /></td>
          </tr>
        <tr>
          <td  valign="top">
		  
		  <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
		   <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
		  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
		  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
		  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
		  <? 
		  if($ftype=='Edit') 
		  {
		  ?>
		  <input type="hidden" name="fpurpose" id="fpurpose" value="update" />
		  <input type="hidden" name="url_id" id="url_id" value="<?=$_REQUEST['url_id']?>" />
		  
		  <? } else { ?>
		  <input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
		  <? } 
		   if($url_total_clicks > 0) 
		   {	
		  ?>
		   <input type="hidden" name="cbo_advertlocation" id="cbo_advertlocation" value="<?=$cbo_advertlocation?>" />
		   <input type="hidden" name="cbo_keyword" id="cbo_keyword" value="<?=$cbo_keyword?>" />
		   
		  <? } ?>
		  </td>
		  </tr>
		  </table>
		  </div>
	  </td>
    </tr>
  </table>
</form>

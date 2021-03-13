<?php
	/*#################################################################
	# Script Name 	: list_settings_currencies.php
	# Description 	: Page for listing Curriencies avilable in the site
	# Coded by 		: ANU
	# Created on	: 15-June-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name = 'se_keywords';
$page_type = 'Keywords';
$help_msg = get_help_messages('LIST_ENTIRE_KEYWORD_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistEntireKeywords,\'keyword_id[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistEntireKeywords,\'keyword_id[]\')"/>','Slno.','Keyword','Used in Site?');
$header_positions=array('left','left','left','left');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('keywords');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing search terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'keyword_keyword':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('keyword_keyword' => 'Keywords');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['keywords']) {
	$where_conditions .= "AND  keyword_keyword LIKE '%".add_slash($_REQUEST['keywords'])."%' ";
}

//#Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);#Getting total count of records
/////////////////////////////////For paging///////////////////////////////////////////
/*$records_per_page = (is_numeric($_REQUEST['records_per_page']))?$_REQUEST['records_per_page']:10;#Total records shown in a page
          


$start 		= (!isset($_REQUEST['start']))?0:$_REQUEST['start'];// This variable is set to zero for the first page
$p_f 		= (!isset($_REQUEST['p_f']))?0:$_REQUEST['p_f']; // This variable is set to zero for the first page
$limit 		= $records_per_page;   	// No of records to be shown per page.
$page_limit	= 15;	
$totcount	= $numcount;	// total number of records 
*//////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = (is_numeric($_REQUEST['records_per_page']) &&($_REQUEST['records_per_page']))?$_REQUEST['records_per_page']:10;#Total records shown in a page
$pg= ($_REQUEST['search_click']==1)?1:$_REQUEST['pg'];
if (!($pg > 0) || $pg == 0) { $pg = 1; }
$start = ($pg - 1) * $records_per_page;#Starting record.
$pages = ceil($numcount / $records_per_page);#Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
/////////////////////////////////////////////////////////////////////////////////////
//$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=general_settings&fpurpose=captions&records_per_page=$records_per_page&start=$start";
$query_string .= "request=seo_keyword&fpurpose=entire_keywords&sort_by=$sort_by&sort_order=$sort_order";
/////// end paging/////////
/*$records_per_page = (is_numeric($_REQUEST['records_per_page']))?$_REQUEST['records_per_page']:10;#Total records shown in a page
$pg= $_REQUEST['pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
$startrec = ($pg - 1) * $records_per_page;#Starting record.
$pages = ceil($numcount / $records_per_page);#Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);*/

?>
<script  type="text/javascript">
function ajax_return_contents() 
{
	var ret_val='';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val = req.responseText;
			document.getElementById('maincontent').innerHTML=ret_val;
			hide_processing();
		}
		else
		{
			 show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}

function getSelected(purpose){
		document.frmlistEntireKeywords.fpurpose.value=purpose;
		document.frmlistEntireKeywords.submit();
}
function call_ajax_SaveKeywords(keywords,sortby,sortorder,recs,start,pg)
{
	
	var qrystr = 'keywords='+keywords+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;
	
	if(confirm('Are you sure you want to Save the keywords?'))
		{
			show_processing();
			document.frmlistEntireKeywords.fpurpose.value='Save_entire_keyword';
		document.frmlistEntireKeywords.submit();
			//Handlewith_Ajax('services/seo_keyword.php','fpurpose=Save_entire_keyword&'+qrystr);
		}
		
}
function checkDelete(){
len=document.frmlistEntireKeywords.length;
	var cnt=0;	
	var def=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistEntireKeywords.elements[j]
		if (el!=null && el.name== "currency_id[]" )
		   if(el.checked) {
		   		cnt++;
				currency_id=el.value;
				//alert(currency_id);
		   }
		   
	}
	if(cnt==0) {
		alert('Please select atleast one Currency to delete ');
		return false;
		
	}
	
if(confirm('Are you sure you want to delete the currency?')){
getSelected('delete_currency');
}else{
return false;
}
}
function call_ajax_delete(keywords,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var del_ids 			= '';
	var qrystr				= 'keywords='+keywords+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistEntireKeywords.elements.length;i++)
	{
		if (document.frmlistEntireKeywords.elements[i].type =='checkbox' && document.frmlistEntireKeywords.elements[i].name=='keyword_id[]')
		{

			if (document.frmlistEntireKeywords.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistEntireKeywords.elements[i].value;
			
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the Keywords to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Keyword?'))
		{
			show_processing();
			Handlewith_Ajax('services/seo_keyword.php','fpurpose=delete_entirekeyword&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
</script>
<form name="frmlistEntireKeywords" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="entire_keywords" />
<input type="hidden" name="request" value="seo_keyword" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
<input type="hidden" name="search_click" value="" />

  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> List Entire Keywords</span></div></td>
    </tr>
	<tr>
	  <td align="left" valign="middle" class="helpmsgtd_main" colspan="3">
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
          			<td  colspan="7" align="center" valign="middle" class="errormsg"><?php echo $alert?></td>
          		</tr>
		 <?php
		 	}
		 ?> 
    <tr>
		<td  colspan="7" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td>
	</tr>
	<tr>
      <td height="48" colspan="3" class="sorttd">
	    <div class="sorttd_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0">		
        <tr>
          <td width="12%" height="30"   align="left" valign="middle">Key words </td>
          <td width="23%" height="30" align="left" valign="middle"><input name="keywords" type="text" class="textfeild" id="keywords" value="<?=$_REQUEST['keywords']?>" /></td>
          <td width="11%" height="30" align="left" valign="middle">Records Per Page </td>
          <td width="10%" height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" value="<?=$records_per_page?>" /></td>
          <td width="5%" height="30" align="left" valign="middle">Sort By</td>
          <td width="25%" height="30" align="left" valign="middle"><?=$sort_option_txt?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;<?=$sort_by_txt?></td>
          <td width="14%" height="30" align="right" valign="middle"><input name="go_button" type="button" class="red" id="go_button" value="Go" onclick="document.frmlistEntireKeywords.search_click.value=1;getSelected('entire_keywords')" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ENTIRE_KEYWORD_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
      </table>
	  </div>
      </td>
	</tr>  
    <tr>
      <td colspan="3" class="listingarea">
	  <div class="listingarea_div">
	  <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	  <tr>
      <td class="listeditd" align="left" valign="middle" colspan="3"> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['keywords']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a> </td>
      <td width="50%" align="right" valign="middle" class="listeditd"><input name="Save_Keywords" type="button" class="red" id="Save_Keywords" value="Save Keywords" onclick="call_ajax_SaveKeywords('<?=$currency_name?>','<?=$sort_by?>','<?=$sort_order?>','<?=$records_per_page?>','<?=$start?>',<?=$pg?>)"/>&nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ENTIRE_KEYWORD_SAVE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    </tr>
        <?
	   echo table_header($table_headers,$header_positions);
		$sql_settings_currency = "SELECT keyword_id,keyword_keyword FROM $table_name 
									$where_conditions 
										ORDER BY $sort_by $sort_order 
											LIMIT $start,$records_per_page ";
	   $res = $db->query($sql_settings_currency); 
	   if ($db->num_rows($res)){
	   while($row = $db->fetch_array($res))
	   {
			$count_no++;
			$array_values = array();
			if($count_no %2 == 0)
				$class_val="listingtablestyleA";
			else
				$class_val="listingtablestyleB";	
	   
	   ?>
        <tr >
          <td  width="6%" align="left" valign="middle" class="<?=$class_val;?>"><input name="keyword_id[]" value="<? echo $row['keyword_id']?>" type="checkbox" /></td>
          <td width="2%" align="left" valign="middle"  class="<?=$class_val;?>"><?=$count_no?></td>
          <td width="42%"  align="left" valign="middle" class="<?=$class_val;?>"><input type="text" size="45" name="txtkey_<?php echo $row['keyword_id']?>" value="<?php echo stripslashes($row['keyword_keyword']); ?>" /></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><?php 
	  	$assigned 		= 'No';
	  	//Check whether this keyword is assigned to any of the categories in current site
		if($assigned 	== 'No')
		{
			$sql_check = "SELECT se_keywords_keyword_id FROM se_category_keywords WHERE se_keywords_keyword_id = ".$row['keyword_id']." LIMIT 1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check))
			{
				$assigned 	= 'Yes';
			}
		}
		if($assigned == 'No')
		{
			//Check whether this keyword is assigned to any of the properties in current site
			$sql_check = "SELECT se_keywords_keyword_id FROM se_product_keywords WHERE se_keywords_keyword_id = ".$row['keyword_id']." LIMIT 1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check))
			{
				$assigned 	= 'Yes';
			}
			else
			{
				//Check whether this keyword is assigned to any of the static pages in current site
				$sql_check = "SELECT se_keywords_keyword_id FROM se_static_keywords WHERE se_keywords_keyword_id = ".$row['keyword_id']." LIMIT 1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check))
				{
					$assigned 	= 'Yes';
				}
			}
		}
		if($assigned == 'No')
		{
				$sql_check = "SELECT se_keywords_keyword_id FROM se_bestseller_keywords WHERE se_keywords_keyword_id = ".$row['keyword_id']." LIMIT 1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check))
				{
					$assigned 	= 'Yes';
				}
		}
		if($assigned == 'No')
		{
				$sql_check = "SELECT se_keywords_keyword_id FROM se_combo_keywords WHERE se_keywords_keyword_id = ".$row['keyword_id']." LIMIT 1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check))
				{
					$assigned 	= 'Yes';
				}
		} 
		if($assigned == 'No')
		{
				$sql_check = "SELECT se_keywords_keyword_id FROM se_forgotpassword_keywords
								 WHERE se_keywords_keyword_id = ".$row['keyword_id']." LIMIT 1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check))
				{
					$assigned 	= 'Yes';
				}
		}
		if($assigned == 'No')
		{
				$sql_check = "SELECT se_keywords_keyword_id FROM se_home_keywords
								 WHERE se_keywords_keyword_id = ".$row['keyword_id']." LIMIT 1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check))
				{
					$assigned 	= 'Yes';
				}
		} 
		if($assigned == 'No')
		{
				$sql_check = "SELECT se_keywords_keyword_id FROM se_registration_keywords
								 WHERE se_keywords_keyword_id = ".$row['keyword_id']." LIMIT 1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check))
				{
					$assigned 	= 'Yes';
				}
		} 
		if($assigned == 'No')
		{
				$sql_check = "SELECT se_keywords_keyword_id FROM se_savedsearch_main_keywords
								 WHERE se_keywords_keyword_id = ".$row['keyword_id']." LIMIT 1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check))
				{
					$assigned 	= 'Yes';
				}
		} 
		if($assigned == 'No')
		{
				$sql_check = "SELECT se_keywords_keyword_id FROM se_search_keyword
								 WHERE se_keywords_keyword_id = ".$row['keyword_id']." LIMIT 1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check))
				{
					$assigned 	= 'Yes';
				}
		} 
		if($assigned == 'No')
		{
				$sql_check = "SELECT se_keywords_keyword_id FROM se_shelf_keywords
								 WHERE se_keywords_keyword_id = ".$row['keyword_id']." LIMIT 1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check))
				{
					$assigned 	= 'Yes';
				}
		} 
		if($assigned == 'No')
		{
				$sql_check = "SELECT se_keywords_keyword_id FROM se_shop_keywords
								 WHERE se_keywords_keyword_id = ".$row['keyword_id']." LIMIT 1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check))
				{
					$assigned 	= 'Yes';
				}
		}
		if($assigned == 'No')
		{
				$sql_check = "SELECT se_keywords_keyword_id FROM se_sitemap_keywords
								 WHERE se_keywords_keyword_id = ".$row['keyword_id']." LIMIT 1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check))
				{
					$assigned 	= 'Yes';
				}
		}
		if($assigned == 'No')
		{
				$sql_check = "SELECT se_keywords_keyword_id FROM se_sitereviews_keywords
								 WHERE se_keywords_keyword_id = ".$row['keyword_id']." LIMIT 1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check))
				{
					$assigned 	= 'Yes';
				}
		}
		if($assigned == 'No')
		{
				$sql_check = "SELECT se_keywords_keyword_id FROM se_static_keywords
								 WHERE se_keywords_keyword_id = ".$row['keyword_id']." LIMIT 1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check))
				{
					$assigned 	= 'Yes';
				}
		}
		
		
		if($assigned=='No')
			echo "<span class='redtext'><strong>$assigned</strong></span>";
		else
			echo $assigned;
	  ?></td>
         
        </tr>
        <?
	  }
	}
	  else
		{
	?>
        <tr>
          <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>"> No Keywords for this site. </td>
        </tr>
        <?php
		}
	?>
	<tr>
      <td class="listeditd" align="left" valign="middle" colspan="3"><a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['keywords']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a> </td>
      <td class="listeditd" align="right" valign="middle"></td>
    </tr>
      </table>
	  </div>
	  </td>
    </tr>
	<tr>
      <td class="listing_bottom_paging" align="right" valign="middle" colspan="2"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td>
    </tr>
   
    
  </table>
</form>

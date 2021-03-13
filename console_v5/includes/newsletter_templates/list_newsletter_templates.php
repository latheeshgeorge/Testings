<?php
	/*#################################################################
	# Script Name 	: list_newsletter_templates.php
	# Description 	: Page for Listing Product Store 
	# Coded by 		: SG
	# Created on	: 11-Aug-2008
	# Modified by	: SG
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$table_name			= 'newsletter_template';
$page_type			= 'Newsletter Templates';
$help_msg 			= get_help_messages('NEWS_TEMPLATE_MESS1');
//'This section lists the Product Category Groups available on the site. Here there is provision for adding a Product Category Groups, editing, & deleting it.';
$table_headers 		= array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_newslettertemplate,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_newslettertemplate,\'checkbox[]\')"/>','Slno.','Newsletter Template Name','Hidden');
$header_positions	= array('center','left','left','center','center');
$colspan 			= count($table_headers);

//#Search terms
$search_fields = array('search_newstemplate_name');

$query_string = "request=newsletter_templates&sort_by=".$sort_by."&sort_order=".$sort_order;
foreach($search_fields as $v) {
	$query_string .= "&$v=".$_REQUEST[$v]."";//#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'newstemplate_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('newstemplate_name' => 'Template Name');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_newstemplate_name']) {
	$where_conditions .= "AND ( newstemplate_name LIKE '%".add_slash($_REQUEST['search_newstemplate_name'])."%') ";
}

//#Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);#Getting total count of records
/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = (is_numeric($_REQUEST['records_per_page']) and $_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:10;#Total records shown in a page
$pg = ($_REQUEST['search_click'])?1:$_REQUEST['pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
$pages = ceil($numcount / $records_per_page);#Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}
if ($pg>=1)
{
	$start = ($pg - 1) * $records_per_page;//#Starting record.
	$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
}	
else
{
	$start = $count_no = 0;	
}

/////////////////////////////////////////////////////////////////////////////////////

$sql_qry = "SELECT newstemplate_id,newstemplate_name,newstemplate_hide FROM $table_name 
					$where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
					//echo $sql_qry ;
$ret_qry = $db->query($sql_qry);
?>
<script type="text/javascript">
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
function call_ajax_delete(cname,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var del_ids 			= '';
	var qrystr				= 'search_newstemplate_name='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_newslettertemplate.elements.length;i++)
	{
		if (document.frm_newslettertemplate.elements[i].type =='checkbox' && document.frm_newslettertemplate.elements[i].name=='checkbox[]')
		{

			if (document.frm_newslettertemplate.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frm_newslettertemplate.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the Newsletter Template to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Newsletter Template?'))
		{
			show_processing();
			Handlewith_Ajax('services/newsletter_templates.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function call_ajax_changestatus(cname,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var curid				= 0;
	var cat_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frm_newslettertemplate.cbo_changehide.value;
	var qrystr				= 'search_newstemplate_name='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_newslettertemplate.elements.length;i++)
	{
		if (document.frm_newslettertemplate.elements[i].type =='checkbox' && document.frm_newslettertemplate.elements[i].name=='checkbox[]')
		{

			if (document.frm_newslettertemplate.elements[i].checked==true)
			{
				atleastone = 1;
				if (cat_ids!='')
					cat_ids += '~';
				 cat_ids += document.frm_newslettertemplate.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the newsletter template to change the hide status');
	}
	else
	{
		if(confirm('Change Hide Status of Seleted newsletter template(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/newsletter_templates.php','fpurpose=change_hide&'+qrystr+'&catids='+cat_ids);
		}	
	}	
}
function go_edit(cname,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var del_ids 			= '';
	var qrystr				= 'search_newstemplate_name='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frm_newslettertemplate.elements.length;i++)
	{
		if (document.frm_newslettertemplate.elements[i].type =='checkbox' && document.frm_newslettertemplate.elements[i].name=='checkbox[]')
		{

			if (document.frm_newslettertemplate.elements[i].checked==true)
			{
				atleastone += 1;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the template to edit');
	}
	else
	{
		if(atleastone==1)
		{
			show_processing();
			document.frm_newslettertemplate.fpurpose.value='edit';
			document.frm_newslettertemplate.submit();
		}	
		else
		{
			alert('Please select only one template to delete.');
		}
	}	
}
</script>
<form method="post" name="frm_newslettertemplate" class="frmcls" action="home.php">
<input type="hidden" name="request" value="newsletter_templates" />
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="search_click" value="" />
<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> List Newsletter Templates</span></div></td>
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
          			<td colspan="3" align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
          		</tr>
		 <?php
		 	}
		 ?> 
	<?php 
		if ($db->num_rows($ret_qry))
		{
	?>
    <tr><td colspan="3" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);?></td></tr>
	<?php
		}
	?>
	<tr>
      <td height="48" colspan="3" class="sorttd">
		<div class="sorttd_div">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
        <tr>
          <td width="7%" height="30" align="left" valign="middle">Template Name </td>
          <td width="12%" height="30" align="left" valign="middle"><input name="search_newstemplate_name" type="text" class="textfeild" id="search_newstemplate_name" value="<?php echo $_REQUEST['search_newstemplate_name']?>" /></td>
          <td width="8%" height="30" align="left" valign="middle">Records Per Page </td>
          <td width="4%" height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
          <td width="4%" height="30" align="left" valign="middle">Sort By</td>
          <td width="22%" height="30" align="left" valign="middle"><?=$sort_option_txt?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp; <?=$sort_by_txt?></td>
          <td width="6%" height="30" align="left" valign="middle"><input name="button5" type="submit" class="red" id="button5" value="Go" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CUST_ASS_NEWSLETT_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td width="35%" height="30" align="left" valign="middle">&nbsp;</td>
        </tr>
      </table>
        </div></td>
    </tr>
    
    <tr>
      <td colspan="3" class="listingarea">
	  <div class="listingarea_div">
	  <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	  <tr>
      <td align="left" valign="middle" colspan="3" class="listeditd"><a href="home.php?request=newsletter_templates&fpurpose=add&search_newstemplate_name=<?php echo $_REQUEST['search_newstemplate_name']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" class="addlist">Add</a>
	  	<?php
			if ($db->num_rows($ret_qry))
			{
		?>	
				<a href="#" class="editlist" onclick="go_edit('<?php echo $_REQUEST['search_newstemplate_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_newstemplate_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
		<?php
			}
		?>		</td>
      <td align="right" valign="middle" class="listeditd"><?php
			if ($db->num_rows($ret_qry))
			{
		?>
Change Hidden Status
  <?php
					$chhide_array = array('0'=>'No','1'=>'Yes');
					echo generateselectbox('cbo_changehide',$chhide_array,0);
				?>
  <input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['search_newstemplate_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('NEWS_TEMPLATE_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
  <?php
			}
		?></td>
    </tr>
        <?php  
	 	echo table_header($table_headers,$header_positions); 
		if ($db->num_rows($ret_qry))
		{ 
			$srno = 1;
			while ($row_qry = $db->fetch_array($ret_qry))
			{
				$cls = ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
	 ?>
        <tr>
          <td align="center" valign="middle" class="<?php echo $cls?>" width="7%"><input type="checkbox" name="checkbox[]" id="checkbox[]" value="<?php echo $row_qry['newstemplate_id']?>" /></td>
          <td align="left" valign="middle" class="<?php echo $cls?>" width="1%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?php echo $cls?>" width="30%"><a href="home.php?request=newsletter_templates&fpurpose=edit&checkbox[0]=<?php echo $row_qry['newstemplate_id']?>&search_newstemplate_name=<?php echo $_REQUEST['search_newstemplate_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>&records_per_page=<?php echo $records_per_page?>" title="edit" class="edittextlink"><?php echo stripslashes($row_qry['newstemplate_name'])?></a></td>
          <td align="center" valign="middle" class="<?php echo $cls?>"><?php echo ($row_qry['newstemplate_hide']==1)?'Yes':'No'?></td>

		</tr>
        <?php
			}
		}
		else
		{
	?>
			<tr>
			  <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>"> No Newsletter Template found. </td>
			</tr>
        <?php
		}
	?>
	<tr>
      <td class="listeditd" align="left" valign="middle" colspan="3"><a href="home.php?request=newsletter_templates&fpurpose=add&search_newstemplate_name=<?php echo $_REQUEST['search_newstemplate_name']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>&start=<?php echo $start?>&p_f=<?php echo $p_f?>&records_per_page=<?php echo $records_per_page?>" class="addlist" onclick="show_processing();">Add</a>
	  <?php 
		if ($db->num_rows($ret_qry))
		{
	?>
	  		<a href="#" class="editlist" onclick="go_edit('<?php echo $_REQUEST['search_newstemplate_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>')">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_newstemplate_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	 <?php
	 	}
	 ?>	  </td>
      <td class="listeditd" align="right" valign="middle">
	 </td>
    </tr>
      </table>
	  </div></td>
    </tr>
	<tr>
     
      <td class="listing_bottom_paging" align="right" valign="middle" colspan="2">
	  <?php 
		if ($db->num_rows($ret_qry))
		{
			paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan);
		}
		?></td>
    </tr>
  </table>
</form>
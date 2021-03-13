<?php
	/*#################################################################
	# Script Name 	: list_fbcontent.php
	# Description 	: Page for listing Newsletters
	# Coded by 		: ANU
	# Created on	: 29-Aug-2007
	# Modified by	: ANU
	# Modified On	: 29-Aug-2007
	#################################################################*/
//Define constants for this page
$table_name='facebook_tab_content';
$page_type='Facebook Content';
$help_msg = get_help_messages('LIST_FACEBOOK_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistFacebook,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistFacebook,\'checkbox[]\')"/>','Slno.','Title','Action','is Active?');
$header_positions=array('left','left','left');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('fb_subject');
foreach($search_fields as $v) {
	$query_string .= "$v=${$v}&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_options = array('fb_subject' => 'Title');
$sort_by = (!array_key_exists($_REQUEST['sort_by'],$sort_options))?'fb_subject':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( fb_subject LIKE '%".add_slash($_REQUEST['search_name'])."%')";
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

$start = ($pg - 1) * $records_per_page;#Starting record.
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
/////////////////////////////////////////////////////////////////////////////////////
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=facebook_tab&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start";
?>
<script language="javascript">

function checkSelected()
{
	len=document.frmlistFacebook.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistFacebook.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one advert ');
		return false;
	}
	show_processing();
	return true;
}
function edit_selected()
{
	
	len=document.frmlistFacebook.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistFacebook.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one template');
	}
	else if(cnt>1 ){
		alert('Please select only one template to edit');
	}
	else
	{
		show_processing();
		document.frmlistFacebook.fpurpose.value='edit';
		document.frmlistFacebook.submit();
	}
	
	
}
function call_ajax_changestatus(search_name,sortby,sortorder,recs,start,pg)
{
	
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
    var chstatus = '';
	len=document.frmlistFacebook.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistFacebook.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one template');
	}
	else if(cnt>1 ){
		alert('Please select only one template to change status');
	}
	else
	{
		chstatus = document.frmlistFacebook.fb_is_active.value;
		for(i=0;i<document.frmlistFacebook.elements.length;i++)
		{
			if (document.frmlistFacebook.elements[i].type =='checkbox' && document.frmlistFacebook.elements[i].name=='checkbox[]')
			{

				if (document.frmlistFacebook.elements[i].checked==true)
				{
					atleastone = 1;				
					 del_ids = document.frmlistFacebook.elements[i].value;
				}	
			}
		}

		if(confirm('Are you sure you want to change status of template?'))
		{
			show_processing();
			Handlewith_Ajax('services/facebook_tab_content.php','fpurpose=chstatus&del_ids='+del_ids+'&chastatus='+chstatus+'&'+qrystr);
		}	
	}	
}
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
function call_ajax_delete(search_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 	= 0;
	var del_ids 	= '';
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistFacebook.elements.length;i++)
	{
		if (document.frmlistFacebook.elements[i].type =='checkbox' && document.frmlistFacebook.elements[i].name=='checkbox[]')
		{

			if (document.frmlistFacebook.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistFacebook.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select template to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected  template?'))
		{
			show_processing();
			Handlewith_Ajax('services/facebook_tab_content.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
</script>
<form name="frmlistFacebook" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="facebook_tab" />
<input type="hidden" name="pass_start" value="<?=$start?>" />
<input type="hidden" name="pass_pg" value="<?=$pg?>" />
<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
<input  type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>"  />
  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> Facebook Templates</span></div></td>
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
		  if($numcount)
		  {
	?> 
    <tr>
		<td colspan="3" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?> </td>
	</tr>
	<?php
		  }
	?>
	<tr>
      <td height="48" class="sorttd" colspan="3" >
	  <div class="sorttd_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
        <tr>
          <td width="8%" height="30" align="left" valign="middle">Title </td>
          <td width="19%" height="30" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
          <td width="10%" height="30" align="left" valign="middle">Records Per Page </td>
          <td width="12%" height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
          <td width="5%" height="30" align="left" valign="middle">Sort By</td>
          <td width="38%" height="30" align="left" valign="middle"><?=$sort_option_txt?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp; <?=$sort_by_txt?></td>
          <td width="8%" height="30" align="right" valign="middle"><input name="button5" type="submit" class="red" id="button5" value="Go" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_FB_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
      </table>
      </div></td>
    </tr>
    
    <tr>
      <td colspan="3" class="listingarea">
	  <div class="listingarea_div">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
	  <tr>
      <td colspan="<?php echo round($colspan/2)?>" align="left" valign="middle" class="listeditd"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=facebook_tab&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>	  
	  </td>
	  <td align="right" valign="middle" class="listeditd" colspan="<?php echo round($colspan/2)?>" ><?php
			if ($numcount)
			{
		?>
			Change Status to
  <?php
					$chhide_array = array('0'=>'No','1'=>'Yes');
					echo generateselectbox('fb_is_active',$chhide_array,0);
				?>
 <input name="Change" type="button" class="red" id="Change" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_FB_ACTIVE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
  <?php
			}
		?></td>
    </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_group = "SELECT id,fb_subject,fb_content,fb_is_active,fb_preview_content  
	   FROM $table_name $where_conditions ORDER BY $sort_by  $sort_order LIMIT $start,$records_per_page ";
	   
	   $res = $db->query($sql_group);
	   $srno = 1; 
	   $action = '';
	   while($row = $db->fetch_array($res))
	   {
			$count_no++;
			$array_values = array();
			if($count_no %2 == 0)
				$class_val="listingtablestyleA";
			else
				$class_val="listingtablestyleB";
				
				if($row['fb_preview_content']!='')
				{
				 $action = "<a href=\"home.php?request=facebook_tab&fpurpose=preview&fbtab_id=".$row['id']."&mod=list\"  title=\"Click here To see Preview \" class=\"edittextlink\">Preview</a>";
				}
				else
				$action = "-";	   
	   ?>
        <tr >
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="7%"><input name="checkbox[]" value="<? echo $row['id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=facebook_tab&fpurpose=edit&checkbox[0]=<?php echo $row['id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>" title="<? echo $row['fb_subject']?>" class="edittextlink" onclick="show_processing()"><?php echo $row['fb_subject']; ?></a></td>
           <td align="center" valign="middle" class="<?=$class_val;?>">
		    		   <?php 
		    		   echo $action;
		    		   ?>		
		  </td>
		   <td align="center" valign="middle" class="<?=$class_val;?>">
		   <?php
		   echo ($row['fb_is_active']==1)?'Yes':'No';
		   
		   ?>		
		  </td>
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="5" >
				  	No Details  exists.				  </td>
			</tr>
		<?
		}
		?>
		<tr>
      <td class="listeditd" align="left" valign="middle" colspan="3"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=facebook_tab&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
	  </td>
	   <td class="listeditd" colspan="2"  align="right" valign="middle">
	  </td>
    </tr>	
      </table>
	  </div></td>
    </tr>
	<tr>
     
	   <td class="listing_bottom_paging" colspan="2"  align="right" valign="middle">
	  <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
    </tr>	
    </table>
</form>

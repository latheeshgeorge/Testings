<?php
/*#################################################################
# Script Name 	: list_faq.php
# Description 		: Page for listing FAQ
# Coded by 		: Sny
# Created on		: 01-Jan-2009
#################################################################*/
//Define constants for this page
$table_name='faq';
$page_type='Faq';
$help_msg = get_help_messages('LIST_FAQ_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmfaq,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmfaq,\'checkbox[]\')"/>','Slno.','Question','Order','Hidden'	);
$header_positions=array('left','left','left','left','left');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('search_faq_question');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'faq_question':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('faq_question' => 'Question', 'faq_sortorder' => 'Order');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_faq_question']) {
	$where_conditions .= "AND ( faq_question LIKE '%".add_slash($_REQUEST['search_faq_question'])."%')";
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
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=faq&records_per_page=$records_per_page&search_faq_question=".$_REQUEST['search_faq_question']."&start=$start";
?>
<script language="javascript">
function call_ajax_changestatus_old(search_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var curid				= 0;
	var type_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frmfaq.cbo_changehide.value;
	var qrystr				= 'search_faq_question='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmfaq.elements.length;i++)
	{
		if (document.frmfaq.elements[i].type =='checkbox' && document.frmfaq.elements[i].name=='checkbox[]')
		{
			if (document.frmfaq.elements[i].checked==true)
			{
				atleastone = 1;
				if (type_ids!='')
					type_ids += '~';
				 type_ids += document.frmfaq.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the FAQs to change the hide status');
	}
	else
	{
		if(confirm('Change Hide Status of Seleted Company Type(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/faq.php','fpurpose=change_hide&'+qrystr+'&type_ids='+type_ids);
		}	
	}	
}
function  call_ajax_changestatus(search_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 	= 0;
	var del_ids 	= '';
	var Idstr		= '';
	var Orderstr	= '';
	var ch_status			= document.frmfaq.cbo_changehide.value;
	var qrystr				= 'search_faq_question='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmfaq.elements.length;i++)
	{
		if (document.frmfaq.elements[i].type =='checkbox' && document.frmfaq.elements[i].name=='checkbox[]')
		{

			if (document.frmfaq.elements[i].checked==true)
			{
				atleastone = 1;
				if (Idstr!='')
					Idstr += '~';
				 Idstr += document.frmfaq.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the FAQs to change the hide status');
	}
	else
	{
		if(confirm('Change the status of selected FAQ(s)?'))
		{
			show_processing();
			Handlewith_Ajax('services/faq.php','fpurpose=change_hide&'+qrystr+'&type_ids='+Idstr);
		}	
	}	
}
function edit_selected()
{
	
	len=document.frmfaq.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmfaq.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one FAQ ');
	}
	else if(cnt>1 ){
		alert('Please select only one FAQ to edit');
	}
	else
	{
		show_processing();
		document.frmfaq.fpurpose.value='edit';
		document.frmfaq.submit();
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
	var qrystr				= 'search_faq_question='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmfaq.elements.length;i++)
	{
		if (document.frmfaq.elements[i].type =='checkbox' && document.frmfaq.elements[i].name=='checkbox[]')
		{

			if (document.frmfaq.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmfaq.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select FAQ to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected FAQ?'))
		{
			show_processing();
			Handlewith_Ajax('services/faq.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function call_ajax_saveorder(search_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 	= 0;
	var del_ids 	= '';
	var Idstr		= '';
	var Orderstr	= '';
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmfaq.elements.length;i++)
	{
		if (document.frmfaq.elements[i].type =='checkbox' && document.frmfaq.elements[i].name=='checkbox[]')
		{

			if (document.frmfaq.elements[i].checked==true)
			{
				atleastone = 1;
				if (Idstr!='')
					Idstr += '~';
				 Idstr += document.frmfaq.elements[i].value;
				 if (Orderstr!='')
					Orderstr += '~';
				obj = eval('document.frmfaq.ord_'+document.frmfaq.elements[i].value);
				 Orderstr += obj.value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the faq to save the order');
	}
	else
	{
		if(confirm('Save Sort Order Of FAQ?'))
		{
			show_processing();
			Handlewith_Ajax('services/faq.php','fpurpose=save_faq_order&'+qrystr+'&Idstr='+Idstr+'&OrderStr='+Orderstr);
		}	
	}	
}
function call_ajax_saveorder_old(search_name,sortby,sortorder,recs,start,pg)
{
	var qrystr= 'search_faq_question='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;
	var atleastone = 0;
	var IdArr=new Array();
	var OrderArr=new Array();
	var index;
	var val;
	var j=0;
	for(i=0;i<document.frmfaq.elements.length;i++)
	{
		if (document.frmfaq.elements[i].type =='text' && document.frmfaq.elements[i].name.substr(0,4)=='ord_')
		{
			if (document.frmfaq.elements[i].checked==true)
			{
				atleastone = 1;
				
				index_arr=document.frmfaq.elements[i].name.split('_');
				index = index_arr[1];
				val=document.frmfaq.elements[i].value;
				IdArr[j]=index;
				OrderArr[j]=val;
				j=j+1;
			}
		}
	}
	if (atleastone==0)
	{
		alert('Please select the faq to save the order');
		return false;
	}
	var Idstr=IdArr.join('~');
	var OrderStr=OrderArr.join('~');
	if(confirm('Save Sort Order Of FAQ?'))
	{
			show_processing();
			Handlewith_Ajax('services/faq.php','fpurpose=save_faq_order&'+qrystr+'&Idstr='+Idstr+'&OrderStr='+OrderStr);
	}

}
</script>
<form name="frmfaq" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="faq" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span>List FAQ</span></div></td>
    </tr>
	<tr>
	  <td colspan="3" align="left" valign="middle" class="helpmsgtd_main">
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
      <td class="sorttd" colspan="3" align="right" >
	   <?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?>
	  </td>
    </tr>
	<?php
	  }
	?>
    <tr>
      <td height="48" class="sorttd" colspan="3" >
	  <div class="sorttd_div" >
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
        <tr>
          <td width="10%" height="30" align="left" valign="middle">Question</td>
          <td width="19%" height="30" align="left" valign="middle"><input name="search_faq_question" type="text" class="textfeild" id="search_faq_question" value="<?=$_REQUEST['search_faq_question']?>"  /></td>
          <td width="10%" height="30" align="left" valign="middle">Records Per Page </td>
          <td width="15%" height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
          <td width="5%" height="30" align="left" valign="middle">Sort By</td>
          <td width="23%" height="30" align="left" valign="middle"><?=$sort_option_txt?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;  <?=$sort_by_txt?></td>
          <td width="18%" height="30" align="right" valign="middle">&nbsp;
            <input name="button5" type="submit" class="red" id="button5" value="Go" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_FAQ_SRCH_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			</tr>
      </table>
	  </div>
      </td>
    </tr>

     
    <tr>
      <td colspan="3" class="listingarea">
	  <div class="listingarea_div">
      <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	   <tr >
	   	<td class="listeditd" colspan="3">
		<a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=faq&fpurpose=add&records_per_page=<?=$records_per_page?>&search_faq_question=<?=$_REQUEST['search_faq_question']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_faq_question']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
		</td>
		<td colspan="2" align="right" class="listeditd">
		 <?
	  if($numcount)
	  {
	  ?>
        <input name="save_order" type="button" class="red" id="save_order" value="Save Order" onclick="call_ajax_saveorder('<?php echo $_REQUEST['search_faq_question']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
        &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_COMPANY_TYPES_SAVE_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;
		Change Hidden Status
          <select name="cbo_changehide" class="dropdown" id="cbo_changehide">
            <option value="1">Yes</option>
			<option value="0">No</option>
          </select>&nbsp;<input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['search_faq_question']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_FAQ_SAVE_CHANGE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
		<?
		}
		?>
		</td>
	   </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_faq = "SELECT faq_id, sites_site_id, faq_question, faq_answer, faq_sortorder, faq_hide 
	   								FROM 
										$table_name 
										$where_conditions 
									ORDER BY 
										$sort_by $sort_order 
									LIMIT 
										$start,$records_per_page ";
	   
	   $res = $db->query($sql_faq);
	   $srno = getStartOfPageno($records_per_page,$pg); 
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
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="6%"><input name="checkbox[]" value="<? echo $row['faq_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=faq&fpurpose=edit&checkbox[0]=<?php echo $row['faq_id']?>&search_faq_question=<?php echo $_REQUEST['search_faq_question']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>"  class="edittextlink" onclick="show_processing()"><? echo stripslashes($row['faq_question'])?></a></td>
		 <td align="left" valign="middle" class="<?=$class_val;?>"><input type="text" name="ord_<? echo $row['faq_id']?>" id="ord_<? echo $row['faq_id']?>" value="<? echo $row['faq_sortorder']?>" size="3" /></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['faq_hide'] == 1)?'Yes':'No'; ?></td>
         
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="5" >
				  	No FAQ exists.				  </td>
			</tr>
		<?
		}
		?>
		<tr >
	   	<td class="listeditd" colspan="3">
		<a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=faq&fpurpose=add&records_per_page=<?=$records_per_page?>&search_faq_question=<?=$_REQUEST['search_faq_question']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_faq_question']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
		</td>
		<td class="listeditd" colspan="2" align="right">
		
		</td>
		</tr>	
      </table>
	  </div></td>
    </tr>
	<tr >
	   
		<td class="listing_bottom_paging" colspan="2" align="right">
		<?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?>
		</td>
		</tr>
  </table>
</form>
<!-- Script for auto complete starts here -->
<script language="javascript">
var $pnc = jQuery.noConflict();
$pnc().ready(function() { 
	auto_search('search_faq_question','faqques'); 
});
</script>
<!-- Script for auto complete ends here -->
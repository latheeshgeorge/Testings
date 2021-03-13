<?php
	/*#################################################################
	# Script Name 	: list_card.php
	# Description 	: Page for listing Giftwrap Card
	# Coded by 		: SKR
	# Created on	: 21-July-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name='giftwrap_card';
$page_type='Cards';
$help_msg = get_help_messages('LIST_GIFTWRAP_CARD_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistCard,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistCard,\'checkbox[]\')"/>','Slno.','Card Name','Extra Price','Order','Hidden');
$header_positions=array('left','left','left','left','left','left','left');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('card_name');
foreach($search_fields as $v) {
	$query_string .= "$v=${$v}&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'card_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('card_name' => 'Card Name');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( card_name LIKE '%".add_slash($_REQUEST['search_name'])."%')";
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
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=giftwrap_cards&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start";
?>
<script language="javascript">
function call_ajax_changestatus(search_name,sortby,sortorder,recs,start,pg)
{
	var atleastone 			= 0;
	var curid				= 0;
	var card_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frmlistCard.cbo_changehide.value;
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistCard.elements.length;i++)
	{
		if (document.frmlistCard.elements[i].type =='checkbox' && document.frmlistCard.elements[i].name=='checkbox[]')
		{

			if (document.frmlistCard.elements[i].checked==true)
			{
				atleastone = 1;
				if (card_ids!='')
					card_ids += '~';
				 card_ids += document.frmlistCard.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the cards to change the hide status');
	}
	else
	{
		if(confirm('Change Hide Status of Seleted Card(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/giftwrap_card.php','fpurpose=change_hide&'+qrystr+'&card_ids='+card_ids);
		}	
	}	
}

function edit_selected()
{
	
	len=document.frmlistCard.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistCard.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				card_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one Card ');
	}
	else if(cnt>1 ){
		alert('Please select only one Card to edit');
	}
	else
	{
		show_processing();
		document.frmlistCard.fpurpose.value='edit';
		document.frmlistCard.submit();
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
	for(i=0;i<document.frmlistCard.elements.length;i++)
	{
		if (document.frmlistCard.elements[i].type =='checkbox' && document.frmlistCard.elements[i].name=='checkbox[]')
		{

			if (document.frmlistCard.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistCard.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select card to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Card?'))
		{
			show_processing();
			Handlewith_Ajax('services/giftwrap_card.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function call_ajax_saveorder(search_name,sortby,sortorder,recs,start,pg)
{
	var qrystr= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;
	
	var IdArr=new Array();
	var OrderArr=new Array();
	var index;
	var val;
	var j=0;
	for(i=0;i<document.frmlistCard.elements.length;i++)
	{
		if (document.frmlistCard.elements[i].type =='text' && document.frmlistCard.elements[i].name!='records_per_page' && document.frmlistCard.elements[i].name!='search_name')
		{
			
			index=document.frmlistCard.elements[i].name;
			val=document.frmlistCard.elements[i].value;
			IdArr[j]=index;
			OrderArr[j]=val;
			j=j+1;
		}
	}
	
	
	var Idstr=IdArr.join('~');
	var OrderStr=OrderArr.join('~');
	
	if(confirm('Save Sort Order Of Cards?'))
		{
				show_processing();
				Handlewith_Ajax('services/giftwrap_card.php','fpurpose=save_card_order&'+qrystr+'&Idstr='+Idstr+'&OrderStr='+OrderStr);
		}

}
</script>
<form name="frmlistCard" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="giftwrap_cards" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span>List Giftwrap Cards</span></div></td>
    </tr>
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
		<td colspan="3" align="right" valign="middle" class="sorttd">
		<?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td>
	</tr> 
	<?php
		}
	?>
    <tr>
      <td height="48" class="sorttd" colspan="3" >
	  <div class="editarea_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
        <tr>
          <td width="11%" height="30" align="left" valign="middle">Card Name </td>
          <td width="20%" height="30" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
          <td width="10%" height="30" align="left" valign="middle">Records Per Page </td>
          <td width="10%" height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
          <td width="5%" height="30" align="left" valign="middle">Sort By</td>
          <td width="26%" height="30" align="left" valign="middle"><?=$sort_option_txt?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp; <?=$sort_by_txt?></td>
          <td width="18%" height="30" align="right" valign="middle"><input name="button5" type="submit" class="red" id="button5" value="Go" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_GIFTWRAP_CARD_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
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
		  <td align="left" valign="middle" class="listeditd"  colspan="4">
		  <a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=giftwrap_cards&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
	  </td>
		  <td align="right" valign="middle" class="listeditd"  colspan="2">
		  <?
	  if($numcount)
	  {
	  ?>
        <input name="save_order" type="button" class="red" id="save_order" value="Save Order" onclick="call_ajax_saveorder('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_GIFTWRAP_CARD_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;
		Change Status
          <select name="cbo_changehide" class="dropdown" id="cbo_changehide">
            <option value="0">Yes</option>
			<option value="1">No</option>
          </select>&nbsp;<input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_GIFTWRAP_CARD_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
		<?
		}
		?>
		  </td>
	  </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_user = "SELECT card_id,card_name,card_extraprice,card_active,card_order FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
	   $res = $db->query($sql_user);
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
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="5%"><input name="checkbox[]" value="<? echo $row['card_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="6%"><?php echo $srno++?></td>
          <td width="48%" align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=giftwrap_cards&fpurpose=edit&checkbox[0]=<?php echo $row['card_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>" title="<? echo $row['card_name']?>" class="edittextlink" onclick="show_processing()"><? echo $row['card_name']?></a></td>
          
		  <td width="13%" align="left" valign="middle" class="<?=$class_val;?>"><?php echo  display_curr_symbol()?><?php echo $row['card_extraprice']; ?></td>
		   <td width="14%" align="left" valign="middle" class="<?=$class_val;?>"><input type="text" name="<? echo $row['card_id']?>" value="<?php echo $row['card_order']; ?>" size="3" /></td>
          <td width="14%" align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['card_active'] == 0)?'Yes':'No'; ?></td>
         
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext"  colspan="6">
				  	No Card exists.				  </td>
		  </tr>
		<?
		}
		?>	
	  <tr>
		  <td align="left" valign="middle" class="listeditd"  colspan="4">
		  <a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&request=giftwrap_cards&fpurpose=add&records_per_page=<?=$records_per_page?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$start?>&pg=<?=$pg?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>
		  </td>
		  <td align="right" valign="middle" class="listeditd"  colspan="2">
		 
		  </td>
	  </tr>
      </table>
	  </div></td>
    </tr>
	<tr>
		  
		  <td align="right" valign="middle" class="listing_bottom_paging"  colspan="2">
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

<?php
	/*#################################################################
	# Script Name 	: list_forms.php
	# Description 	: Page for listing Dynamic Forms
	# Coded by 		: SKR
	# Created on	: 20-Aug-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name='elements';
$page_type='Forms';
$help_msg = get_help_messages('EDIT_CHECKOUT_FORM_ACTION');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistForm,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistForm,\'checkbox[]\')"/>','Slno.','Label','Type','Align','Valign','Sort','Mandatory<a href="#" onmouseover ="ddrivetip(\''.get_help_messages('LIST_CHECKOUTFORM_MANDATORY_HEAD').'\')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>');
$header_positions=array('left','left','left','left','left','left','left','left','left','left');
$colspan = count($table_headers);


//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['section_id'])
{
	$where_conditions.=" AND element_sections_section_id=".$_REQUEST['section_id'];
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
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=customform&fpurpose=manage_form&section_id=".$_REQUEST['section_id']."&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start";
?>
<script language="javascript">
function open_elements(type)
{
	if(type == 'edit')
	{
		if(document.frmlistForm.delete_id.value == "")
		{
			alert("No row Selected");
			return false;
		}
		window.open('services/add_elements.php?from_element_page=yes&form_type=<?=$_REQUEST['form_type']?>&section_id=<?=$_REQUEST['section_id']?>&emt_id='+document.frmlistForm.delete_id.value,'win','width=500,height=250,scrollbars=yes');
	}
	else
	{
		window.open('./services/add_elements.php?from_element_page=yes&form_type=<?=$_REQUEST['form_type']?>&section_id=<?=$_REQUEST['section_id']?>&type='+type,'win','width=500,height=250,scrollbars=yes');
	}
	return false;
}


function edit_selected()
{
	
	len=document.frmlistForm.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistForm.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				emt_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one element ');
	}
	else if(cnt>1 ){
		alert('Please select only one element to edit');
	}
	else
	{
		window.open('services/add_elements.php?from_element_page=yes&form_type=<?=$_REQUEST['form_type']?>&section_id=<?=$_REQUEST['section_id']?>&emt_id='+emt_id,'win','width=500,height=250,scrollbars=yes');
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
function call_ajax_delete(form_type,section_id,start,pg,search_name,records_per_page)
{
	var atleastone 	= 0;
	var del_ids 	= '';
	var qrystr				= 'form_type='+form_type+'&section_id='+section_id+'&start='+start+'&pg='+pg+'&search_name='+search_name+'&records_per_page='+records_per_page;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistForm.elements.length;i++)
	{
		if (document.frmlistForm.elements[i].type =='checkbox' && document.frmlistForm.elements[i].name=='checkbox[]')
		{

			if (document.frmlistForm.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistForm.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select element to delete');
	}
	else
	{
	//alert('form_type='+form_type+'&section_id='+section_id+'&start='+start+'&pg='+pg+'&search_name='+search_name+'&records_per_page='+records_per_page);
		if(confirm('Are you sure you want to delete selected Element?'))
		{
			show_processing();
			Handlewith_Ajax('services/custom_form.php','fpurpose=delete_element&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function call_ajax_save(form_type,section_id,start,pg,search_name,records_per_page,sort_by,sort_order)
{
	var ch_labels 			= '';
	var ch_order			= '';
	var ch_ids				='';
	var ch_mandatory		='';
	
	var j=0;
	var i;
	var name_label;
	var qrystr				= 'form_type='+form_type+'&section_id='+section_id+'&start='+start+'&pg='+pg+'&search_name='+search_name+'&records_per_page='+records_per_page+'&sort_by='+sort_by+'&sort_order='+sort_order;
	
	for(i=0;i<document.frmlistForm.elements.length;i++)
	{
		
		if (document.frmlistForm.elements[i].type =='checkbox' && document.frmlistForm.elements[i].name=='checkbox[]')
		{

			
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmlistForm.elements[i].value;
				
		}
		if (document.frmlistForm.elements[i].type =='text')
		{
			
			name_label=document.frmlistForm.elements[i].name;
			if(name_label.indexOf('sort_')==-1)
			{
				if (ch_labels!='')
					ch_labels += '~';
				ch_labels+=document.frmlistForm.elements[i].value;
				
			}	
			if(name_label.indexOf('label_')==-1)
			{
				if (ch_order!='')
					ch_order += '~';
				ch_order+=document.frmlistForm.elements[i].value;
				
			}	
			
		}
		if (document.frmlistForm.elements[i].type =='checkbox' && document.frmlistForm.elements[i].name=='chkmandatory[]')
		{

			
				if(document.frmlistForm.elements[i].checked==true)
				{
					if (ch_mandatory!='')
						ch_mandatory += '~';
					 ch_mandatory += document.frmlistForm.elements[i].value;
				}				
		}
		
	}	
	
	if(confirm('Save Details Of Form Elements?'))
	{
				show_processing();
				Handlewith_Ajax('services/custom_form.php','fpurpose=save_details&'+qrystr+'&ch_ids='+ch_ids+'&ch_labels='+ch_labels+'&ch_order='+ch_order+'&ch_mandatory='+ch_mandatory);
	}

}
</script>
<form name="frmlistForm" action="home.php" method="post" >	

<input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />

  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="javascript:void(0);"><? echo ucwords($_REQUEST['form_type'])?> Form</a><a href="home.php?request=customform&form_type=<?=$_REQUEST['form_type']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Sections</a><span> List Forms</span></div></td>
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
   <tr>
		<td colspan="3" align="center" valign="middle">
			<div class="sorttd_div">
			<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
      <td width="466" class="listeditd"><?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['form_type']?>','<? echo $_REQUEST['section_id']?>','<? echo $_REQUEST['start']?>','<? echo $_REQUEST['pg']?>','<? echo $_REQUEST['search_name']  ?>','<? echo $_REQUEST['records_per_page'] ?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>	  </td>
	   <td class="listeditd" width="247" align="right">
	   <?php
	   	if($numcount)
		{
	   ?>
	   	<input name="save_order" type="button" class="red" id="save_order" value="Save " onclick="call_ajax_save('<? echo $_REQUEST['form_type']?>','<? echo $_REQUEST['section_id']?>','<? echo $_REQUEST['start']?>','<? echo $_REQUEST['pg']?>','<? echo $_REQUEST['search_name']  ?>','<? echo $_REQUEST['records_per_page'] ?>','<? echo $_REQUEST['sort_by'] ?>','<? echo $_REQUEST['sort_order'] ?>')" /><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CUSTOM_FORM_SAVE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;
		 	<input name="save_order" type="button" class="red" id="save_order" value="Preview " onclick="window.open('preview_dynamic_form.php?section_id=<?=$_REQUEST['section_id']?>&section_type=<?=$_REQUEST['form_type']?>','preview_regform','width=600,height=auto,scrollbars=yes');" /><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CHECKOUT_FORM_PREVIEW')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
	  <?php
		}
		?>	  </td>
	   <td width="5" align="right" class="listeditd">&nbsp;   	   </td>
    </tr>
     
    <tr>
      <td colspan="3" class="listingarea">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_user = "SELECT element_id,element_label,element_type,element_align,element_valign,sort_no,mandatory FROM $table_name $where_conditions ORDER BY sort_no  ";
	   
	   $res = $db->query($sql_user);
	   $srno = 1;
	    $cnt_tmp=0;
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
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="5%" ><input name="checkbox[]" value="<? echo $row['element_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><input type="text" name="label_<? echo $cnt_tmp?>" value="<? echo $row['element_label']?>" id="label_<? echo $cnt_tmp?>" size="25"  /></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><?=$row['element_type']?></td>
		   <td align="left" valign="middle" class="<?=$class_val;?>"><?=$row['element_align']?></td>
		   <td align="left" valign="middle" class="<?=$class_val;?>"><?=$row['element_valign']?></td>
		   <td align="left" valign="middle" class="<?=$class_val;?>"><input type="text" name="sort_<? echo $cnt_tmp?>" value="<? echo $row['sort_no']?>" id="sort_<? echo $cnt_tmp?>" size="3"  /></td>
          <td colspan="2" align="left" valign="middle" class="<?=$class_val;?>"><input type="checkbox" name="chkmandatory[]" value="<? echo $row['element_id']?>" <? if($row['mandatory']=='Y') echo "checked";?> />&nbsp;<input type="button" name="error_msg" value="ErrorMsg" onclick="window.open('./services/add_error_msg.php?emt_id=<?=$row['element_id']?>','win','width=300,height=150'); return false;" class="red"></td>
        </tr>
      <?
	    $cnt_tmp++;
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td colspan="<?=$colspan?>" align="center" valign="middle" class="norecordredtext" >
				  	No Form exists.				  </td>
			</tr>
		<?
		}
		?>	
      </table></td>
    </tr>
	
  <tr>
      <td colspan="2" class="listeditd"> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['form_type']?>','<? echo $_REQUEST['section_id']?>','<? echo $_REQUEST['start']?>','<? echo $_REQUEST['pg']?>','<? echo $_REQUEST['search_name']  ?>','<? echo $_REQUEST['records_per_page'] ?>')" class="deletelist">Delete</a>
	  <?
	  }
	  ?>	</td>
	   <td class="listeditd" align="right">&nbsp;   	   </td>
    </tr>
	 <tr >
	 <td  colspan="2" align="center" >&nbsp;</td>
	 </tr>
	 <tr>
	 <td  colspan="2" align="left"><input name="Add_textbox" type="button" onclick="open_elements('text')" class="red" value="Add Textbox" />&nbsp;<input name="Add_textarea" type="button" onclick="open_elements('textarea')" class="red" value="Add TextArea" />&nbsp;<input name="Add_radio" type="button" class="red" value="Add Radio Button" onclick="open_elements('radio')" />&nbsp;<input name="Add_checkbox" type="button" class="red" onclick="open_elements('checkbox')" value="Add CheckBox " />&nbsp;<input name="Add_combobox" type="button" class="red" value="Add ComboBox" onclick="open_elements('select')" />&nbsp;
	 <input name="Add_Date" type="button" class="red" value="Add Date" onclick="open_elements('date')" /></td>
	 </tr>
	 </table>
	 </div>
	 </td>
	 </tr>
    </table>
</form>

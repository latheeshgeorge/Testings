<? 
	/*#################################################################
	# Script Name 	: import_export.php
	# Description 	: Import or Export main page
	# Coded by 		: Sny
	# Created on	: 28-Nov-2007
	# Modified by	: Sny
	# Modified On	: 03-Dec-2007
	# Modified by	: LH
	# Modified On	: 03-Feb-2008
	#################################################################*/
	//Define constants for this page
	$page_type 	= 'Import / Export';
	$help_msg 	= get_help_messages('LIST_IMPORT_EXPORT_MESS1');
	/*if($ecom_siteid==83 || $ecom_siteid==104)// and $_SESSION['console_id']!=8)
	{
		echo "<center><br><br><span class='redtext'><strong>You are not authorised to view this page.</strong></span></center>";
		exit;
	}*/
	if($_REQUEST['alert']==1)
	{
	 $alert ="Error!!!!The file extension is not csv.";
	}
	else if($_REQUEST['alert']==2)
	{
	$alert = "Error!!!! file mismatch.Please click the 'Generate Template' button to get the file in correct format.";
	}
	else if($_REQUEST['alert']==3)
	{
	$alert ="Customers imported successfully.";
	}
	else if($_REQUEST['alert']==4)
	{
	 $alert ="Shops imported successfully.";
	}
	else if($_REQUEST['alert']==5)
	{
	 $alert ="Category imported successfully.";
	}
	else if($_REQUEST['alert']==6)
	{
	  $alert ="Products imported successfully.";
	}
	else if($_REQUEST['alert']==7)
	{
	  $alert ="Error!!!!The selected file is not CSV(Excel) file.";
	}
	else if($_REQUEST['alert']==8)
	{
	  $alert ="The selected file cannot open";
	}
	//For selected order exporting
	if($_REQUEST['ids'])
	{ 
		$ids =$_REQUEST['ids'];
	}
	
	
?>
<script type="text/javascript">
function ajax_return_contents() 
{
	var ret_val='';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val 			= req.responseText;
			targetdiv 			= document.getElementById('retdiv_id').value;
			targetobj 			= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val;  /*Setting the output to required div */
		}
		else
		{
			 show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}

function call_ajax_showlistall(mod,what,type)
{  
	var atleastone 		= 0;
	var cat_orders		= '';
	var fpurpose		= '';
	var retdivid		= '';
	var moredivid		= '';
	var id_arr = new Array();
	switch(mod)
	{
		case 'main_select': // Case of main select box change
			var main_select		= document.getElementById('main_select').value;
			retdivid   	= 'import_export_what_div';
			fpurpose	= 'show_importorexport_options';
			var qrystr	= '&main_select='+ main_select;
			document.getElementById('import_export_details_div').innerHTML = '';
		break;
		case 'subexport_select': // Case of sub_select box change
			var main_select		= document.getElementById('main_select').value;
			var export_what		= document.getElementById('export_what').value;
			retdivid   			= 'import_export_details_div';
			fpurpose			= 'show_export_fields';
			var qrystr			= '&main_select='+ main_select+'&export_what='+export_what;
		break;
		case 'subimport_select': // Case of sub_select box change
			var main_select		= document.getElementById('main_select').value;
			var import_what		= document.getElementById('import_what').value;
			var qrystr			= '&main_select='+ main_select+'&import_what='+import_what;
			retdivid   			= 'import_export_details_div';
			fpurpose			= 'show_import_fields';
		break;
	}
	if(what){
	if(type=='export')
	{     	fpurpose1 = 'show_export_fields';
			retdivid   	= 'import_export_details_div';
			var id_arr = '<? echo $ids?>';
			qrystr = qrystr+'&cur_what='+what+'&fpurpose1='+fpurpose1+'&ids='+id_arr;
	}
	else if(type=='import')
	{
	fpurpose1 = 'show_import_fields';
	retdivid   	= 'import_export_details_div';
	qrystr = qrystr+'&cur_what='+what+'&fpurpose1='+fpurpose1;
	}
	
	}
	/*alert(fpurpose1);*/
	document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */	
	retobj 											= eval("document.getElementById('"+retdivid+"')");
	/*retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';	*/	
	retobj.innerHTML 								= '<center>loading......</center>';													
	/* Calling the ajax function */
	Handlewith_Ajax('services/import_export.php','fpurpose='+fpurpose+qrystr);
		
}
function handle_select_allfields(mod)
{
	switch(mod)
	{ 
	  case 'export_fields':
	  obj 	= document.getElementById('export_fields[]');
	  break;
	  case 'import_fields':
	  obj 	= document.getElementById('import_fields[]');
	  break;
	}
	//obj 	= document.getElementById('export_fields[]');
	var sel = '';
	if (document.getElementById('select_allfields').checked)
		sel = true;
	else
		sel = false;
	for (i=0;i<obj.options.length;i++)
	{
		obj.options[i].selected = sel;
	}
}
function handle_select_allcategory(mod)
{
	switch(mod)
	{ 
	  case 'select_category':
	  obj 	= document.getElementById('sel_category_id[]');
	  break;
	}
	var sel = '';
	if (document.getElementById('select_allfields_category').checked)
		sel = true;
	else
		sel = false;
	for (i=0;i<obj.options.length;i++)
	{
		obj.options[i].selected = sel;
	}
}
function check_fields(mod,mod_sub)
{
	var atleastone = false;
	if(mod=='export_Submit'){
		obj 	= document.getElementById('export_fields[]');
		
		var sel = '';
		for (i=0;i<obj.options.length;i++)
		{
			if(document.getElementById('select_allfields').checked)
			{
				obj.options[i].selected = true;
				atleastone = true;
			}
			else
			{
				if(obj.options[i].selected)
				atleastone = true;
			}
		}
		if (atleastone == false)
		{
			alert('Please select atleast One field');
			return false;
		}
			if(mod_sub=='order')
			{
				if(!obj.options[0].selected)
				{
				alert('Please select Order Id');
					return false;
				}	
			}	
	}
	else if(mod=='import_Submit'){
	   switch(mod_sub)
	   {
	     /*case 'cat':
				 obj 	= document.getElementById('sel_catgroup_id[]');
				 var sel = '';
					for (i=0;i<obj.options.length;i++)
					{
						
							if(obj.options[i].selected)
								atleastone = true;
					}
					if (atleastone == false)
					{
						alert('Please select Atleast one category group');
						return false;
					}
						
		break;
		case 'shop':
		           obj 	= document.getElementById('sel_shopgroup_id[]');
				 var sel = '';
					for (i=0;i<obj.options.length;i++)
					{
						
							if(obj.options[i].selected)
								atleastone = true;
					}
					if (atleastone == false)
					{
						alert('Please select Atleast one shop group');
						return false;
					}
		break;*/
	   }
	  if(document.frm_importexport.file_import.value=='')
	  {
	    alert('please select the file to import.');
		return false;
		frm_importexport.file_import.focus()
		
	  }	
		
	}
	
	else
	{
		if (document.frm_importexport.export_output_format.value == 'html')
			document.frm_importexport.target = '_blank';
		else	
			document.frm_importexport.target = '';
		return true;
	}	
}
</script>
<form action="do_import_export.php" method="post" name="frm_importexport"  enctype="multipart/form-data">
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">
<tr>
<td valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
	<td align="left" class="treemenutd"><div class="treemenutd_div"><span><?php echo $page_type?></span></div></td>
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
          	<td colspan="2" align="center" valign="middle" class="errormsg" ><?=stripslashes($alert)?></td>
        </tr>
		<?
		}
		?>
	<tr>
	<td></td>
	</tr>
	</table>
	<div class="editarea_div">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="316" align="left" valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
        
          <tr>
            <td  align="left" class="td_export" valign="top">
			<div class="td_export_diva">
			<div>Select Option </div>
			<div><?PHP if(trim($ids) && $_REQUEST['export_what']=='giftvoucher') {
			echo "<input type='hidden' name='main_select' id='main_select' value='export'/>";
			echo "  <font size='2'> :  Export </font> "; 
			echo "<script language=\"javascript\"> call_ajax_showlistall('main_select','') </script>";
			 } else {
			?>
              <select name="main_select" id="main_select" onchange="call_ajax_showlistall('main_select','')">
                <option>-- Select --</option>
                <option value="import" <?php echo ($_REQUEST['import_what'])?'selected="selected"':''?>>Import</option>
                <option value="export" <?php echo ($_REQUEST['export_what'])?'selected="selected"':''?>>Export</option>
              </select>   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_IMPORT_EXPORT_OPTION')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			<?PHP } ?>
			</div>
            </div>
			</td>
           
            <td align="left" class="td_export_a" valign="top" ><div id="import_export_what_div" ></div></td>
            <td align="left" class="td_export_b" valign="top"><div id="import_export_details_div"></div></td>
          </tr>
        </table></td>
        </tr>
    </table>
	</div>
    </td>
</tr>
</table>
<input type="hidden" name="retdiv_id" id="retdiv_id" value="maincontent" />
</form>
<?php 
if($_REQUEST['import_what'])
{
?>
	<script>
		call_ajax_showlistall('main_select','<?php echo $_REQUEST['import_what']?>','import');
	</script>
<?php
}
if($_REQUEST['export_what'])
{
?>
	<script>
		call_ajax_showlistall('main_select','<?php echo $_REQUEST['export_what']?>','export');
	</script>
<?php
}
?>

<?php
	/*#################################################################
	# Script Name 	: edit_survey.php
	# Description 	: Page for editing Surevys
	# Coded by 		: ANU
	# Created on	: 6-Aug-2007
	# Modified by	: ANU
	# Modified On	: 6-Aug-2007
	#################################################################*/
#Define constants for this page
$page_type = 'Survey';
$help_msg = get_help_messages('EDIT_SURVAY_MESS1');
$survey_id=($_REQUEST['survey_id']?$_REQUEST['survey_id']:$_REQUEST['checkbox'][0]);
$sql="SELECT survey_id,survey_title,survey_question,survey_hide,survey_displayresults,survey_showinall,survey_status,survay_activateperiodchange,survay_displaystartdate,survay_displayenddate  FROM survey WHERE sites_site_id=$ecom_siteid AND survey_id=".$survey_id;
$res=$db->query($sql);
$row=$db->fetch_array($res);
// Find the feature_id for mod_survey module from features table
	$sql_feat = "SELECT feature_id FROM features WHERE feature_modulename='mod_survey'";
	$ret_feat = $db->query($sql_feat);
	if ($db->num_rows($ret_feat))
	{
		$row_feat 	= $db->fetch_array($ret_feat);
		$feat_id	= $row_feat['feature_id'];
	}
// Find the display settings details for this survey
	$sql_disp = "SELECT a.display_id,a.display_position,a.layout_code,a.themes_layouts_layout_id,a.display_order,b.layout_name FROM 
					display_settings a,themes_layouts b WHERE a.sites_site_id = $ecom_siteid AND display_component_id=$survey_id AND 
					features_feature_id=$feat_id AND a.themes_layouts_layout_id=b.layout_id";
	$ret_disp = $db->query($sql_disp);
?>	
<script language="javascript" type="text/javascript">
function activeperiod(check,bid){
 if(document.frmEditSurvey.survay_activateperiodchange.checked == true){
		document.getElementById(bid).style.display = '';
		}
		else{
		document.getElementById(bid).style.display = 'none';
		document.frmEditSurvey.survay_activateperiodchange.checked = false;
		}
		
		
}
function ajax_return_contents() 
{
	var ret_val = '';
	var disp 	= 'no';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val 	= req.responseText;
			targetdiv 	= document.getElementById('retdiv_id').value;
			norecdiv 	= document.getElementById('retdiv_more').value;
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			/* Decide the display of action buttons*/
			switch(targetdiv)
			{
				
				case 'category_div':
					if(document.getElementById('category_norec'))
					{
						if(document.getElementById('category_norec').value==1)
						{
							disp = 'none';
						}	
						else
							disp = '';
					}
					else
						disp = '';	
				break;
				case 'products_div':
					if(document.getElementById('products_norec'))
					{
						if(document.getElementById('products_norec').value==1)
						{
							disp = 'none';
						}	
						else
							disp = '';
					}
					else
						disp = '';	
				break;
				case 'assign_pages_div':
					if(document.getElementById('assign_pages_norec'))
					{
						if(document.getElementById('assign_pages_norec').value==1)
						{
							disp = 'none';
						}	
						else
							disp = '';
					}
					else
						disp = '';	
				break;
				
			};
			
			if (disp!='no')
			{
				norecobj 	= eval("document.getElementById('"+norecdiv+"')");
				if (norecobj)
				norecobj.style.display = disp;
			}	
			hide_processing();
		}
		else
		{
			 show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}
function handle_expansionall(imgobj,mod)
{
	var src 			= imgobj.src;
	var retindx 		= src.search('plus.gif');
	switch(mod)
	{
	
		case 'category': // Case of Catgeory
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('category_tr'))
					document.getElementById('category_tr').style.display = '';
				if(document.getElementById('categoryunassign_div'))
					document.getElementById('categoryunassign_div').style.display = '';	
				call_ajax_showlistall('category')
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('category_tr'))
					document.getElementById('category_tr').style.display = 'none';
				if(document.getElementById('categoryunassign_div'))
					document.getElementById('categoryunassign_div').style.display = 'none';
			}	
		break;
		case 'products': // Case of product tabs
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('products_tr'))
					document.getElementById('products_tr').style.display = '';
				if(document.getElementById('productsunassign_div'))
					document.getElementById('productsunassign_div').style.display = '';	
				call_ajax_showlistall('products');
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('products_tr'))
					document.getElementById('products_tr').style.display = 'none';
				if(document.getElementById('productsunassign_div'))
					document.getElementById('productsunassign_div').style.display = 'none';
			}	
		break;
		
		case 'assign_pages': // Case of Static Pages
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('assign_pages_tr'))
					document.getElementById('assign_pages_tr').style.display = '';
				if(document.getElementById('assign_pagesunassign_div'))
					document.getElementById('assign_pagesunassign_div').style.display = '';	
				call_ajax_showlistall('assign_pages')
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('assign_pages_tr'))
					document.getElementById('assign_pages_tr').style.display = 'none';
				if(document.getElementById('assign_pagesunassign_div'))
					document.getElementById('assign_pagesunassign_div').style.display = 'none';
			}	
		break;
		
		case 'survey_results': // TO disply Survey Results
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('results_tr'))
					document.getElementById('results_tr').style.display = '';
				if(document.getElementById('resultsunassign_div'))
					document.getElementById('resultsunassign_div').style.display = '';	
				call_ajax_showlistall('survey_results')
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('results_tr'))
					document.getElementById('results_tr').style.display = 'none';
				if(document.getElementById('resultsunassign_div'))
					document.getElementById('resultsunassign_div').style.display = 'none';
			}	
		break;
	};
}
function call_ajax_showlistall(mod)
{  
	var atleastone 										= 0;
	var survey_id										= '<?php echo $survey_id?>';
	var cat_orders										= '';
	var fpurpose										= '';
	var retdivid										= '';
	var moredivid										= '';
	switch(mod)
	{
	
		case 'category': // Case of Category
			retdivid   	= 'category_div';
			fpurpose	= 'list_categoriesInSurvey_ajax';
			moredivid	= 'categoryunassign_div';
		break;
		case 'products': // Case of product assigned to the Survey
			retdivid   	= 'products_div';
			fpurpose	= 'list_products_ajax';
			moredivid	= 'productsunassign_div';
		break;
		case 'assign_pages': // Case of product link
			retdivid   	= 'assign_pages_div';
			fpurpose	= 'list_assign_pages_ajax';
			moredivid	= 'assign_pagesunassign_div';
		break;
		case 'survey_results': // Case of Survey Results
			retdivid   	= 'results_div';
			fpurpose	= 'view_survey_results';
			moredivid	= 'resultsunassign_div';
		break;
	}
	document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */	
	document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
	retobj 										= eval("document.getElementById('"+retdivid+"')");
	retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	var qrystr									= '';
	/* Calling the ajax function */
	Handlewith_Ajax('services/survey.php','fpurpose='+fpurpose+'&cur_surveyid='+survey_id);
}
function call_ajax_changestatuspagesall(mod,checkboxname)
{ 
	var atleastone 			= 0;
	var survey_id			= '<?php echo $survey_id?>';
	var ch_ids 				= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';

	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditSurvey.elements.length;i++)
	{
	
	if (document.frmEditSurvey.elements[i].type =='checkbox' && document.frmEditSurvey.elements[i].name==checkboxname)
		{

			if (document.frmEditSurvey.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmEditSurvey.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		
		case 'category': // Case of product catgeories to be displayed
			atleastmsg 	= 'Please select the categories to change the status';
			confirmmsg 	= 'Are you sure you want to Change the Status of selected categories?';
			retdivid   	= 'category_div';
			moredivid	= 'categoryunassign_div';
			fpurpose	= 'changestat_category_ajax';
			var chstat	= document.getElementById('categories_chstatus').value;
		break;
		case 'product': // Case of product tabs
			atleastmsg 	= 'Please select the product(s) to change the status';
			confirmmsg 	= 'Are you sure you want to Change the Status of selected Product(s) ?';
			retdivid   	= 'products_div';
			moredivid	= 'productsunassign_div';
			fpurpose	= 'changestat_product_ajax';
			var chstat	= document.getElementById('product_chstatus').value;
		break;
		case 'assign_pages': // Case of linked products
			atleastmsg 	= 'Please select the Static Page(s) Assigned to Survey to change the status';
			confirmmsg 	= 'Are you sure you want to Change the Status of selected Static Page(s)?';
			retdivid   	= 'assign_pages_div';
			moredivid	= 'assign_pagesunassign_div';
			fpurpose	= 'changestat_assign_pages_ajax';
			var chstat	= document.getElementById('assign_pages_chstatus').value;
		break;
	}
	if (atleastone==0)
	{
		alert(atleastmsg);
	}
	else
	{
		if(confirm(confirmmsg))
		{	
			document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
			document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
			retobj 										= eval("document.getElementById('"+retdivid+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
		Handlewith_Ajax('services/survey.php','fpurpose='+fpurpose+'&chstat='+chstat+'&cur_survey_id='+survey_id+'&ch_ids='+ch_ids);
		}	
	}	
}	


function call_ajax_deleteall(mod,checkboxname)
{
	var atleastone 			= 0;
	var survey_id			= '<?php echo $survey_id?>';
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditSurvey.elements.length;i++)
	{
		if (document.frmEditSurvey.elements[i].type =='checkbox' && document.frmEditSurvey.elements[i].name==checkboxname)
		{

			if (document.frmEditSurvey.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmEditSurvey.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		
		case 'category': // Case of product messages
			atleastmsg 	= 'Please select the Product Categories to be deleted';
			confirmmsg 	= 'Are you sure you want to delete the selected Product Categories?'
			retdivid   	= 'category_div';
			moredivid	= 'categoryunassign_div';
			fpurpose	= 'delete_category_ajax';
		break;
		case 'product': // Case of product tabs
			atleastmsg 	= 'Please select the product(s) to be deleted';
			confirmmsg 	= 'Are you sure you want to delete the selected Product(s)?'
			retdivid   	= 'products_div';
			moredivid	= 'productsunassign_div';
			fpurpose	= 'delete_product_ajax';
		break;
		case 'assign_pages': // Case of linked products
			atleastmsg 	= 'Please select the Static Page(s) to be Unassigned';
			confirmmsg 	= 'Are you sure you want to Unassign the selected Static Page(s)?'
			retdivid   	= 'assign_pages_div';
			moredivid	= 'assign_pagesunassign_div';
			fpurpose	= 'delete_assign_pages';
		break;
	}
	if (atleastone==0)
	{
		alert(atleastmsg);
	}
	else
	{
		if(confirm(confirmmsg))
		{ 
			document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
			document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
			retobj 										= eval("document.getElementById('"+retdivid+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			
			Handlewith_Ajax('services/survey.php','fpurpose='+fpurpose+'&cur_survey_id='+survey_id+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function valform(frm)
{
	fieldRequired = Array('survey_title','survey_question','display_id[]');
	fieldDescription = Array('Survey Title','Survey question','Display Location');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		if(document.frmEditSurvey.survay_activateperiodchange.checked  ==true){
			val_dates = compareDates(document.frmEditSurvey.survay_displaystartdate,"Start Date\n Correct Format:dd-mm-yyyy ",document.frmEditSurvey.survay_displayenddate,"End Date\n Correct Format:dd-mm-yyyy");
			if(!val_dates){
				return false;
			}
		}
		if(document.frmEditSurvey.survey_status.value == 3 || document.frmEditSurvey.survey_status.value == 4 ){
			if(confirm('This Survey Status will end the survey - are you sure?')){
				show_processing();
				return true;
				}else{
					return false;
				}
		}else{
			show_processing();//show_processing();
			return true;
		}	
	}else{
	return false;
	}		
}			
		


</script>
<form name='frmEditSurvey' action='home.php?request=survey' enctype="multipart/form-data" method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><a href="home.php?request=survey&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&search_name=<?=$_REQUEST['pass_search_name']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&status=<?=$status?>">List Survey</a> &gt;&gt; Edit Survey</td>
        </tr>
        <tr>
          <td colspan="4" align="left" valign="middle" class="helpmsgtd" ><?=$help_msg ?></td>
        </tr>
		<?php 
		if($alert)
		{			
		?>
        <tr>
          <td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
        <tr><td width="51%" class="tdcolorgray" valign="top">
		<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
          <td width="29%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="71%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
         </tr>
		  <tr>
          <td width="29%" align="left" valign="middle" class="tdcolorgray" >Survey Title  <span class="redtext">*</span> </td>
          <td width="71%" align="left" valign="middle" class="tdcolorgray"><input name="survey_title" type="text" id="survey_title" value="<?=$row['survey_title']?>" /></td>
         </tr>
		 <tr>
           <td align="left" valign="middle" class="tdcolorgray" >Survey Question <span class="redtext">*</span> </td>
		   <td align="left" valign="middle" class="tdcolorgray"><input name="survey_question" type="text" id="survey_question" value="<?=$row['survey_question']?>" />
		   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_QUEST')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	      </tr>
		 <tr  >
		   <td align="left" valign="middle" class="tdcolorgray" >Display Results </td>
		   <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="survey_displayresults"  value="1" <? if($row['survey_displayresults']==1) echo "checked";?> />
		   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_RESDISP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	      </tr>
		 <tr>
		   <td align="left" valign="top" class="tdcolorgray" >Display Location <span class="redtext">*</span></td>
		   <td align="left" valign="top" class="tdcolorgray"><?php
		  	$disp_array		= array();
			if ($db->num_rows($ret_disp))
			{
			while ($row_disp = $db->fetch_array($ret_disp))
				{	
					
					$layoutid				= $row_disp['themes_layouts_layout_id'];
					$layoutcode				= $row_disp['layout_code'];
					$layoutname				= stripslashes($row_disp['layout_name']);
					$disp_id				= $row_disp['display_id'];
					$curid 					= $disp_id."_".$layoutid."_".stripslashes($layoutcode)."_".stripslashes($row_disp['display_position']);
					$ext_val[]				= $layoutid."_".stripslashes($layoutcode)."_".stripslashes($row_disp['display_position']);
					$disp_array[$curid] 	= $layoutname."(".stripslashes($row_disp['display_position']).")(".$row_disp['display_order'].")";
					$disp_ext_arr[]			= $curid; // used to highlight all the items in the dropdownbox
				}
			}
			// Get the list of position allowable for category groups for the current theme
			$sql_themes = "SELECT survey_positions FROM themes WHERE theme_id=$ecom_themeid";
			$ret_themes = $db->query($sql_themes);
			if ($db->num_rows($ret_themes))
			{
				$row_themes = $db->fetch_array($ret_themes);
				$pos_arr	= explode(",",$row_themes['survey_positions']);
			}
			// Get the layouts fot the current theme
			$sql_layouts = "SELECT layout_id,layout_code,layout_name,layout_positions FROM themes_layouts WHERE 
							themes_theme_id=$ecom_themeid";
			$ret_layouts = $db->query($sql_layouts);
			if ($db->num_rows($ret_layouts))
			{ 
				while ($row_layouts = $db->fetch_array($ret_layouts))
				{
					for($i=0;$i<count($pos_arr);$i++)
					{
					$curid 	= $row_layouts['layout_id']."_".stripslashes($row_layouts['layout_code'])."_".stripslashes($pos_arr[$i]);					if(count($ext_val)){
						if(!in_array($curid,$ext_val))
						{
							$curname			= stripslashes($row_layouts['layout_name'])." (".$pos_arr[$i].")";
							$disp_array["0_".$curid] = $curname;
						}
					}else {
						$curname			= stripslashes($row_layouts['layout_name'])." (".$pos_arr[$i].")";
						$disp_array["0_".$curid] = $curname;
					}	
					}	
				}
			}
			echo generateselectbox('display_id[]',$disp_array,$disp_ext_arr,'','',5);
		  ?>&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_DISPLOC')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    </tr>
		 <tr>
          <td width="29%" align="left" valign="middle" class="tdcolorgray" > </td>
          <td width="71%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
	    </tr>
		  <tr>
		 <td align="left" valign="middle" class="tdcolorgray">Show in all pages</td>
          <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="survey_showinall"  value="1" <? if($row['survey_showinall']==1) echo "checked";?> />
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_SHOWALL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		</tr>
		<tr>
		  <td align="left" valign="middle" class="tdcolorgray" >Hidden</td>
		   <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="survey_hide" value="1" <? if($row['survey_hide']==1) echo "checked";?> />
Yes
  <input type="radio" name="survey_hide"  value="0" <? if($row['survey_hide']==0) echo "checked";?> />
No&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		</tr>
		<tr>
		 <td align="left" valign="middle" class="tdcolorgray">Survey Status </td>
		   <td align="left" valign="middle" class="tdcolorgray"><?php 
		   $status_array = array('1' => 'NEW','2' => 'ACTIVE','3' => 'FINISH','4' => 'PUBLISH');
		   $selected = $row['survey_status']; 
		   echo generateselectbox('survey_status',$status_array,$selected);?>
		   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_SETSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		</tr>
		 </table>
		</td>
		<td width="49%" class="tdcolorgray" colspan="3" valign="top">
		<table width="100%"  cellpadding="0" cellspacing="0">
		 <tr>
           <td colspan="4" align="left" valign="top" class="tdcolorgray" ><b>The following are the option values for the question </b>
		   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_QUESTOPT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
         </tr>
		<tr>
           <td colspan="2" align="left" valign="top" > <table width="100%" border="0">
		     <tr>
					    <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
					    <td align="left" valign="middle" class="tdcolorgray" ><b>Option Text</b></td>
					    <td align="left" valign="middle" class="tdcolorgray" ><b>Order</b></td>
		     </tr>
			<?php 
			
			$sql_optionvalues = "SELECT option_id,option_text,option_order  FROM survey_option WHERE survey_id =".$survey_id."";
			$res_optionvalues = $db->query($sql_optionvalues);
			$optioncnt=0;
			if($db->num_rows($res_optionvalues)) {
					while($optionvalues =$db->fetch_array($res_optionvalues)){ 
					$optioncnt++;
					?>
					
					  <tr>
						<td width="26%" align="left" valign="middle" class="tdcolorgray">Option <?=$optioncnt?></td>
						<td width="47%" align="left" valign="middle" class="tdcolorgray" ><input name="option_text[<?=$optioncnt?>]" id="option_text[<?=$optioncnt?>]" type="text" value="<?=$optionvalues['option_text']?>" />
						<input name="option_id[<?=$optioncnt?>]" type="hidden" value="<?=$optionvalues['option_id'];?>" id="option_id[<?=$optioncnt?>]"  size="30">				</td>
					    <td width="27%" align="left" valign="middle" class="tdcolorgray" ><input name="option_order[<?=$optioncnt?>]" id="option_order[<?=$optioncnt?>]" type="text" value="<?=$optionvalues['option_order']?>" size="1" /></td>
					  </tr>
					  
					 
					  <? }
			}
			 for($i=0;$i<5;$i++){
			  $optioncnt++;
			  ?>
			   <tr>
                <td width="26%" align="left" valign="middle" class="tdcolorgray">Option <?=$optioncnt?></td>
                <td width="47%" align="left" valign="middle" class="tdcolorgray" ><input name="option_text[<?=$optioncnt?>]" type="text" value="" />				</td>
                <td width="27%" align="left" valign="middle" class="tdcolorgray" ><input name="option_order[<?=$optioncnt?>]" id="option_order[<?=$optioncnt?>]" type="text" value="<?=$optionvalues['option_order']?>" size="1" /></td>
		      </tr>
			  <? }?>
            </table>	</td>
          </tr>
		  <tr>
		<td align="left" valign="top"  colspan="3" class="tdcolorgray" width="30%">
		   <table width="100%" cellpadding="0" cellspacing="0">
		   <tr>
		       <td align="left" valign="middle" class="tdcolorgray" colspan="2"><b>Active Period</b></td>
		   </tr>
		   <? $id='tr_survay';
		   if($row['survay_activateperiodchange']==1)
		   			 {
					  $display='';
					  
						  $active_start_arr 		= explode(" ",$row['survay_displaystartdate']);
						  $active_end_arr 			= explode(" ",$row['survay_displayenddate']);
						  $active_starttime_arr 	= explode(":",$active_start_arr[1]);
							$active_start_hr			= $active_starttime_arr[0];
							$active_start_mn			= $active_starttime_arr[1];
							$active_start_ss			= $active_starttime_arr[2];	
							$active_endttime_arr 		= explode(":",$active_end_arr[1]);
							$active_end_hr				= $active_endttime_arr[0];
							$active_end_mn				= $active_endttime_arr[1];
							$active_end_ss				= $active_endttime_arr[2];	
						  $exp_survey_displaystartdate=explode("-",$active_start_arr[0]);
						  $val_survay_displaystartdate=$exp_survey_displaystartdate[2]."-".$exp_survey_displaystartdate[1]."-".$exp_survey_displaystartdate[0];
						  $exp_survey_displayenddate=explode("-",$active_end_arr[0]);
						  $val_survay_displayenddate  =$exp_survey_displayenddate[2]."-".$exp_survey_displayenddate[1]."-".$exp_survey_displayenddate[0];
						$display='';
					}
					else
					{ 
					 //echo "none";
					  $display='none';
					}
						for($i=0;$i<60;$i++)
					$option .= '<option value="'.$i.'">'.$i.'</option>';
					for($i=0;$i<=23;$i++)
					$houroption .= '<option value="'.$i.'">'.$i.'</option>';	
		   ?>
		    <tr>
		    <td lign="left" valign="middle" class="tdcolorgray" width="38%">
			    Change Active Period			</td>
			 <td lign="left" valign="middle" class="tdcolorgray" width="62%">
			    <input type="checkbox" name="survay_activateperiodchange"  onclick="activeperiod(this.checked,'<? echo $id?>')" value="1" <? if($row['survay_activateperiodchange']==1) echo "checked"?>/>
			&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_ACTPERIOD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		   </tr>
		   <tr id="<? echo $id;?>" style="display:<?= $display; ?>">
		   <td colspan="3" class="tdcolorgray" >
		   <table width="100%" cellpadding="0" cellspacing="0"> 
		   <tr >
		     <td align="right" valign="middle" >&nbsp;</td>
		     <td  align="left" valign="middle">&nbsp;</td>
		     <td width="11%" align="left" valign="middle">&nbsp;</td>
		     <td width="13%" class="tdcolorgray">Hrs</td>
		     <td width="14%" class="tdcolorgray">Min</td>
		     <td width="19%" class="tdcolorgray">Sec</td>
		     </tr>
		   <tr >
		    <td align="right" valign="middle" class="tdcolorgray">
			    Start Date			</td>
			<td  align="left" valign="middle"  width="17%"><input class="input" type="text" name="survay_displaystartdate" size="8" value="<? echo $val_survay_displaystartdate ?>"  />		  </td>
			<td align="left" valign="middle"><a href="javascript:show_calendar('frmEditSurvey.survay_displaystartdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a></td>
		    <td align="left" valign="middle"><select name="survey_starttime_hr" id="survey_starttime_hr">
              <option value="<?php echo $active_start_hr?>"><?php echo $active_start_hr?></option>
              <?php echo $houroption?>
            </select></td>
		    <td align="left" valign="middle"><select name="survey_starttime_mn" id="survey_starttime_mn">
              <option value="<?php echo $active_start_mn?>"><?php echo $active_start_mn?></option>
              <?php echo $option?>
            </select></td>
		    <td align="left" valign="middle"><select name="survey_starttime_ss" id="survey_starttime_ss">
              <option value="<?php echo $active_start_ss?>"><?php echo $active_start_ss?></option>
              <?php echo $option?>
            </select></td>
		   </tr>
		   <tr >
		    <td align="right" valign="middle" class="tdcolorgray" width="26%">
			    End Date			</td>
			<td  align="left" valign="middle"  width="17%"><input class="input" type="text" name="survay_displayenddate" size="8" value="<? echo $val_survay_displayenddate ?>"  />		  </td>
			<td align="left" valign="middle"   >
		  <a href="javascript:show_calendar('frmEditSurvey.survay_displayenddate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a></td>
		    <td align="left" valign="middle"   ><select name="survey_endtime_hr" id="survey_endtime_hr">
              <option value="<?php echo $active_end_hr?>"><?php echo $active_end_hr?></option>
              <?php echo $houroption?>
            </select></td>
		    <td align="left" valign="middle"   ><select name="survey_endtime_mn" id="survey_endtime_mn">
              <option value="<?php echo $active_end_mn?>"><?php echo $active_end_mn?></option>
              <?php echo $option?>
            </select></td>
		    <td align="left" valign="middle"   ><select name="survey_endtime_ss" id="survey_endtime_ss">
              <option value="<?php echo $active_end_ss?>"><?php echo $active_end_ss?></option>
              <?php echo $option?>
            </select></td>
		   </tr>
		   </table>		   
		   </td>
		   </tr>
		   </table>		   </td>
		</tr>
		</table>		</td>
		</tr>
         <tr>
           <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
         </tr>
        
		<tr>
          <td colspan="4" align="center" valign="middle" class="tdcolorgray" >
		   
  <input type="hidden" name="survey_id" id="survey_id" value="<?=$survey_id?>" />
  <input type="hidden" name="pass_search_name" id="pass_search_name" value="<?=$_REQUEST['pass_search_name']?>" />
  <input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['pass_start']?>" />
  <input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
  <input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
  <input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
  <input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
  <input type="hidden" name="status" value="<?=$status?>" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="update_survey" />
		  <input type="hidden" name="retdiv_id" id="retdiv_id" value="maincontent" />
		  <input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
		  
		  <input name="Submit" type="submit" class="red" value="Submit" /></td>
        </tr>
		 <tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
        </tr>
<!--		for displaying the survey results STARTS
-->			
<tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" > 
		  
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="3%" class="seperationtd"><img id="cat_imgtag" src="images/plus.gif" border="0" onclick="handle_expansionall(this,'survey_results')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd"> View Survey Results for this survey&nbsp;			  </td>
            </tr>
          </table></td>
        </tr>
		<tr>
          <td colspan="4" align="right" valign="middle" class="tdcolorgray" >
&nbsp;&nbsp;				</td>
        </tr>
		
		<tr id="results_tr">
			<td align="right" colspan="4" class="tdcolorgray_buttons">
			<div id="results_div" style="text-align:center">			</div>			</td>
		</tr>
<!--for diaplaying survey results ends
-->		<!--		for displaying the Categories assigned to the Surveys STARTS -->	
		<tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" > 
		  <?php
		 // Check whether categories are Assiged to this Surveys
			$sql_categories_in_survey = "SELECT id FROM survey_display_category
						 WHERE survey_survey_id=$survey_id";
			$ret_categories_in_survey = $db->query($sql_categories_in_survey);
			
		 
		 ?>
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="3%" class="seperationtd"><img id="cat_imgtag" src="images/plus.gif" border="0" onclick="handle_expansionall(this,'category')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd">  Categories for which  this Survey will be displayed &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_ASSCAT_HEAD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
          </table></td>
        </tr>
		<tr>
          <td colspan="4" align="right" valign="middle" class="tdcolorgray" ><input name="Addmore" type="button" class="red" id="Addmore" value="Assign More" onclick="document.frmEditSurvey.fpurpose.value='list_assign_categories';document.frmEditSurvey.submit();" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_ASSCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				if ($db->num_rows($ret_categories_in_survey))
				{
				?>
					<div id="categoryunassign_div" class="unassign_div" style="display:none">
					<!--Change Hidden Status to -->
					<?php
						/*$categories_status = array(0=>'No',1=>'Yes');
						echo generateselectbox('categories_chstatus',$categories_status,0);*/
					?>
					<!--<input name="category_chstatus" type="button" class="red" id="category_chstatus" value="Change" onclick="call_ajax_changestatuspagesall('category','checkboxcategory[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_CHSTATCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
-->								
					&nbsp;&nbsp;&nbsp;<input name="categories_unassign" type="button" class="red" id="categories_unassign" value="Un Assign" onclick="call_ajax_deleteall('category','checkboxcategory[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_UNASSCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
				<?php
				}				
				?></td>
        </tr>
		
		<tr id="category_tr">
			<td align="right" colspan="4" class="tdcolorgray_buttons">
			<div id="category_div" style="text-align:center">			</div>			</td>
		</tr>
<!--		for displaying the Categories assigned to the Survey ENDS  -->	
		<tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp; </td>
        </tr>
<!--		for displaying the products assigned to the Survey STARTS  -->	
	<tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" >  
		  <?php
		 // Check whether Products are added to this survey
			$sql_product_in_survey = "SELECT products_product_id FROM survey_display_product
						 WHERE survey_survey_id=$survey_id";
			$ret_product_in_survey = $db->query($sql_product_in_survey);		
		 
		 ?>
		 <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="3%" class="seperationtd"><img id="cat_imgtag" src="images/plus.gif" border="0" onclick="handle_expansionall(this,'products')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd"> Products for which  this Survey will be displayed &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_ASSPROD_HEAD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
          </table></td>
        </tr>
		<tr>
          <td colspan="4" align="right" valign="middle" class="tdcolorgray" ><input name="Addmore" type="button" class="red" id="Addmore" value="Assign More" onclick="document.frmEditSurvey.fpurpose.value='list_assign_products';document.frmEditSurvey.submit();" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_ASSPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				if ($db->num_rows($ret_product_in_survey))
				{
				?>
					<div id="productsunassign_div" class="unassign_div" style="display:none">
					<!--Change Hidden Status to -->
					<?php
						/*$products_status = array(0=>'No',1=>'Yes');
						echo generateselectbox('product_chstatus',$products_status,0);*/
					?>
					<!--<input name="product_chstatus" type="button" class="red" id="product_chstatus" value="Change" onclick="call_ajax_changestatuspagesall('product','checkboxproducts[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_CHSTATPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
-->								
					&nbsp;&nbsp;&nbsp;<input name="products_unassign" type="button" class="red" id="products_unassign" value="Un Assign" onclick="call_ajax_deleteall('product','checkboxproducts[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_UNASSPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
				<?php
				}				
				?></td>
        </tr>
		<tr id="products_tr">
			<td align="right" colspan="4" class="tdcolorgray_buttons">
			<div id="products_div" style="text-align:center">			</div>			</td>
		</tr>
		<!--		for displaying the products assigned to the Survey ENDS  -->	
		<tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp; </td>
        </tr>
		<!--		for displaying the Static Pages assigned to the Survey STARTS  -->	
	<tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" >  
		  <?php
		 // Check whether Pages are added to this Surevy
			$sql_assigned_pages = "SELECT static_pages_page_id FROM survey_display_static
						 WHERE survey_survey_id=$survey_id";
			$ret_assigned_pages = $db->query($sql_assigned_pages);		
		 
		 ?>
		 <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="3%" class="seperationtd"><img id="cat_imgtag" src="images/plus.gif" border="0" onclick="handle_expansionall(this,'assign_pages')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd">  Static Pages for which  this Survey will be displayed&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_ASSSTAT_HEAD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
          </table></td>
        </tr>
		<tr>
          <td colspan="4" align="right" valign="middle" class="tdcolorgray" ><input name="Addmore" type="button" class="red" id="Addmore" value="Assign More" onclick="document.frmEditSurvey.fpurpose.value='list_assign_pages';document.frmEditSurvey.submit();" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_ASSSTAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				if ($db->num_rows($ret_assigned_pages))
				{
				?>
					<div id="assign_pagesunassign_div" class="unassign_div" style="display:none">
					<!--Change Hidden Status to -->
					<?php
						/*$products_status = array(0=>'No',1=>'Yes');
						echo generateselectbox('assign_pages_chstatus',$products_status,0);*/
					?>
					<!--<input name="assign_pages_chstatus" type="button" class="red" id="assign_pages_chstatus" value="Change" onclick="call_ajax_changestatuspagesall('assign_pages','checkboxassignpages[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_CHSTATPAGE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
-->								
					&nbsp;&nbsp;&nbsp;<input name="assign_pages_unassign" type="button" class="red" id="assign_pages_unassign" value="Un Assign" onclick="call_ajax_deleteall('assign_pages','checkboxassignpages[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SURVAY_UNASSSTAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
				<?php
				}				
				?></td>
        </tr>
		<tr id="assign_pages_tr">
			<td align="right" colspan="4" class="tdcolorgray_buttons">
			<div id="assign_pages_div" style="text-align:center">			</div>			</td>
		</tr>
		<!--		for displaying the Static pages assigned to the Survey ENDS  -->	
		<tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp; </td>
        </tr><tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp; </td>
        </tr><tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp; </td>
        </tr>
      </table>
</form>	  


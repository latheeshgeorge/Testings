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
if($survey_id==''){
$survey_id=($_REQUEST['survey_id']?$_REQUEST['survey_id']:$_REQUEST['checkbox'][0]);
}
if($curtab==''){
$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';
}
	$sql="SELECT survey_id,survey_title,survey_question,survey_hide,survey_displayresults,survey_showinall,
				 survey_status,survay_activateperiodchange,survay_displaystartdate,survay_displayenddate  
				 		FROM survey 
								WHERE sites_site_id=$ecom_siteid AND survey_id=".$survey_id;
	$res=$db->query($sql);
	if($db->num_rows($res)==0)  { echo " <font color='red'> You Are Not Authorised  </a>"; exit; } 
	$row=$db->fetch_array($res);
	$survey_showinall = $row['survey_showinall'];
(trim($survey_title))?$survey_title=$_REQUEST['survey_title']:$survey_title=$row['survey_title'];

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
	var tab = '<?PHP echo $curtab; ?>';
	var typ = '<?PHP echo $row['advert_type']; ?>';
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
			
			if(norecdiv=='main_tab_td') {
				var adverttype = document.getElementById('advert_type').value;
				handletype_change( typ);
			}
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

/*
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

	/* check whether any checkbox is ticked /
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
			document.getElementById('retdiv_id').value 	= retdivid;/ Name of div to show the result /
			document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result /	
			retobj 										= eval("document.getElementById('"+retdivid+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
		Handlewith_Ajax('services/survey.php','fpurpose='+fpurpose+'&chstat='+chstat+'&cur_survey_id='+survey_id+'&ch_ids='+ch_ids);
		}	
	}	
}	
*/

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
	var cname							= '<?php echo $_REQUEST['pass_search_name']?>';
	var sortby							= '<?php echo $_REQUEST['pass_sort_by']?>';
	var sortorder						= '<?php echo $_REQUEST['pass_sort_order']?>';
	var recs							= '<?php echo $_REQUEST['pass_records_per_page']?>';
	var start							= '<?php echo $_REQUEST['pass_start']?>';
	var pg								= '<?php echo $_REQUEST['pass_pg']?>';
	var status							= '<?php echo $_REQUEST['status']?>';
	var curtab							= '<?php echo $curtab?>';
	var showinall						= '<?php echo $survey_showinall?>'; 
	var survey_title					= '<?php echo $survey_title?>';
	var qrystr							= 'pass_search_name='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&survey_id='+survey_id+'&curtab='+curtab+'&showinall='+showinall+'&survey_title='+survey_title+'&survey_showinall='+showinall+'&status='+status;

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
		
			fpurpose	= 'delete_category_ajax';
		break;
		case 'product': // Case of product tabs
			atleastmsg 	= 'Please select the product(s) to be deleted';
			confirmmsg 	= 'Are you sure you want to delete the selected Product(s)?'
		
			fpurpose	= 'delete_product_ajax';
		break;
		case 'assign_pages': // Case of linked products
			atleastmsg 	= 'Please select the Static Page(s) to be Unassigned';
			confirmmsg 	= 'Are you sure you want to Unassign the selected Static Page(s)?'
		
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
			//document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
			//document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
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
		


function handle_tabs(id,mod)
{
	tab_arr 							= new Array('main_tab_td','survyreslt_tab_td','survycatg_tab_td','survyprod_tab_td','survystatic_tab_td');
	var atleastone 						= 0;
	var survey_id						= '<?php echo $survey_id?>';
	var cat_orders						= '';
	var fpurpose						= '';
	var retdivid						= '';
	var moredivid						= '';
	var cname							= '<?php echo $_REQUEST['pass_search_name']?>';
	var sortby							= '<?php echo $_REQUEST['pass_sort_by']?>';
	var sortorder						= '<?php echo $_REQUEST['pass_sort_order']?>';
	var recs							= '<?php echo $_REQUEST['pass_records_per_page']?>';
	var start							= '<?php echo $_REQUEST['pass_start']?>';
	var pg								= '<?php echo $_REQUEST['pass_pg']?>';
	var status							= '<?php echo $_REQUEST['status']?>';
	var curtab							= '<?php echo $curtab?>';
	var showinall						= '<?php echo $survey_showinall?>'; 
	var survey_title					= '<?php echo $survey_title?>';
	var qrystr							= 'pass_search_name='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&survey_id='+survey_id+'&curtab='+curtab+'&showinall='+showinall+'&survey_title='+survey_title+'&survey_showinall='+showinall+'&status='+status;
	for(i=0;i<tab_arr.length;i++)
	{
		if(tab_arr[i]!=id)
		{
			obj = eval ("document.getElementById('"+tab_arr[i]+"')");
			obj.className = 'toptab';
		}
	}
	obj = eval ("document.getElementById('"+id+"')");
	obj.className = 'toptab_sel';
	    
	switch(mod)
	{
		case 'surveymain_info':
		
			fpurpose 	= 'list_survey_maininfo';
			
		break;
		case 'survey_result': // Case of Categories in the group
		
			fpurpose	= 'list_survey_result';
			
		break;
		case 'survycategory_group': // Case of Display Products in the group
		
			fpurpose	= 'list_survey_categgroup';
			
		break;
		case 'survyproduct_group': // Case of Display Categories in the group
		
			fpurpose	= 'list_survey_prodgroup';
			
		break;
		case 'survystatic_group': // Case of Display Categories in the group
		
			fpurpose	= 'list_survey_staticgroup';
			
		break;
	}
	retobj 									= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	//retobj								= document.getElementById('retdiv_id');
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	
	/* Calling the ajax function */
	//alert('fpurpose='+fpurpose+'&cur_groupid='+group_id);
	Handlewith_Ajax('services/survey.php','fpurpose='+fpurpose+'&survey_id='+survey_id+'&'+qrystr);	
}

function normal_assign_categories(cname,sortby,sortorder,recs,start,pg,survey_id,survey_title,status)
{
		window.location 			= 'home.php?request=survey&fpurpose=list_assign_categories&pass_search_name='+cname+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_survey_id='+survey_id+'&survey_title='+survey_title+'&status='+status;
}
function normal_assign_prodGroupAssign(cname,sortby,sortorder,recs,start,pg,survey_id,survey_title,status)
{
		window.location 			= 'home.php?request=survey&fpurpose=list_assign_products&pass_search_name='+cname+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_survey_id='+survey_id+'&survey_title='+survey_title+'&status='+status;
}
function normal_assign_StaticGroupAssign(cname,sortby,sortorder,recs,start,pg,survey_id,survey_title,status)
{
		window.location 			= 'home.php?request=survey&fpurpose=list_assign_pages&pass_search_name='+cname+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_survey_id='+survey_id+'&survey_title='+survey_title+'&status='+status;
}

</script>
<form name='frmEditSurvey' action='home.php?request=survey' enctype="multipart/form-data" method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="6" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=survey&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&search_name=<?=$_REQUEST['pass_search_name']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&status=<?=$_REQUEST['status']?>">List Survey</a><span> Edit Survey</span></div></td>
        </tr>
     <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="6">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<tr>
			<td colspan="6" align="left">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabmenu_x" >
				<tr> 
						<td width="10%" align="left" onClick="handle_tabs('main_tab_td','surveymain_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Main Info</span></td>
						<td width="17%" align="left" onClick="handle_tabs('survyreslt_tab_td','survey_result')" class="<?php if($curtab=='survyreslt_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="survyreslt_tab_td"><span>View Survey Results </span></td>
						<td width="22%" align="left" onClick="handle_tabs('survycatg_tab_td','survycategory_group')" class="<?php if($curtab=='survycatg_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="survycatg_tab_td"><span> Show Survey in these Categories</span> </td>
						<td width="23%" align="left" onClick="handle_tabs('survyprod_tab_td','survyproduct_group')" class="<?php if($curtab=='survyprod_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="survyprod_tab_td"><span>Show Survey in these Products </span></td>
						<td width="24%" align="left" onClick="handle_tabs('survystatic_tab_td','survystatic_group')" class="<?php if($curtab=='survystatic_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="survystatic_tab_td"><span>Show Survey in these Static Pages</span></td>
						<td width="4%" align="left">&nbsp;</td>
				</tr>
				</table>			</td>
		</tr>
		<?php /*?><?php 
			if($alert!='')
			{			
		?>
        	<tr>
          		<td colspan="6" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        	</tr>
		<?
			}
		?><?php */?>
<tr>
		  <td colspan="6" align="center" valign="middle" class="tdcolorgray" >
		  <div id='master_div'>
			<?php 
			if ($curtab=='main_tab_td')
			{ 
				show_survey_maininfo($survey_id,$alert);
			}
			elseif ($curtab=='survyreslt_tab_td')
			{
				view_survey_results($survey_id,$alert);
			}
			elseif ($curtab=='survycatg_tab_td')
			{
				show_category_list($survey_id,$alert);
			}
			elseif ($curtab=='survyprod_tab_td')
			{
				show_product_list($survey_id,$alert);
			}
			elseif ($curtab=='survystatic_tab_td')
			{
				show_assign_pages_list($survey_id,$alert);
			}
			?>		
		  </div>
		  </td>
    </tr>
		<tr>
          <td colspan="6" align="center" valign="middle" class="tdcolorgray" >
		   
  <input type="hidden" name="survey_id" id="survey_id" value="<?=$survey_id?>" />
  <input type="hidden" name="pass_search_name" id="pass_search_name" value="<?=$_REQUEST['pass_search_name']?>" />
  <input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['pass_start']?>" />
  <input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
  <input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
  <input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
  <input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
  <input type="hidden" name="status" value="<?=$_REQUEST['status']?>" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="update_survey" />
		  <input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
		  <input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
		</td>
        </tr>
		 <tr>
          <td colspan="6" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
        </tr>
	
	<tr>
          <td colspan="6" align="left" valign="middle" class="tdcolorgray" >&nbsp; </td>
        </tr>
      </table>
</form>	  

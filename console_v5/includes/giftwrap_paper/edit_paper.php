<?php
	/*#################################################################
	# Script Name 	: edit_paper.php
	# Description 	: Page for editing Giftwrap Paper
	# Coded by 		: SKR
	# Created on	: 23-July-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'Papers';
$help_msg = get_help_messages('EDIT_GIFTWRAP_PAP_MESS1');
$paper_id=($_REQUEST['paper_id']?$_REQUEST['paper_id']:$_REQUEST['checkbox'][0]);
$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';

$sql_paper="SELECT paper_name,paper_extraprice,paper_order,paper_active FROM giftwrap_paper  WHERE paper_id=".$paper_id;
$res_paper= $db->query($sql_paper);
$row_paper = $db->fetch_array($res_paper);

?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('paper_name');
	fieldDescription = Array('Paper Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('paper_extraprice','paper_order');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		if(frm.paper_extraprice.value<0)
		{
		  alert('Extra Price entered should be a positive value.');
		  frm.paper_extraprice.focus();
		  return false;
		}
		else
		{
		show_processing();
		return true;
		}
	} else {
		return false;
	}
}
function handle_expansion(imgobj,mod)
{
	var src 			= imgobj.src;
	var retindx 		= src.search('plus.gif');
	switch(mod)
	{
		case 'paper_img': /* Case of bow images*/
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('paper_img_tr'))
					document.getElementById('paper_img_tr').style.display = '';
				if(document.getElementById('paper_imgunassign_div'))
					document.getElementById('paper_imgunassign_div').style.display = '';	
				call_ajax_showlistall('paper_img');
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('paper_img_tr'))
					document.getElementById('paper_img_tr').style.display = 'none';
				if(document.getElementById('paper_imgunassign_div'))
					document.getElementById('paper_imgunassign_div').style.display = 'none';
			}	
		break;
	};
}
function call_ajax_showlistall(mod)
{
	var atleastone 										= 0;
	var paper_id											= '<?php echo $paper_id;?>';
	var cat_orders										= '';
	var fpurpose										= '';
	var retdivid										= '';
	var moredivid										= '';
	switch(mod)
	{
		case 'paper_img': // Case of category images
			//retdivid   	= 'paper_img_div';
			//moredivid	= 'paper_imgunassign_div';
			fpurpose	= 'list_paper_img';
		break;
	};
	//document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */	
	//document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
	retobj 											= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	var qrystr										= '';
	/* Calling the ajax function */
	Handlewith_Ajax('services/giftwrap_paper.php','fpurpose='+fpurpose+'&'+qrystr+'&paper_id='+paper_id);
}
function ajax_return_contents() 
{
	var ret_val='';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val 	= req.responseText;
			targetdiv 	= document.getElementById('retdiv_id').value;
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
		}
		else
		{
			show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}
function call_ajax_deleteall(checkboxname)
{
	var atleastone 			= 0;
	var paperid				= '<?php echo $paper_id?>';
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditPaper.elements.length;i++)
	{
		if (document.frmEditPaper.elements[i].type =='checkbox' && document.frmEditPaper.elements[i].name==checkboxname)
		{

			if (document.frmEditPaper.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmEditPaper.elements[i].value;
			}	
		}
	}
	
	atleastmsg 	= 'Please select the bow image(s) to be unassigned.';
	confirmmsg 	= 'Are you sure you want to unassign the selected Image(s)?';
	//retdivid   	= 'paper_img_div';
	//moredivid	= 'paper_imgunassign_div';
	fpurpose	= 'unassign_paperimagedetails';
	if (atleastone==0)
	{
		alert(atleastmsg);
	}
	else
	{
		if(confirm(confirmmsg))
		{
			//document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
			//document.getElementById('retdiv_more').value= moredivid;/* Name of div to show the result */
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/giftwrap_paper.php','fpurpose='+fpurpose+'&paper_id='+paperid+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function call_ajax_saveimagedetails(checkboxname)
{
	var atleastone 			= 0;
	var paperid				= '<?php echo $paper_id?>';
	var ch_ids 				= '';
	var ch_variable			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	//var retdivid			= 'paper_img_div';
	//var moredivid			= 'paper_imgunassign_div';
	var fpurpose			= 'save_paperimagedetails';
	var ch_order			= '';
	var ch_title			= '';
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditPaper.elements.length;i++)
	{
		if (document.frmEditPaper.elements[i].type =='checkbox' && document.frmEditPaper.elements[i].name== checkboxname)
		{

			if (document.frmEditPaper.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmEditPaper.elements[i].value;
				 
				 obj1 = eval("document.getElementById('img_ord_"+document.frmEditPaper.elements[i].value+"')");
				 obj2 = eval("document.getElementById('img_title_"+document.frmEditPaper.elements[i].value+"')");
				 
				if (ch_order != '')
					ch_order += '~';
				 ch_order += obj1.value; 
				 
				 if (ch_title != '')
					ch_title += '~';
				 ch_title += obj2.value; 
			}	
		}
	}
	
	if (atleastone==0)
	{
		alert('Please select the image(s) to be saved');
	}
	else
	{
		if(confirm('Are you sure you want to save the title and order of selected images?'))
		{
			//document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/giftwrap_paper.php','fpurpose='+fpurpose+'&paper_id='+paperid+'&ch_order='+ch_order+'&ch_title='+ch_title+'&ch_ids='+ch_ids+'&'+qrystr);
		}	
	}
}
function handle_imagesel(id)
{
	var ret_str	= '';
	var new_str = ''
	tdobj		= eval("document.getElementById('img_td_"+id+"')");
	if(tdobj.className=='imagelistproducttabletd')
	{
		tdobj.className = 'imagelistproducttabletd_sel';
	}	
	else
	{
		tdobj.className = 'imagelistproducttabletd';
	}	
}
function handle_tabs(id,mod)
	{
	tab_arr 								= new Array('main_tab_td','images_tab_td');  
	var atleastone 							= 0;
	var paper_id								= '<?php echo $paper_id?>';
	var cat_orders							= '';
	var fpurpose							= '';
	var retdivid							= '';
	var moredivid							= '';
	var search_name							= '<?php echo $_REQUEST['search_name']?>';
	var sortby								= '<?php echo $sort_by?>';
	var sortorder							= '<?php echo $sort_order?>';
	var recs								= '<?php echo $records_per_page?>';
	var start								= '<?php echo $start?>';
	var pg									= '<?php echo $pg?>';
	var curtab								= '<?php echo $curtab?>';
	//var showinall							= '<?php //echo $advert_showinall?>';
	//var advert_title						= '<?php //echo $advert_title; ?>';
	var qrystr								= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&paper_id='+paper_id+'&curtab='+curtab;
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
		case 'papermain_info':
			fpurpose ='list_paper_maininfo';
		break;
		case 'image_paper_group': // Case of Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'list_paper_img';
			//moredivid	= 'category_groupunassign_div';
			
		break;  
	}
	retobj 									= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	//retobj										= document.getElementById('retdiv_id');
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';
	//document.getElementById('retdiv_more').value = id;															
	/* Calling the ajax function */
	//alert('fpurpose='+fpurpose+'&cur_groupid='+paper_id);
	Handlewith_Ajax('services/giftwrap_paper.php','fpurpose='+fpurpose+'&paper_id='+paper_id+'&'+qrystr);	
}
</script>
<form name='frmEditPaper' action='home.php?request=giftwrap_papers' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=giftwrap_papers&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Giftwrap Papers</a><span>Edit Paper</span></div></td>
        </tr>
       <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="2">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<tr>
          <td colspan="2" align="center" valign="middle" ><table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabmenu_x">
				<tr>  
						<td  align="left" onClick="handle_tabs('main_tab_td','papermain_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Main Info</span></td>
						<td  align="left" onClick="handle_tabs('images_tab_td','image_paper_group')" class="<?php if($curtab=='images_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="images_tab_td"> <span>Paper Images</span> </td>
						<td width="90%" align="left">&nbsp;</td>  
				</tr></table></td>
        </tr>
		
        <tr>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray" ><div id='master_div'>
			<?php 
			 
			if ($curtab=='main_tab_td')
			{
			
				show_paper_maininfo($paper_id,$alert);
			}
			elseif ($curtab=='images_tab_td')
			{
				show_paper_image_list($paper_id,$alert);
			}
		
			?>		
		  </div></td>
        </tr>
        <tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  
		  <input type="hidden" name="paper_id" id="country_id" value="<?=$paper_id?>" />
		  <input type="hidden" name="pass_search_name" id="pass_search_name" value="<?=$_REQUEST['search_name']?>" />
		  <input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['start']?>" />
		  <input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['sort_by']?>" />
		  <input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['sort_order']?>" />
		  <input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		  <input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pg']?>" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="update" />
		  <input type="hidden" name="checkbox[0]" id="checkbox[0]" value="<?php echo $paper_id?>" />
		  <input type="hidden" name="src_page" id="src_page" value="gift_paper" />
		  <input type="hidden" name="src_id" id="src_id" value="<?php echo $paper_id?>" />
		  <input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
		  <input type="hidden" name="retdiv_more" id="retdiv_more" value="" /></td>
        </tr>
		
      </table>
</form>	  


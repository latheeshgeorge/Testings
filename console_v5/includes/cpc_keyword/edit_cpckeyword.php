<?php
	/*#################################################################
	# Script Name 	: edit_bow.php
	# Description 	: Page for editing Giftwrap Bow
	# Coded by 		: SKR
	# Created on	: 21-July-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'Keyword';
$help_msg =get_help_messages('ADD_KEYWORD_MESS1');

$keyword_id=($_REQUEST['keyword_id']?$_REQUEST['keyword_id']:$_REQUEST['checkbox'][0]);

$sql_key="SELECT keyword_id,keyword_word 
			FROM costperclick_keywords  
				WHERE sites_site_id=$ecom_siteid AND keyword_id=".$keyword_id;
$res_key= $db->query($sql_key);
if($db->num_rows($res_key)==0) { echo " <font color='red'> You Are Not Authorised  </a>"; exit; }
$row_key = $db->fetch_array($res_key);
?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('keyword_word');
	fieldDescription = Array('Keyword');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		show_processing();
		return true;
	} else {
		return false;
	}
}
/*function handle_expansion(imgobj,mod)
{
	var src 			= imgobj.src;
	var retindx 		= src.search('plus.gif');
	switch(mod)
	{
		case 'bow_img': //Case of bow images//
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('bow_img_tr'))
					document.getElementById('bow_img_tr').style.display = '';
				if(document.getElementById('bow_imgunassign_div'))
					document.getElementById('bow_imgunassign_div').style.display = '';	
				call_ajax_showlistall('bow_img');
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('bow_img_tr'))
					document.getElementById('bow_img_tr').style.display = 'none';
				if(document.getElementById('bow_imgunassign_div'))
					document.getElementById('bow_imgunassign_div').style.display = 'none';
			}	
		break;
	};
}*/
/*function call_ajax_showlistall(mod)
{
	var atleastone 										= 0;
	var bow_id											= '<?php //echo $bow_id;?>';
	var cat_orders										= '';
	var fpurpose										= '';
	var retdivid										= '';
	var moredivid										= '';
	switch(mod)
	{
		case 'bow_img': // Case of category images
			retdivid   	= 'bow_img_div';
			moredivid	= 'bow_imgunassign_div';
			fpurpose	= 'list_bow_img';
		break;
	};
	document.getElementById('retdiv_id').value 		= retdivid;// Name of div to show the result //	
	document.getElementById('retdiv_more').value 	= moredivid;// Name of div to show the result //	
	retobj 											= eval("document.getElementById('"+retdivid+"')");
	retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	var qrystr										= '';
	// Calling the ajax function //
	Handlewith_Ajax('services/giftwrap_bow.php','fpurpose='+fpurpose+'&'+qrystr+'&bow_id='+bow_id);
}*/
/*function ajax_return_contents() 
{
	var ret_val='';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val 	= req.responseText;
			targetdiv 	= document.getElementById('retdiv_id').value;
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; // Setting the output to required div //
		}
		else
		{
			show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}*/
/*function call_ajax_deleteall(checkboxname)
{
	var atleastone 			= 0;
	var bow_id				= '<?php //echo $bow_id?>';
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var search_name							= '<?php //echo $_REQUEST['search_name']?>';
	var sortby								= '<?php //echo $sort_by?>';
	var sortorder							= '<?php //echo $sort_order?>';
	var recs								= '<?php //echo $records_per_page?>';
	var start								= '<?php //cho $start?>';
	var pg									= '<?php //echo $pg?>';
	var curtab								= '<?php //echo $curtab?>';
	var showinall							= '<?php //echo $advert_showinall?>';
	var advert_title						= '<?php //echo $advert_title; ?>';
	var qrystr								= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&bow_id='+bow_id+'&curtab='+curtab+'&advert_showinall='+showinall+'&advert_title='+advert_title;

	// check whether any checkbox is ticked //
	for(i=0;i<document.frmEditBow.elements.length;i++)
	{
		if (document.frmEditBow.elements[i].type =='checkbox' && document.frmEditBow.elements[i].name==checkboxname)
		{

			if (document.frmEditBow.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmEditBow.elements[i].value;
			}	
		}
	}
	
	atleastmsg 	= 'Please select the bow image(s) to be unassigned.';
	confirmmsg 	= 'Are you sure you want to unassign the selected Image(s)?';
	//retdivid   	= 'bow_img_div';
	//moredivid	= 'bow_imgunassign_div';
	fpurpose	= 'unassign_bowimagedetails';
	if (atleastone==0)
	{
		alert(atleastmsg);
	}
	else
	{
		if(confirm(confirmmsg))
		{
			//document.getElementById('retdiv_id').value 	= retdivid;// Name of div to show the result //
			//document.getElementById('retdiv_more').value= moredivid;// Name of div to show the result //
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/giftwrap_bow.php','fpurpose='+fpurpose+'&bow_id='+bow_id+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}*/
/*function call_ajax_saveimagedetails(checkboxname)
{
	var atleastone 			= 0;
	var bow_id				= '<?php //echo $bow_id?>';
	var ch_ids 				= '';
	var ch_variable			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var search_name							= '<?php //echo $_REQUEST['search_name']?>';
	var sortby								= '<?php //echo $sort_by?>';
	var sortorder							= '<?php //echo $sort_order?>';
	var recs								= '<?php //echo $records_per_page?>';
	var start								= '<?php //echo $start?>';
	var pg									= '<?php //echo $pg?>';
	var curtab								= '<?php //echo $curtab?>';
	var showinall							= '<?php //echo $advert_showinall?>';
	var advert_title						= '<?php //echo $advert_title; ?>';
	var qrystr								= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&bow_id='+bow_id+'&curtab='+curtab+'&advert_showinall='+showinall+'&advert_title='+advert_title;

	var fpurpose			= 'save_bowimagedetails';
	var ch_order			= '';
	var ch_title			= '';
	// check whether any checkbox is ticked //
	for(i=0;i<document.frmEditBow.elements.length;i++)
	{
		if (document.frmEditBow.elements[i].type =='checkbox' && document.frmEditBow.elements[i].name== checkboxname)
		{

			if (document.frmEditBow.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmEditBow.elements[i].value;
				 
				 obj1 = eval("document.getElementById('img_ord_"+document.frmEditBow.elements[i].value+"')");
				 obj2 = eval("document.getElementById('img_title_"+document.frmEditBow.elements[i].value+"')");
				 
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
			//document.getElementById('retdiv_id').value 	= retdivid;// Name of div to show the result //
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/giftwrap_bow.php','fpurpose='+fpurpose+'&bow_id='+bow_id+'&ch_order='+ch_order+'&ch_title='+ch_title+'&ch_ids='+ch_ids+'&'+qrystr);
		}	
	}
}*/
/*function handle_imagesel(id)
{
	var ret_str	= '';
	var new_str = ''
	tdobj		= eval("document.getElementById('img_td_"+id+"')");
	if(tdobj.className == 'imagelistproducttabletd')
	{
		tdobj.className = 'imagelistproducttabletd_sel';
	}	
	else
	{
		tdobj.className = 'imagelistproducttabletd';
	}	
}
*/
	/*function handle_tabs(id,mod)
	{
	tab_arr 								= new Array('main_tab_td','images_tab_td');  
	var atleastone 							= 0;
	var bow_id								= '<?php //echo $bow_id?>';
	var cat_orders							= '';
	var fpurpose							= '';
	var retdivid							= '';
	var moredivid							= '';
	var search_name							= '<?php //echo $_REQUEST['search_name']?>';
	var sortby								= '<?php //echo $sort_by?>';
	var sortorder							= '<?php //echo $sort_order?>';
	var recs								= '<?php //echo $records_per_page?>';
	var start								= '<?php //echo $start?>';
	var pg									= '<?php //echo $pg?>';
	var curtab								= '<?php //echo $curtab?>';
	var showinall							= '<?php //echo $advert_showinall?>';
	var advert_title						= '<?php //echo $advert_title; ?>';
	var qrystr								= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&bow_id='+bow_id+'&curtab='+curtab+'&advert_showinall='+showinall+'&advert_title='+advert_title;
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
		case 'bowmain_info':
			fpurpose ='list_bow_maininfo';
		break;
		case 'image_bow_group': // Case of Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'list_bow_img';
			//moredivid	= 'category_groupunassign_div';
			
		break;  
	}
	retobj 									= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	//retobj										= document.getElementById('retdiv_id');
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';
	document.getElementById('retdiv_more').value = id;															
		//	if(id=='main_tab_td') {
				var adverttype = document.getElementById('advert_type').value;
				alert(adverttype);
				handletype_change('<?PHP// echo $row['advert_type']; ?>');
			}//
	// Calling the ajax function //
	//alert('fpurpose='+fpurpose+'&cur_groupid='+bow_id);
	Handlewith_Ajax('services/giftwrap_bow.php','fpurpose='+fpurpose+'&bow_id='+bow_id+'&'+qrystr);	
}*/
</script>
<form name='frmEditBow' action='home.php?request=costperclick_keyword' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><a href="home.php?request=costperclick_keyword&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Cost Per Click Keywords</a> &gt;&gt; Edit Cost Per Click Keyword</td>
        </tr>
        <tr>
          <td colspan="2" align="left" valign="middle" class="helpmsgtd" ><?=$help_msg ?></td>
        </tr>
        <tr>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray" > 
			 <table width="100%" cellpadding="0" cellspacing="0" border="0">
		<?php 
		if($alert)
		{			
		?>
        <tr>
          <td colspan="2" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>  
         <tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Keyword <span class="redtext">*</span> </td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="keyword_word" value="<?=$row_key['keyword_word']?>"  />		  </td>
        </tr>
		 
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp; <input name="Submit" type="submit" class="red" value="Update" /></td>
        </tr>
	</table></td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">
		  
		  <input type="hidden" name="keyword_id" id="keyword_id" value="<?=$keyword_id?>" />
		  <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
		  <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
		  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
		  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
		  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="update" />
		  <input type="hidden" name="checkbox[0]" id="checkbox[0]" value="<?php echo $keyword_id?>" />
		  <input type="hidden" name="src_page" id="src_page" value="gift_bow" />
		  <input type="hidden" name="src_id" id="src_id" value="<?php echo $keyword_id?>" />
		  <input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
		 <input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
		 </td>
        </tr>
		
		
      </table>
</form>	  

<?php
	/*#################################################################
	# Script Name 	: edit_newsletter.php
	# Description 	: Page for editing News Letter
	# Coded by 		: ANU
	# Created on	: 29-Aug-2007
	# Modified by	: ANU
	# Modified On	: 29-Aug-2007
	#################################################################*/
#Define constants for this page

$page_type = 'newsletter';
$help_msg = get_help_messages('EDIT_NOTIFICATION_MESS1');
$newsletter_id=(($_REQUEST['newsletter_id'])?$_REQUEST['newsletter_id']:$_REQUEST['checkbox'][0]);

$sql="SELECT news_id, newsletter_template_id, newsletter_title,preview_title, preview_contents, newsletter_content,newsletter_createdate,newsletter_updatedate 
			FROM customer_email_notification 
				WHERE sites_site_id=$ecom_siteid AND news_id=".$newsletter_id. " AND sites_site_id='".$ecom_siteid."'";
$res=$db->query($sql);
if($db->num_rows($res)==0)  { echo " <font color='red'> You Are Not Authorised  </a>"; exit; } 
$row=$db->fetch_array($res);

$editor_elements = "newsletter_contents";
include_once(ORG_DOCROOT."/console/js/tinymce.php");
?>	
<script language="javascript" type="text/javascript">

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

function call_ajax_showlistall(mod)
{  
	var atleastone 										= 0;
	var newsletter_id									= '<?php echo $newsletter_id?>';
	var cat_orders										= '';
	var fpurpose										= '';
	var retdivid										= '';
	var moredivid										= '';
	switch(mod)
	{
	
	
		case 'products': // Case of product assigned to the Advert
			retdivid   	= 'products_div';
			fpurpose	= 'list_products_ajax';
			moredivid	= 'productsunassign_div';
		break;
	
	}
	document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */	
	document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
	retobj 										= eval("document.getElementById('"+retdivid+"')");
	retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	var qrystr									= '';
	/* Calling the ajax function */
	Handlewith_Ajax('services/email_notify.php','fpurpose='+fpurpose+'&cur_newsletterid='+newsletter_id);
}
function call_ajax_changestatuspagesall(mod,checkboxname)
{ 
	var atleastone 			= 0;
	var newsletter_id		= '<?php echo $newsletter_id?>';
	var ch_ids 				= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';

	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditNewsletter.elements.length;i++)
	{
	
	if (document.frmEditNewsletter.elements[i].type =='checkbox' && document.frmEditNewsletter.elements[i].name==checkboxname)
		{

			if (document.frmEditNewsletter.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmEditNewsletter.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		
		
		case 'product': // Case of product tabs
			atleastmsg 	= 'Please select the product(s) to change the status';
			confirmmsg 	= 'Are you sure you want to Change the Status of selected Product(s) ?';
			retdivid   	= 'products_div';
			moredivid	= 'productsunassign_div';
			fpurpose	= 'changestat_product_ajax';
			var chstat	= document.getElementById('product_chstatus').value;
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
		Handlewith_Ajax('services/adverts.php','fpurpose='+fpurpose+'&chstat='+chstat+'&cur_newsletter_id='+newsletter_id+'&ch_ids='+ch_ids);
		}	
	}	
}	


function call_ajax_deleteall(mod,checkboxname)
{
	var atleastone 			= 0;
	var newsletter_id		= '<?php echo $newsletter_id?>';
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditNewsletter.elements.length;i++)
	{
		if (document.frmEditNewsletter.elements[i].type =='checkbox' && document.frmEditNewsletter.elements[i].name==checkboxname)
		{

			if (document.frmEditNewsletter.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmEditNewsletter.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		
		
		case 'product': // Case of product tabs
			atleastmsg 	= 'Please select the product(s) to be deleted';
			confirmmsg 	= 'Are you sure you want to delete the selected Product(s)?'
			retdivid   	= 'products_div';
			moredivid	= 'productsunassign_div';
			fpurpose	= 'delete_product_ajax';
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
			
			Handlewith_Ajax('services/email_notify.php','fpurpose='+fpurpose+'&cur_newsletter_id='+newsletter_id+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function valform(frm)
{
	fieldRequired = Array('newsletter_title','newsletter_contents');
	fieldDescription = Array('News Letter Title','News Letter Contents');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		show_processing();
		frm.fpurpose.value = 'update_newsletter';
		frm.submit();
		
	} else {
		return false;
	}
}
function tempale_change() 
{
	document.frmEditNewsletter.fpurpose.value='edit'; 
	document.frmEditNewsletter.submit();
}
function temp_check(frm) {
	if(frm.chk_temp.checked==true) {
	var answer = confirm(" If you Change Template You will Lost Previous Data. Do you want to Continue ? ");
	if(answer) {
		document.getElementById('newstemp').style.display = '';
		document.getElementById('tmpcheck').style.display = 'none';		
	} 
}	
	/*
	else {
		document.getElementById('newstemp').style.display = 'none';
		document.getElementById('tmpcheck').style.display = '';	
	} */
}
function handle_expansionall(imgobj,mod)
{
	var src 			= imgobj.src;
	var retindx 		= src.search('plus.gif');
	switch(mod)
	{
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
	};
}
</script>
<form name='frmEditNewsletter' action='home.php?request=email_notify'  method="post" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="5" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=email_notify&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Email Notifcations </a><span> Edit Email Notification</span></div></td>
        </tr>
       <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="5">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<tr>
          <td colspan="5" align="left" valign="middle" > <?PHP echo notification_tabs('main_tab_td',$newsletter_id) ?></td>
        </tr>
		<?php 
		if($alert)
		{			
		?>
        <tr>
          <td colspan="5" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
	
		?>
		<tr>
          <td colspan="5" align="center" valign="middle" class="tdcolorgray" >
		  <div class="editarea_div">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		 <tr id="newstemp" <?PHP if(!isset($_REQUEST['template_name'])) { ?> style="display:none;" <?PHP } ?>>
           <td align="left" valign="middle" class="tdcolorgray" >Notification Template </td>
		   <td colspan="4" align="left" valign="middle" class="tdcolorgray"><?PHP
		    $template_arr = array();
			$template_arr['none'] = ' - NONE - ';
		  	$sql = "SELECT newstemplate_id, newstemplate_name
						   FROM newsletter_template  
						        WHERE newstemplate_hide='0' AND sites_site_id='".$ecom_siteid."' 
								     ORDER BY newstemplate_name";
			$res = $db->query($sql);
			while($trow = $db->fetch_array($res)) {
					$templateid   = $trow['newstemplate_id'];
					$templatename = $trow['newstemplate_name'];
					$template_arr[$templateid] = stripslashes($templatename);
			}	
			$onchange = "javascript:tempale_change()";
			($_REQUEST['template_name'])?$template_id=$_REQUEST['template_name']:$template_id=$row['newsletter_template_id'];
				
			echo generateselectbox('template_name',$template_arr,$template_id,'',$onchange);		
				
		  ?>           </td>
    </tr>
		<?PHP 
		if($_REQUEST['template_name']>0) {
		   	   	
			   $tempsql = "SELECT newstemplate_name, newstemplate_template 
									  FROM newsletter_template 
										   WHERE newstemplate_id='".$_REQUEST['template_name']."'";
			   $tempres = $db->query($tempsql);
			   $temprow = $db->fetch_array($tempres);
			   $template = $temprow['newstemplate_template'];
			    $preview_title = $temprow['newstemplate_name'];						   
		   } else {
		   	   $template = $row['preview_contents'];
			   $preview_title = $row['preview_title'];
		   }
		if(!isset($_REQUEST['template_name'])) {
		
			   $ntempsql = "SELECT newstemplate_name, newstemplate_template 
									  FROM newsletter_template 
										   WHERE newstemplate_id='".$row['newsletter_template_id']."'";
			   $ntempres = $db->query($ntempsql);
			   $ntemprow = $db->fetch_array($ntempres);
			   $templatename = $ntemprow['newstemplate_name'];
				if ($templatename=='') 
				   	$templatename = 'No Template used';
			   
		?>
		 <tr id="tmpcheck">
          <td align="left" valign="middle" class="tdcolorgray" >Current Template        </td>
          <td align="left" valign="middle" class="tdcolorgray" ><?PHP echo $templatename; ?>&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_NOTIFICATION_SLECT_TEMPLATE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td colspan="2" align="right" valign="middle" class="tdcolorgray" >Do You want to change Template </td>
          <td align="left" valign="middle" class="tdcolorgray" >
		  <input type="checkbox" name="chk_temp" value="1" onclick="temp_check(document.frmEditNewsletter)" />	<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_NOTIFICATION_CHANGE_TEMPLATE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	    </tr>
		<?  
		}
		?>
        <tr>
          <td width="18%" align="left" valign="middle" class="tdcolorgray" >Notification  Title  <span class="redtext">*</span> </td>
          <td align="left" valign="middle" class="tdcolorgray"><input name="newsletter_title" type="text" id="newsletter_title" value="<?=$preview_title?>" size="40" /></td>
          <td width="29%" colspan="2" align="right" valign="middle" class="tdcolorgray">Created Date </td>
          <td width="27%" align="left" valign="middle" class="tdcolorgray">
		  <?=dateFormat($row['newsletter_createdate'],'datetime')?></td>
        </tr>
		 <tr>
           <td align="left" valign="middle" class="tdcolorgray" >Notification Contents <span class="redtext">*</span></td>
		   <td align="left" valign="middle" class="tdcolorgray"></td>
           <td colspan="2" align="right" valign="middle" class="tdcolorgray" >Last Updated Date </td>
           <td align="left" valign="middle" class="tdcolorgray"><?=dateFormat($row['newsletter_updatedate'],'datetime');?></td>
    </tr>
		 
		 <tr  >
		   <td colspan="5" align="left" valign="top" class="tdcolorgray" style="padding-left:25px;" >
		   <?php 
						
			/*			include_once("classes/fckeditor.php");
						$editor 			= new FCKeditor('newsletter_contents') ;
						$editor->BasePath 	= '/console/js/FCKeditor/';
						$editor->Width 		= '850';
						$editor->Height 	= '500';
						$editor->ToolbarSet = 'BshopWithImages';
						$editor->Value 		= stripslashes($template);
						$editor->Create() ;
			*/			
				       
		?>
		<textarea style="height:500px; width:850px" id="newsletter_contents" name="newsletter_contents"><?=stripslashes($template)?></textarea>
		</td>
    </tr>
		 
		 
		 <tr>
		   <td colspan="5" align="left" valign="middle" class="tdcolorgray" ></td>
    </tr>
		 <tr>
		  <td colspan="5" align="left" valign="middle" class="tdcolorgray" ><table width="70%" border="0" class="listingtable">
             <tr >
               <td colspan="3" class="helpmsgtd" align="left"><div class="helpmsg_divcls"><?=get_help_messages('NOTIFICATION_CODE_REPLACE')?></div></td>
             </tr>
             <tr class="listingtableheader">
               <td width="17%"><div align="left"><strong>&nbsp; Code</strong></div></td>
               <td width="5%">&nbsp;</td>
               <td width="78%"><div align="left"><strong>&nbsp; Description</strong></div></td>
             </tr>
             <?PHP 
			 	foreach($notifycode AS $key=>$val) {
			 ?>
             <tr class="listingtablestyleB">
               <td align="left" > &nbsp; <?PHP echo $val; ?></td>
               <td>=&gt;</td>
               <td align="left">&nbsp; <?PHP echo $key; ?></td>
             </tr>
             <?PHP } ?>
           </table></td>
    </tr>
	</table>
	</div>
	</td>
	</tr>
		 <tr>
          <td align="left" valign="middle" class="tdcolorgray" colspan="5" >
		  <div class="editarea_div">
		  <table  border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td width="100%" align="right" valign="middle">		   
				<input type="hidden" name="newsletter_id" id="newsletter_id" value="<?=$newsletter_id?>" />
				<input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
				<input type="hidden" name="pass_start" id="start" value="<?=$_REQUEST['start']?>" />
				<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
				<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
				<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
				<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
				<input type="hidden" name="fpurpose" id="fpurpose"  />
				<input type="hidden" name="retdiv_id" id="retdiv_id" value="maincontent" />
				<input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
				<input name="Submit" type="button" class="red" value=" Continue " onclick="valform(frmEditNewsletter)" />
				</td>
			</tr>
		</table>
		</div>
		</td>
        </tr>
		 
      </table>
</form>	  

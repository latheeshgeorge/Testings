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
$help_msg = get_help_messages('EDIT_NEWSLETTER_MESS1');
$newsletter_id=($_REQUEST['newsletter_id']?$_REQUEST['newsletter_id']:$_REQUEST['checkbox'][0]);

$sql="SELECT newsletter_id,newsletter_title,newsletter_contents,newsletter_createdate,newsletter_lastupdate 
			FROM newsletters 
				WHERE sites_site_id=$ecom_siteid AND newsletter_id=".$newsletter_id;
$res=$db->query($sql);
$row=$db->fetch_array($res);

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
	var newsletter_id										= '<?php echo $newsletter_id?>';
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
	Handlewith_Ajax('services/newsletter.php','fpurpose='+fpurpose+'&cur_newsletterid='+newsletter_id);
}
function call_ajax_changestatuspagesall(mod,checkboxname)
{ 
	var atleastone 			= 0;
	var newsletter_id			= '<?php echo $newsletter_id?>';
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
	var newsletter_id			= '<?php echo $newsletter_id?>';
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
			
			Handlewith_Ajax('services/newsletter.php','fpurpose='+fpurpose+'&cur_newsletter_id='+newsletter_id+'&del_ids='+del_ids+'&'+qrystr);
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
	var answer = confirm(" If you Change Template You will Lost Previous Data. Do you want to Continue ? ")
	if(answer)
		document.getElementById('newstemp').style.display = '';
	} else {
		document.getElementById('newstemp').style.display = 'none';
	}
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
<form name='frmEditNewsletter' action='home.php?request=newsletter'  method="post" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="5" align="left" valign="middle" class="treemenutd"><a href="home.php?request=newsletter&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&search_name=<?=$_REQUEST['pass_search_name']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>">List Newsletter</a> &gt;&gt; Edit News lettter</td>
        </tr>
        <tr>
          <td colspan="5" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=$help_msg ?></div></td>
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
	
		 <tr id="newstemp" <?PHP if(!isset($_REQUEST['template_name'])) { ?> style="display:none;" <?PHP } ?>>
           <td align="left" valign="middle" class="tdcolorgray" >News Letter Template </td>
		   <td colspan="4" align="left" valign="middle" class="tdcolorgray"><?PHP
		    $template_arr = array();
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
			echo generateselectbox('template_name',$template_arr,$_REQUEST['template_name'],'',$onchange);		
				
		  ?>           </td>
    </tr>
		<?PHP if(!isset($_REQUEST['template_name'])) { ?>
		 <tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;&nbsp;           </td>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td colspan="2" align="right" valign="middle" class="tdcolorgray" >Do You want to change Template </td>
          <td align="left" valign="middle" class="tdcolorgray" >
		  <input type="checkbox" name="chk_temp" value="1" onclick="temp_check(document.frmEditNewsletter)" />		  </td>
	    </tr>
		<? } ?>
        <tr>
          <td width="18%" align="left" valign="middle" class="tdcolorgray" >News Letter  Title  <span class="redtext">*</span> </td>
          <td align="left" valign="middle" class="tdcolorgray"><input name="newsletter_title" type="text" id="newsletter_title" value="<?=$row['newsletter_title']?>" size="40" /></td>
          <td width="29%" colspan="2" align="right" valign="middle" class="tdcolorgray">Created Date </td>
          <td width="27%" align="left" valign="middle" class="tdcolorgray">
		  <?=dateFormat($row['newsletter_createdate'],'datetime')?></td>
        </tr>
		 <tr>
           <td align="left" valign="middle" class="tdcolorgray" >News LetterContents <span class="redtext">*</span></td>
		   <td align="left" valign="middle" class="tdcolorgray"></td>
           <td colspan="2" align="right" valign="middle" class="tdcolorgray" >Last Updated Date </td>
           <td align="left" valign="middle" class="tdcolorgray"><?=dateFormat($row['newsletter_lastupdate'],'datetime');?></td>
    </tr>
		 
		 <tr  >
		   <td colspan="3" align="left" valign="top" class="tdcolorgray" style="padding-left:25px;" ><?php 
		   if($_REQUEST['template_name']>0) {
			   $tempsql = "SELECT newstemplate_template 
								  FROM newsletter_template 
									   WHERE newstemplate_id='".$_REQUEST['template_name']."'";
			   $tempres = $db->query($tempsql);
			   $temprow = $db->fetch_array($tempres);
			   $template = $temprow['newstemplate_template'];						   
		   } else {
		   	   $template = $row['newsletter_contents'];
		   }
						
						include_once("classes/fckeditor.php");
						$editor 			= new FCKeditor('newsletter_contents') ;
						$editor->BasePath 	= '/console/js/FCKeditor/';
						$editor->Width 		= '650';
						$editor->Height 	= '400';
						$editor->ToolbarSet = 'BshopWithImages';
						$editor->Value 		= stripslashes($template);
						$editor->Create() ;
				       
		?></td>
           <td colspan="2" align="left" valign="top" class="tdcolorgray" style="padding-left:25px;" ><table width="100%" border="0">
             <tr>
               <td>&nbsp;</td>
               <td>&nbsp;</td>
             </tr>
             <tr>
               <td><div align="left"><strong>Code</strong></div></td>
               <td><div align="left"><strong>Description</strong></div></td>
             </tr>
             <?PHP 
			 	foreach($templatecode AS $key=>$val) {
			 ?>
             <tr>
               <td><?PHP echo $key; ?></td>
               <td><?PHP echo $val; ?></td>
             </tr>
             <?PHP } ?>
           </table></td>
    </tr>
		 
		 
		 <tr>
		   <td align="left" valign="middle" class="tdcolorgray" ></td>
		   <td colspan="4" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
    </tr>
		 <tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="26%" align="right" valign="middle" class="tdcolorgray">
		   
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
		  <input name="Submit" type="button" class="red" value="Submit" onclick="valform(frmEditNewsletter)" />		 </td>
          <td colspan="3" align="left" valign="middle" class="tdcolorgray">
           <input name="Submit" type="button" class="red" value=" Preview " onclick="window.open('mailpreview.php?newsletter_id=<?=$newsletter_id?>','popup','height=500,width=500,resizable=yes,scrollbars=yes')" />	</td>
        </tr>
		 <tr>
          <td width="18%" align="left" valign="middle" class="tdcolorgray" > </td>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
       
        <tr>
          <td colspan="5" align="left" valign="middle" class="tdcolorgray" >  
		  <?php
		 // Check whether Products are added to this Advert
			$sql_product_in_adverts = "SELECT products_product_id FROM newsletter_products
						 WHERE newsletters_newsletter_id=$newsletter_id";
			$ret_product_in_adverts = $db->query($sql_product_in_adverts);		
		 
		 ?>
		 <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="3%" class="seperationtd"><img id="cat_imgtag" src="images/plus.gif" border="0" onclick="handle_expansionall(this,'products')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd"> Products for which  this Advert will be displayed</td>
            </tr>
          </table></td>
        </tr>
		<tr>
          <td colspan="5" align="right" valign="middle" class="tdcolorgray" >
		  <input name="Addmore" type="button" class="red" id="Addmore" value="Assign More" onclick="document.frmEditNewsletter.fpurpose.value='list_assign_products';document.frmEditNewsletter.submit();" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_ASS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				if ($db->num_rows($ret_product_in_adverts))
				{
				?>
					<div id="productsunassign_div" class="unassign_div" style="display:none">
					<!--Change Hidden Status to -->
					<?php
						/*$products_status = array(0=>'No',1=>'Yes');
						echo generateselectbox('product_chstatus',$products_status,0);
*/					?>
					<!--<input name="product_chstatus" type="button" class="red" id="product_chstatus" value="Change" onclick="call_ajax_changestatuspagesall('product','checkboxproducts[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_ASS_PROD_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
-->								
					&nbsp;&nbsp;&nbsp;<input name="products_unassign" type="button" class="red" id="products_unassign" value="Un Assign" onclick="call_ajax_deleteall('product','checkboxproducts[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_UNASS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
				<?php
				}				
				?></td>
        </tr>
		<tr id="products_tr">
			<td align="right" colspan="5" class="tdcolorgray_buttons">
			<div id="products_div" style="text-align:center">			</div>			</td>
		</tr>
		 <tr>
          <td colspan="5" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
        </tr>
		
		 <tr>
          <td colspan="5" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
        </tr>
			
	
		<tr>
          <td colspan="5" align="left" valign="middle" class="tdcolorgray" >&nbsp; </td>
        </tr><tr>
          <td colspan="5" align="left" valign="middle" class="tdcolorgray" >&nbsp; </td>
        </tr>
      </table>
</form>	  
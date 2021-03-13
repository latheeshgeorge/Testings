<?php
	/*#################################################################
	# Script Name 	: edit_product_tab.php
	# Description 	: Page for editing Product tab
	# Coded by 		: Sny
	# Created on	: 02-Jul-2007
	# Modified by	: Sny
	# Modified On	: 24-Jul-2007
	#################################################################*/
//Define constants for this page
$page_type = 'Products';
$help_msg = get_help_messages('EDIT_PROD_EDIT_PROD_TAB');

// Get the name of current product
$sql_prod = "SELECT product_name FROM products WHERE product_id=".$_REQUEST['checkbox'][0]. " AND sites_site_id = $ecom_siteid LIMIT 1";
$ret_prod = $db->query($sql_prod);
if ($db->num_rows($ret_prod))
{
	$row_prod = $db->fetch_array($ret_prod);
	$showprodname = stripslashes($row_prod['product_name']);
}
else
	exit;
// Get the details of tab being editing
$sql_tab = "SELECT * FROM product_tabs WHERE tab_id=$edit_id";
$ret_tab = $db->query($sql_tab);
if ($db->num_rows($ret_tab))
{
	$row_tab = $db->fetch_array($ret_tab);
}
?>	
<script language="javascript" type="text/javascript">
function valforms(frm)
{
	var atleastone = false;
	fieldRequired = Array('tab_title');
	fieldDescription = Array('Tab Title');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('tab_order');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
	{
		show_procession();
		return true;	
	}	
	else
	{
		return false;
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
				case 'tabimg_div':
					if(document.getElementById('tabimg_norec'))
					{
						if(document.getElementById('tabimg_norec').value==1)
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
	var tab_id											= '<?php echo $_REQUEST['edit_id'];?>';
	var cat_orders										= '';
	var fpurpose										= '';
	var retdivid										= '';
	var moredivid										= '';
	switch(mod)
	{
		case 'tabimg': // Case of product variables
			retdivid   	= 'tabimg_div';
			moredivid	= 'tabimgunassign_div';
			fpurpose	= 'list_tabimg';
		break;
	};
	document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */	
	document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
	retobj 											= eval("document.getElementById('"+retdivid+"')");
	retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	var qrystr										= '';
	/* Calling the ajax function */
	Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&'+qrystr+'&cur_tabid='+tab_id);
}	
function handle_expansionall(imgobj,mod)
{
	var src 			= imgobj.src;
	var retindx 		= src.search('plus.gif');
	switch(mod)
	{
		case 'tabimg': /* Case of tab images*/
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('tabimg_tr'))
					document.getElementById('tabimg_tr').style.display = '';
				call_ajax_showlistall('tabimg');
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('tabimg_tr'))
					document.getElementById('tabimg_tr').style.display = 'none';
				if(document.getElementById('tabimgunassign_div'))
					document.getElementById('tabimgunassign_div').style.display = 'none';
			}	
		break;
	};
}
function call_ajax_saveimagedetails(checkboxname)
{
	var atleastone 			= 0;
	var prodid				= '<?php echo $_REQUEST['checkbox'][0]?>';
	var editid				= '<?php echo $_REQUEST['edit_id']?>';
	var ch_ids 				= '';
	var ch_variable			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= 'tabimg_div';
	var moredivid			= 'tabimgunassign_div';
	var fpurpose			= 'save_tabimagedetails';
	var ch_order			= '';
	var ch_title			= '';
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditProductTab.elements.length;i++)
	{
		if (document.frmEditProductTab.elements[i].type =='checkbox' && document.frmEditProductTab.elements[i].name== checkboxname)
		{

			if (document.frmEditProductTab.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmEditProductTab.elements[i].value;
				 
				 obj1 = eval("document.getElementById('img_ord_"+document.frmEditProductTab.elements[i].value+"')");
				 obj2 = eval("document.getElementById('img_title_"+document.frmEditProductTab.elements[i].value+"')");
				 
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
			document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
			retobj 										= eval("document.getElementById('"+retdivid+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&edit_id='+editid+'&ch_order='+ch_order+'&ch_title='+ch_title+'&ch_ids='+ch_ids+'&'+qrystr);
		}	
	}
}
function call_ajax_deleteall(checkboxname)
{
	var atleastone 			= 0;
	var editid				= '<?php echo $_REQUEST['edit_id']?>';
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditProductTab.elements.length;i++)
	{
		if (document.frmEditProductTab.elements[i].type =='checkbox' && document.frmEditProductTab.elements[i].name==checkboxname)
		{

			if (document.frmEditProductTab.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmEditProductTab.elements[i].value;
			}	
		}
	}
	
	atleastmsg 	= 'Please select the tab image(s) to be unassigned.';
	confirmmsg 	= 'Are you sure you want to unassign the selected Image(s)?';
	retdivid   	= 'tabimg_div';
	moredivid	= 'tabimgunassign_div';
	fpurpose	= 'unassign_tabimagedetails';
	if (atleastone==0)
	{
		alert(atleastmsg);
	}
	else
	{
		if(confirm(confirmmsg))
		{
			document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
			document.getElementById('retdiv_more').value= moredivid;/* Name of div to show the result */
			retobj 										= eval("document.getElementById('"+retdivid+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/products.php','fpurpose='+fpurpose+'&edit_id='+editid+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
</script>
<form name='frmEditProductTab' action='home.php?request=products' method="post" onsubmit="return valforms(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=products&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&start=<?php echo $_REQUEST['start']?>&pg=<?php echo $_REQUEST['pg']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>">List Products</a>  <a href="home.php?request=products&amp;fpurpose=edit&amp;checkbox[0]=<?php echo $_REQUEST['checkbox'][0]?>&amp;productname=<?php echo $_REQUEST['productname']?>&amp;manufactureid=<?php echo $_REQUEST['manufactureid']?>&amp;categoryid=<?php echo $_REQUEST['categoryid']?>&amp;vendorid=<?php echo $_REQUEST['vendorid']?>&amp;rprice_from=<?php echo $_REQUEST['rprice_from']?>&amp;rprice_to=<?php echo $_REQUEST['rprice_to']?>&amp;cprice_from=<?php echo $_REQUEST['cprice_from']?>&amp;cprice_to=<?php echo $_REQUEST['cprice_to']?>&amp;discount=<?php echo $_REQUEST['discount']?>&amp;discountas=<?php echo $_REQUEST['discountas']?>&amp;bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&amp;stockatleast=<?php echo $_REQUEST['stockatleast']?>&amp;preorder=<?php echo $_REQUEST['preorder']?>&amp;prodhidden=<?php echo $_REQUEST['prodhidden']?>&amp;start=<?php echo $_REQUEST['start']?>&amp;pg=<?php echo $_REQUEST['pg']?>&amp;records_per_page=<?php echo $_REQUEST['records_per_page']?>&amp;sort_by=<?php echo $sort_by?>&amp;sort_order=<?php echo $sort_order?>&curtab=<?php echo $_REQUEST['curtab']?>">Edit Product</a><span> Edit Product Tab for &quot;<?php echo $showprodname?>&quot;</span></div> </td>
        </tr>
        <tr>
		  <td colspan="4" align="left" valign="middle" class="helpmsgtd_main">
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
				  <td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
				 </tr>
		 <?php
		 	}
		 ?>
         <tr>
           <td colspan="4" align="left" valign="top" class="tdcolorgraynormal" >
		   <div class="editarea_div">
		   <table width="100%" border="0" cellspacing="1" cellpadding="1">
             <tr>
               <td width="15%" align="left">Tab Title <span class="redtext">*</span></td>
               <td width="34%" align="left"><input name="tab_title" type="text" id="tab_title" value="<?php echo stripslashes($row_tab['tab_title'])?>" size="30" /></td>
               <td width="10%" align="left">Hide</td>
               <td width="41%" align="left"><input type="radio" name="var_hide" value="1" <?php echo ($row_tab['tab_hide']==1)?'checked="checked"':''?> />
Yes
  <input name="var_hide" type="radio" value="0" <?php echo ($row_tab['tab_hide']==0)?'checked="checked"':''?> />
No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_DESC_EDTAB')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
             </tr>
             <tr>
               <td align="left">Order</td>
               <td align="left"><input name="tab_order" type="text" size="5" value="<?php echo $row_tab['tab_order']?>"/></td>
               <td align="left">&nbsp;</td>
               <td align="left">&nbsp;</td>
             </tr>
             <tr>
               <td align="left" valign="top">Description </td>
               <td colspan="3" align="left" valign="top">
			   <?php
						$editor_elements = "tab_content";
						include_once(ORG_DOCROOT."/console/js/tinymce.php");
						/*$editor 			= new FCKeditor('tab_content') ;
						$editor->BasePath 	= '/console/js/FCKeditor/';
						$editor->Width 		= '650';
						$editor->Height 	= '300';
						$editor->ToolbarSet = 'BshopWithImages';
						$editor->Value 		= stripslashes($row_tab['tab_content']);
						$editor->Create() ;*/
				       
				?>			
				<textarea style="height:300px; width:650px" id="tab_content" name="tab_content"><?=stripslashes($row_tab['tab_content'])?></textarea>
				   </td>
             </tr>
           </table>
		   </div>
		   </td>
         </tr>
         
         <tr>
           <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
         </tr>
        <tr>
          <td colspan="4" align="right" valign="middle" class="tdcolorgray">
			<div class="editarea_div">
		  	<input type="hidden" name="productname" id="productname" value="<?=$_REQUEST['productname']?>" />
		  	<input type="hidden" name="manufactureid" id="manufactureid" value="<?=$_REQUEST['manufactureid']?>" />
		  	<input type="hidden" name="categoryid" id="categoryid" value="<?=$_REQUEST['categoryid']?>" />
		  	<input type="hidden" name="vendorid" id="vendorid" value="<?=$_REQUEST['vendorid']?>" />
			<input type="hidden" name="rprice_from" id="rprice_from" value="<?=$_REQUEST['rprice_from']?>" />
			<input type="hidden" name="rprice_to" id="rprice_to" value="<?=$_REQUEST['rprice_to']?>" />
			<input type="hidden" name="cprice_from" id="cprice_from" value="<?=$_REQUEST['cprice_from']?>" />
			<input type="hidden" name="cprice_to" id="cprice_to" value="<?=$_REQUEST['cprice_to']?>" />
			<input type="hidden" name="discount" id="discount" value="<?=$_REQUEST['discount']?>" />
			<input type="hidden" name="discountas" id="discountas" value="<?=$_REQUEST['discountas']?>" />
			<input type="hidden" name="bulkdiscount" id="bulkdiscount" value="<?=$_REQUEST['bulkdiscount']?>" />
			<input type="hidden" name="stockatleast" id="stockatleast" value="<?=$_REQUEST['stockatleast']?>" />
			<input type="hidden" name="preorder" id="preorder" value="<?=$_REQUEST['preorder']?>" />
			<input type="hidden" name="prodhidden" id="prodhidden" value="<?=$_REQUEST['prodhidden']?>" />
			<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
			<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
			<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
			<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
			<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
			<input type="hidden" name="parent_id" id="parent_id" value="<?=$_REQUEST['parent_id']?>" />
			<input type="hidden" name="edit_id" id="edit_id" value="<?=$_REQUEST['edit_id']?>" />
			<input type="hidden" name="checkbox[0]" id="checkbox[0]" value="<? echo $_REQUEST['checkbox'][0];?>" />
			<input type="hidden" name="fpurpose" id="fpurpose" value="save_edittab" />
			<input type="hidden" name="curtab" id="curtab" value="<?=$_REQUEST['curtab']?>" />
			<input type="hidden" name="src_page" id="src_page" value="tab" />
			<input type="hidden" name="src_id" id="src_id" value="<?=$_REQUEST['edit_id']?>" />
			<input type="hidden" name="retdiv_id" id="retdiv_id" value="" />
			<input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
			<input name="prodtab_Submit" type="submit" class="red" value="Save" />
			</div>
			</td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td colspan="3" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
		</table>
		<div class="listingarea_div">
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
       <tr>
          <td colspan="4" align="left" valign="bottom">
		 
            <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="3%" class="seperationtd"><img id="tab_imgtag" src="images/plus.gif" border="0" onclick="handle_expansionall(this,'tabimg')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd">Tab Images </td>
            </tr>
          </table>
		  </td>
        </tr>
        <tr >
		   <?php
			// Get the list of tabs for this product
			$sql_tab = "SELECT id FROM images_product_tab 
						 WHERE product_tabs_tab_id=$edit_id LIMIT 1";
			$ret_tab= $db->query($sql_tab);
		 ?>
		 <tr>
		  <td align="right" colspan="4" class="tdcolorgray_buttons">
				<input name="Assign_Image" type="button" class="red" id="Assign_Image" value="Assign More" onclick="document.frmEditProductTab.fpurpose.value='add_tabimg';document.frmEditProductTab.submit();" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_DESC_ASSIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				if ($db->num_rows($ret_tab))
				{
				?>
					<div id="tabimgunassign_div" class="unassign_div" style="display:none">
					<input name="prodimg_save" type="button" class="red" id="prodimg_save" value="Save Details" onclick="call_ajax_saveimagedetails('checkbox_img[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_DESC_DETSAVE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
					
					&nbsp;&nbsp;&nbsp;<input name="prodtab_unassign" type="button" class="red" id="prodtab_unassign" value="Un assign" onclick="call_ajax_deleteall('checkbox_img[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_DESC_UNASSIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>				</div>	
				<?php
				}				
				?>		  </td>
        </tr>
		<tr id="tabimg_tr">
			<td align="right" colspan="4" class="tdcolorgray_buttons">
				<div id="tabimg_div" style="text-align:center"></div>
			</td>
		</tr>
      </table>
	  </tr>
</form>	  


<?php
	/*#################################################################
	# Script Name 	: list_todo.php
	# Description 	: Page for listing product enquire
	# Coded by 		: Latheesh
	# Created on	: 03-feb-2013
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name='facebook_tab_content';
$page_type='Facebook Buisiness Tab';
if($_REQUEST['fpurpose']=='edit')
	$help_msg = get_help_messages('LIST_FACEBOOK_MESS_EDIT');
else
	$help_msg = get_help_messages('LIST_FACEBOOK_MESS_PREV');
$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';

$fb_id 	  = ($_REQUEST['fbtab_id'])?$_REQUEST['fbtab_id']:$_REQUEST['checkbox'][0];
$sql  		=   "SELECT * FROM facebook_tab_content WHERE id=$fb_id AND sites_site_id = $ecom_siteid LIMIT 1";
$ret_sql    = $db->query($sql);

if($db->num_rows($ret_sql)>0)
{
$row_fb 	= $db->fetch_array($ret_sql ); 
$template   = $row_fb['fb_content'];
}
$show_preview   = false;
if($_REQUEST['curtab']=='preview_tab_td' || $row_fb['fb_preview_content']!='')
{ 
	$show_preview = true;
}	

	$editor_elements = "fb_content,fb_review_content";
	include_once(ORG_DOCROOT."/console/js/tinymce.php");
	$css_filename = "http://$ecom_hostname/console/css/editor.css";
?>
<script language="javascript" type="text/javascript">
function valform(frm,mod)
{ 
	fieldRequired = Array('fb_subject');
	fieldDescription = Array('Title');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		show_processing();
		if(mod=='main')
		{
			frm.fpurpose.value = 'update';
			var chids ='';
			var chorder ='';
			var ord = '';
		    var len = document.frmfacebookedit.elements.length;    

			for(i=0;i<len;i++)
				{
					if(document.frmfacebookedit.elements[i].type=='hidden' && document.frmfacebookedit.elements[i].name.substr(0,15)=='sel_product_id_')
					{ 
					  if(chids!='')
					  {
						  chids +='~';
					  }		  
					  chids +=document.frmfacebookedit.elements[i].value;	
					  if(chorder!='')
					  {
						  chorder +='~';
					  }	
					  orderbox = 'product_order_';
				      obj = eval("document.getElementById('"+orderbox+document.frmfacebookedit.elements[i].value+"')");
					  chorder +=obj.value;	
					}
					
				}
				document.frmfacebookedit.chorder_save.value = chorder;
				document.frmfacebookedit.chids_save.value = chids;
				document.frmfacebookedit.mod.value = 'main';
	    }
	    else if(mod=='preview')
	    {
			frm.fpurpose.value = 'preview_update';
			document.frmfacebookedit.mod.value = 'list';
		}
		frm.submit();
		
	} else {
		return false;
	}
}
function handle_tabs(id,mod)
{   
	var atleastone 							= 0;
	var ftab_id							= '<?php echo $fb_id?>';
	var cat_orders							= '';
	var fpurpose							= '';
	var retdivid							= '';
	var moredivid							= '';
	var search_name								='<?php echo $_REQUEST['search_name']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs									= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var showinall							= '<?php echo $showinallpages?>';
	var qrystr									= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&curtab='+curtab+'&showinall='+showinall;
	
	
	switch(mod)
	{
		case 'fbmain_info':
		     obj = eval ("document.getElementById('main_tab_td')");
	         obj.className = 'toptab_sel';
	         if(document.getElementById('preview_tab_td'))
	         {
	          obj1 = eval ("document.getElementById('preview_tab_td')");
	         obj1.className = 'toptab';
		     }
			//fpurpose ='list_combo_maininfo';
			document.frmfacebookedit.fpurpose.value = 'show_maininfo';
			document.frmfacebookedit.submit();
			return;
		break;
		case 'fb_preview':
		     obj = eval ("document.getElementById('preview_tab_td')");
	         obj.className = 'toptab_sel';
	         obj1 = eval ("document.getElementById('main_tab_td')");
	         obj1.className = 'toptab';
			//fpurpose ='list_combo_maininfo';
			document.frmfacebookedit.fpurpose.value = 'preview';
			document.frmfacebookedit.submit();
			return;
		break;
		
	}	
}
function call_ajax_assign(invid)
{
	var qrystr														= '';
	var fpurpose													= 'show_product_popup';
	var len = document.frmfacebookedit.elements.length;    
	var chids = '';
	for(i=0;i<len;i++)
	{
		if(document.frmfacebookedit.elements[i].type=='hidden' && document.frmfacebookedit.elements[i].name.substr(0,15)=='sel_product_id_')
		{
		  if(chids!='')
		  {
			  chids +='~';
		  }
		  
		  chids +=document.frmfacebookedit.elements[i].value;	
		}
	}
	qrystr +='chids='+chids;
	document.getElementById('retdiv_id').value 						= 'moveto_showproduct_div';
	obj																= eval("document.getElementById('moveto_showproduct_div')");
	obj.innerHTML 													= '<center><img src="images/loading.gif" alt="Loading"></center>';
	Handlewith_Ajax('services/facebook_tab_content.php','fpurpose='+fpurpose+'&cur_fbid='+invid+'&'+qrystr);
    showme('#moveto_showproduct_div');
}
function ajax_search(invid)
{
    var qrystr														= '';
	var fpurpose													= 'show_search_res';
	var prod_name ='';
	var cat_id    ='';
	var rec_per_page ='';
	prod_name 		=  document.frmfacebookedit.productname_link.value;
	cat_id 	  		=  document.frmfacebookedit.categoryid_link.value;
	rec_per_page 	=  document.frmfacebookedit.perpage_pop.value;
	var len = document.frmfacebookedit.elements.length;    
	var chids = '';
	for(i=0;i<len;i++)
	{
		if(document.frmfacebookedit.elements[i].type=='hidden' && document.frmfacebookedit.elements[i].name.substr(0,15)=='sel_product_id_')
		{
		  if(chids!='')
		  {
			  chids +='~';
		  }
		  
		  chids +=document.frmfacebookedit.elements[i].value;	
		}
	}
		qrystr +='chids='+chids+'&productname_link='+prod_name+'&categoryid_link='+cat_id+'&perpage_pop='+rec_per_page;

	document.getElementById('retdiv_id').value 						= 'moveto_showproduct_div';
	obj																= eval("document.getElementById('moveto_showproduct_div')");
	obj.innerHTML 													= '<center><img src="images/loading.gif" alt="Loading"></center>';
	Handlewith_Ajax('services/facebook_tab_content.php','fpurpose='+fpurpose+'&cur_fbid='+invid+'&'+qrystr);
    //showme('#moveto_showproduct_div');
}
function call_ajax_page(invid,pg)
{
    var qrystr														= '';
	var fpurpose													= 'show_search_res';
	var prod_name ='';
	var cat_id    ='';
	var rec_per_page ='';
	prod_name 		=  document.frmfacebookedit.productname_link.value;
	cat_id 	  		=  document.frmfacebookedit.categoryid_link.value;
	rec_per_page 	=  document.frmfacebookedit.perpage_pop.value;
	var len = document.frmfacebookedit.elements.length;    
	var chids = '';
	for(i=0;i<len;i++)
	{
		if(document.frmfacebookedit.elements[i].type=='hidden' && document.frmfacebookedit.elements[i].name.substr(0,15)=='sel_product_id_')
		{
		  if(chids!='')
		  {
			  chids +='~';
		  }
		  
		  chids +=document.frmfacebookedit.elements[i].value;	
		}
	}
		qrystr +='chids='+chids+'&productname_link='+prod_name+'&categoryid_link='+cat_id+'&perpage_pop='+rec_per_page+'&pg='+pg;

	document.getElementById('retdiv_id').value 						= 'moveto_showproduct_div';
	obj																= eval("document.getElementById('moveto_showproduct_div')");
	obj.innerHTML 													= '<center><img src="images/loading.gif" alt="Loading"></center>';
	Handlewith_Ajax('services/facebook_tab_content.php','fpurpose='+fpurpose+'&cur_fbid='+invid+'&'+qrystr);
    //showme('#moveto_showproduct_div');
}
function call_ajax_unassign(invid)
{
	var qrystr														= '';
	var fpurpose													= 'unassign_product';
	var len = document.frmfacebookedit.elements.length;    
	var chids = '';
	var chrmids = '';
	var atleastone = 0;
	for(i=0;i<len;i++)
	{
		if(document.frmfacebookedit.elements[i].type=='hidden' && document.frmfacebookedit.elements[i].name.substr(0,15)=='sel_product_id_')
		{ 
		  if(chids!='')
		  {
			  chids +='~';
		  }		  
		  chids +=document.frmfacebookedit.elements[i].value;	
		}
		if(document.frmfacebookedit.elements[i].type=='checkbox' && document.frmfacebookedit.elements[i].name=='checkbox_prod[]')
		{
			if (document.frmfacebookedit.elements[i].checked==true)
			{
				atleastone = 1;
			if(chrmids!='')
			{
				chrmids +='~';
			}
			chrmids +=document.frmfacebookedit.elements[i].value;	
			}
		} 
	}
		if (atleastone==0)
		{
		alert('Please select atleast one product to unassign');
		}
		else
		{
		if(confirm('Are you sure you want to remove selected product(s)?'))
		{
		qrystr +='chids='+chids+'&chrmids='+chrmids;
		document.getElementById('retdiv_id').value 						= 'select_showproduct_div';
		obj																= eval("document.getElementById('moveto_showproduct_div')");
		obj.innerHTML 													= '<center><img src="images/loading.gif" alt="Loading"></center>';
		Handlewith_Ajax('services/facebook_tab_content.php','fpurpose='+fpurpose+'&cur_fbid='+invid+'&'+qrystr);
		}
	}
     //showme('#moveto_showproduct_div');
}
function assign_selected(invid)
{
	var ch_ids     ='';
	var qrystr     = '';
	var atleastone = 0;
	var fpurpose													= 'assign_product_selected';
	var defval;
	var atleastmsg = 'Please select atleast one product';
	for(i=0;i<document.frmfacebookedit.elements.length;i++)
	{
	   
	   if ((document.frmfacebookedit.elements[i].type =='checkbox' || document.frmfacebookedit.elements[i].type =='hidden') )
		{			
			if(document.frmfacebookedit.elements[i].name== 'checkbox_link[]')
			{
				if (document.frmfacebookedit.elements[i].checked==true)
				{
					atleastone ++;
					if (ch_ids!='')
						ch_ids += '~';
					 ch_ids += document.frmfacebookedit.elements[i].value;
				} 
			}
			if(document.frmfacebookedit.elements[i].name== 'checkbox_prod[]')
			{
					
						if (ch_ids!='')
							ch_ids += '~';
						 ch_ids += document.frmfacebookedit.elements[i].value;				
			}
		}
	}
	if (atleastone==0)
	{
		alert(atleastmsg);
		return false;
	}
	else
	{	
			document.getElementById('popup_bg_div').style.display='none';	
			document.getElementById('retdiv_id').value 						= 'select_showproduct_div';

			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/facebook_tab_content.php','fpurpose='+fpurpose+'&cur_fbid='+invid+'&chids='+ch_ids+'&'+qrystr);
	}
   	document.getElementById('moveto_showproduct_div').style.display ='none';
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
				case 'moveto_showproduct_div':					
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
function call_cancel()
{
	document.getElementById('moveto_showproduct_div').style.display ='none';
	//document.getElementById('popup_bg_div').style.display='none';

}
jQuery.noConflict();
		var $ajax_j = jQuery; 
	     $ajax_j(function () { 
		var top = Math.max($ajax_j(window).height() / 2 - $ajax_j("#moveto_showproduct_div")[0].offsetHeight / 2, 0);
		var left = Math.max($ajax_j(window).width() / 2 - $ajax_j("#moveto_showproduct_div")[0].offsetWidth / 2, 0);
		$ajax_j("#moveto_showproduct_div").css('top', top-275 + "px");
		$ajax_j("#moveto_showproduct_div").css('right', (left-200) + "px");
		$ajax_j("#moveto_showproduct_div").css('position', 'fixed');
	});	
	function showme(id)
	{		
		$ajax_j(id).show();
	}
	function hideme(id)
	{
		$ajax_j(id).hide();
		$ajax_j(id).hide();
	}
</script>
<form name="frmfacebookedit" action="home.php?request=facebook_tab" method="post" >	
   <input type="hidden" name="chids_save" value="" />
      <input type="hidden" name="chorder_save" value="" />
  <input type="hidden" name="mod" value="" />

  <input type="hidden" name="fpurpose" value="" />
  <input type="hidden" name="request" value="facebook_tab" />
  <input type="hidden" name="fbtab_id" value="<?php echo $fb_id?>" />
  	<input type="hidden" name="retdiv_id" id="retdiv_id" value="maincontent" />
						<input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
					<div id="popup_bg_div" class="popupbg_fadclass" style="display:none" ></div>

  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="5" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=facebook_tab&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>" title="<? echo $row['fb_subject']?>" class="edittextlink" onclick="show_processing()"> List  Facebook Template</a><span> Edit  Facebook Template</span></div></td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="5">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<tr>
          <td colspan="5" align="center" valign="middle" >
          
		  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabmenu_x">
		  <tr>  
			<td  align="left" onClick="handle_tabs('main_tab_td','fbmain_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Main Info</span></td>
			<?php
			if($show_preview==true)
			{
			?>
			<td  align="left" onClick="handle_tabs('preview_tab_td','fb_preview')" class="<?php if($curtab=='preview_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="preview_tab_td"><span>Preview</span></td>
            <?php
			}
            ?>
			<td width="90%" align="left">&nbsp;</td>
				</tr> 
		</table>		  
		</td>
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
          <td colspan="5" align="center" valign="middle" class="tdcolorgray1" > 
		  	  <div id="moveto_showproduct_div" class="processing_divcls_big_heightA" style="display:none" >afaa
	</div>
		  	 <div id='master_div'>
			<?php 
			if ($curtab=='main_tab_td')
			{
				//include_once("classes/fckeditor.php");
				show_display_maininfo($fb_id,$alert);
			}
			elseif ($curtab=='preview_tab_td')
			{
				show_display_preview($fb_id,$alert);
			}			
			?>		
		  </div>
		  </td>
        </tr>			
      </table>
        


</form>
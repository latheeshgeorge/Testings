<?php
	/*#################################################################
	# Script Name 	: edit_page.php
	# Description 	: Page for editing Static Page
	# Coded by 		: SKR
	# Created on	: 27-June-2007
	# Modified by	: SKR
	# Modified On	: 03-Aug-2007
	#################################################################*/
#Define constants for this page
$page_type = 'Static Page';
$help_msg = get_help_messages('EDIT_STAT_PAGE_MESS1');
$page_id=($_REQUEST['page_id']?$_REQUEST['page_id']:$_REQUEST['checkbox'][0]);
$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';
$sql="SELECT pname FROM static_pages WHERE sites_site_id=$ecom_siteid AND page_id=".$page_id;
$res=$db->query($sql);
if($db->num_rows($res)==0)  { echo " <font color='red'> You Are Not Authorised  </a>"; exit; }
$row=$db->fetch_array($res);
$pname  =  $row['pname'];
$editor_elements = "content";
include_once(ORG_DOCROOT."/console_js/js/tinymce.php");
?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('title');
	fieldDescription = Array('Page Title');
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
function changePageType()
{
	
	if(document.frmEditPage.page_type[0].checked==true)
	{
		document.getElementById('show_content_tr').style.display='';
		document.getElementById('show_link_tr').style.display='none';
	}
	else if(document.frmEditPage.page_type[1].checked==true)
	{
		document.getElementById('show_link_tr').style.display='';
		document.getElementById('show_content_tr').style.display='none';
	}
}

function handle_tabs(id,mod)
{
	//alert("start");
	/* SEO tab in static page starts here */
	tab_arr 								= new Array('main_tab_td','seo_tab_td');  
	/* SEO tab in static page ends here */
	var atleastone 							= 0;
	var page_id								= '<?php echo $page_id?>';	
	var page_name							= "<?php echo $pname?>";
	var fpurpose							= '';
	var retdivid							= '';
	var moredivid							= '';
	var search_name							= '<?php echo $_REQUEST['search_name']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs								= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var advert_title						= '<?php echo $advert_title; ?>';
	var qrystr								= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&curtab='+curtab+'&page_name='+page_name;
	
	//alert(qrystr);
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
	document.getElementById('pass_tab').value = '';
	//alert(mod);
	switch(mod)
	{
		/* SEO tab in static page starts here */
		case 'pagemain_info':
			document.frmEditPage.fpurpose.value = 'edit';
			document.frmEditPage.page_id.value = '<?php echo $page_id?>';
			//alert(document.frmEditPage.fpurpose.value);
			document.frmEditPage.submit();
			return;
		break;		      
		case 'pageseo_info': 
			document.frmEditPage.fpurpose.value = 'upd_page_seoinfo';
			fpurpose	= 'list_page_seoinfo';
		break; 
		/* SEO tab in static page ends here */
		
	}
	retobj 									= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';
	
	Handlewith_Ajax('services/static_page.php','fpurpose='+fpurpose+'&page_id='+page_id+'&'+qrystr);	
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
</script>
<form name='frmEditPage' action='home.php?request=stat_page' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="7" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=stat_page&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&sel_group=<?=$_REQUEST['sel_group']?>">List Static Pages</a> <span>Edit Page</span></div></td>
        </tr>
        <tr>
		  <td colspan="7" align="left" valign="middle" class="helpmsgtd_main">
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
          <td colspan="7" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
		<!-- SEO tab in static page starts here -->
		<tr>
          <td colspan="7" align="center" valign="middle" >
		  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabmenu_x">
				<tr>  
						<td  align="left" onClick="handle_tabs('main_tab_td','pagemain_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Main Info</span></td>
						<td  align="left" onClick="handle_tabs('seo_tab_td','pageseo_info')" class="<?php if($curtab=='seo_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="seo_tab_td"><span>SEO Settings </span></td>
				        <td width="99%" align="left">&nbsp;</td>
				</tr> 
		</table>	  </td>
        </tr>
		<!-- SEO tab in static page ends here -->
		
		<tr>
		<td width="100%" valign="top" colspan="7">
		<div id='master_div'>
		<?php //echo $curtab;echo "<br>";
			if ($curtab=='main_tab_td')
			{
				//include("classes/fckeditor.php");
				show_page_maininfo($page_id,$pname,$alert);
			}/* SEO tab in static page starts here */
			elseif ($curtab=='seo_tab_td')
			{
				show_page_seoinfo($page_id,$pname,$alert);
			}
			/* SEO tab in static page ends here */
		?>
		</div>
		</td>
	</tr>
    <tr>
      <td colspan="7" align="center" valign="middle" class="tdcolorgray" >
	  <div class="editarea_div">
	  	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td align="right" valign="middle" class="tdcolorgray" >
	  	<input type="hidden" name="page_id" id="page_id" value="<?=$page_id?>" />
          <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
          <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
          <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
          <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
          <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
          <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
		  <input type="hidden" name="sel_group" id="sel_group" value="<?=$_REQUEST['sel_group']?>" />
          <input type="hidden" name="fpurpose" id="fpurpose" value="update" />
		  <input type="hidden" name="pname" id="pname" value="<?=$row['pname']?>" />
          <input name="Submit" type="submit" class="red" value="Save" />
		  <!-- Button to save and return starts here -->
		  <input name="Submit" type="submit" class="red" value="Save & Return" />
		  <!-- Button to save and return ends here -->
		  <!-- SEO tab in static page starts here -->
		  <input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
		  <input type="hidden" name="pass_tab" id="pass_tab" value="" />
		  <!-- SEO tab in static page ends here -->
		  </td>
		  </tr>
		  </table>
		 </div>
		 </td>
    </tr>
  </table>
</form>	  


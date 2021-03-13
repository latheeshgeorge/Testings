<?php
	/*#################################################################
	# Script Name 	: pricepromise_enquiries_details.php
	# Description 	: Page to view the details of price promise enquiries
	# Coded by 		: Latheesh
	# Created on	: 03-Sep-2007
	# Modified by	: Sny
	# Modified On	: 20-Apr-2010
	#################################################################*/
#Define constants for this page
$table_name 	= 'pricepromise_checkoutfields a';
$page_type 		= 'Pricepromise';
$help_msg		= get_help_messages('EDIT_PRICEPROMISE_MESS1');
$prom_id		= ($_REQUEST['prom_id']?$_REQUEST['prom_id']:$_REQUEST['checkbox'][0]);
$product_id		= $_REQUEST['product_id'];
// Updating the status to Read in case if the status is New
$update_sql 	= "UPDATE 
						pricepromise 
					SET 
						prom_status = 'Read' 
					WHERE 
						prom_id = $prom_id 
						AND sites_site_id = $ecom_siteid 
						AND prom_status = 'New' 
					LIMIT 
						1";
$db->query($update_sql);
if($curtab=='')
{
	$curtab			= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';
}
// Get the number of new posts in current price promise
$sql_newpost 		= "SELECT count(post_id) as totcnt 
						FROM 
							pricepromise_post 
						WHERE 
							pricepromise_prom_id = $prom_id 
							AND post_status = 'New'";
$ret_newpost 		= $db->query($sql_newpost);
list($new_posts) 	= $db->fetch_array($ret_newpost);
$new_cnt = $new_posts;
if($new_posts>0)
	$new_posts = '('.$new_posts.')';
else
	$new_posts = '';
?>
<script language="javascript" type="text/javascript">
function handle_var(imgobj,id)
{
	obj = eval("document.getElementById('"+id+"')");
	if (obj)
	{
		if (obj.style.display=='none')
		{
			obj.style.display = '';
			imgobj.src = 'images/down_arr.gif';
		}
		else
		{
			obj.style.display = 'none';
			imgobj.src = 'images/right_arr.gif';
		}	
	}
}

function handle_tabs(id,mod)
{ 
	tab_arr 				= new Array('main_tab_td','postmenu_tab_td','posts_tab_td','order_tab_td');
	var atleastone 			= 0;
	var prod_id				= '<?php echo $product_id?>';
	var prom_id 			= '<?php echo $prom_id?>';
	var fpurpose			= '';
	var retdiv_id			= '';
	var sortby				= '<?php echo $sort_by?>';
	var sortorder			= '<?php echo $sort_order?>';
	var recs				= '<?php echo $records_per_page?>';
	var start				= '<?php echo $start?>';
	var pg					= '<?php echo $pg?>';
	var curtab				= '<?php echo $curtab?>';
	var qrystr				= '';
	if(document.getElementById('errormsg_tr'))
	{
		if(document.getElementById('errormsg_tr').style.display == '')
			document.getElementById('errormsg_tr').style.display = 'none';
	}
	//var qrystr			= 'pass_group_name='+customergroupname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&curtab='+curtab;
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
		case 'querymain_info':
			fpurpose ='list_pricequery_details';
			document.frmAddPricepromise.selected_tab.value = 'main_tab_td';
		break;
		case 'post': // Case of notes for queries
			fpurpose	= 'list_notesdetails';
			document.frmAddPricepromise.selected_tab.value = 'postmenu_tab_td';
		break;
		case 'qrypost': // Case of posts for queries
			fpurpose	= 'list_postdetails';
			document.frmAddPricepromise.selected_tab.value = 'posts_tab_td';
			if(document.getElementById('sel_post_stat'))
				qrystr = 'passpost_status='+document.getElementById('sel_post_stat').value;
		break;
		case 'order_list': // case of showing orders linked with current price promise
			fpurpose	= 'list_orderdetails';
			document.frmAddPricepromise.selected_tab.value = 'order_tab_td';
		break;
	}
	retobj 				= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	retobj.innerHTML 	= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	
	/* Calling the ajax function */
	Handlewith_Ajax('services/pricepromise_enquiries.php','fpurpose='+fpurpose+'&product_id='+prod_id+'&prom_id='+prom_id+'&'+qrystr);	
	
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
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			if(document.getElementById('retdiv_id').value!='master_div')
			{
				if(document.getElementById('ch_cur_poststatdiv').value!='')
				{
					curpostid_arr = document.getElementById('ch_cur_poststatdiv').value.split('_');
					postdivid		= 'poststat_div_'+curpostid_arr[3];
					targetstatobj 	= eval("document.getElementById('"+document.getElementById('ch_cur_poststatdiv').value+"')");
					if(targetstatobj)
					{
						if(targetstatobj.value==1)
						{
							targetstatobjnew 	= eval("document.getElementById('"+postdivid+"')");
							targetstatobjnew.innerHTML = 'Read';
							if(document.getElementById('current_new_post_cnt'))
							{
								var curcnt = parseInt(document.getElementById('current_new_post_cnt').value);
								if(curcnt>0)
									curcnt = curcnt-1;
								document.getElementById('current_new_post_cnt').value = curcnt;
							}	
						}
					}	
				}
			}
			if(document.getElementById('current_new_post_cnt'))
			{
				var curval = parseInt(document.getElementById('current_new_post_cnt').value);
				if(curval>0)
				{
					if(document.getElementById('show_new_post_cnt'))
					{
						document.getElementById('show_new_post_cnt').value = curval;
					}
				}
				else
				{
					if(document.getElementById('show_new_post_cnt'))
					{
						document.getElementById('show_new_post_cnt').value = 0;
					}
				}	
			}	
			document.getElementById('retdiv_id').value = 'master_div';
			if(document.getElementById('show_new_post_cnt'))
			{
				var curcnt = parseInt(document.getElementById('show_new_post_cnt').value);
				if(curcnt>0)
					document.getElementById('new_cnt_div').innerHTML = '('+curcnt+')';
				else
					document.getElementById('new_cnt_div').innerHTML = '';	
			} 
			document.getElementById('ch_cur_poststatdiv').value = '';
		}
		else
		{
			document.getElementById('retdiv_id').value = 'master_div';
			show_request_alert(req.status);
			document.getElementById('ch_cur_poststatdiv').value = '';
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}	
}
function save_note()
{
	var ordid 			= '<?php echo $edit_id?>';
	frm					= document.frmAddPricepromise;
	fieldRequired 		= Array('txt_notes');
	fieldDescription 	= Array('Note');
	fieldEmail 			= Array();
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
	{
		call_ajax_showlistall('savenote',0);
	}
	else
		return false
}
function delete_note(noteid)
{
	var prom_id											= '<?php echo $prom_id;?>';
	if (confirm('Are you sure you want to delete this note?'))
	{
		document.frmAddPricepromise.del_note_id.value = noteid;
		call_ajax_showlistall('deletenote',0);
	}
}

function call_ajax_showlistall(mod,unhide_errdiv)
{
	var atleastone 									= 0;
	var prom_id											= '<?php echo $prom_id;?>';
	var cat_orders									= '';
	var fpurpose										= '';
	var retdivid											= '';
	var moredivid										= '';
	var qrystr											= '';
	var curtab											= document.getElementById('selected_tab').value;
	
	if(document.getElementById('mainerror_div') && unhide_errdiv==0)
		document.getElementById('mainerror_div').style.display = 'none';

	switch(mod)
	{
		case 'savenote':	// case of saving order notes
			retdivid   	= '';
			fpurpose	= 'save_note';
			var note	= document.frmAddPricepromise.txt_notes.value;
			qrystr		= 'note='+note;
		break;
		case 'deletenote': 	// case of deleting order notes
			retdivid   	= '';
			fpurpose	= 'delete_note';
			var noteid	= document.frmAddPricepromise.del_note_id.value
			qrystr		= 'noteid='+noteid;
		break;
	};
	retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	retobj.innerHTML 											= '<center><img src="images/loading.gif" alt="Loading"></center>';
	/* Calling the ajax function */
	Handlewith_Ajax('services/pricepromise_enquiries.php','fpurpose='+fpurpose+'&curtab='+curtab+'&'+qrystr+'&prom_id='+prom_id);
}
function handle_showdetailsdiv(trid,divid,postid)
{
	trobj 		= eval("document.getElementById('"+trid+"')");
	trdivobj 	= eval("document.getElementById('"+trid+"div')");
	divobj		= eval("document.getElementById('"+divid+"')");
	var prom_id	= '<?php echo $prom_id;?>';
	if(trobj.style.display=='')
	{
		trobj.style.display = 'none';
		divobj.innerHTML = 'Details<img src="images/right_arr.gif" />';
		document.getElementById('retdiv_id').value = 'master_div';
	}
	else
	{
		trobj.style.display 	= '';
		retobj 					= eval("document.getElementById('"+trid+"div')");
		document.getElementById('retdiv_id').value 			= trid+'div';
		document.getElementById('ch_cur_poststatdiv').value = 'changed_post_status_'+postid;
		retobj.innerHTML 		= '<center><img src="images/loading.gif" alt="Loading"></center>';
		/* Calling the ajax function */
		Handlewith_Ajax('services/pricepromise_enquiries.php','fpurpose=show_post_details&prom_id='+prom_id+'&post_id='+postid);
		divobj.innerHTML 		= 'Details<img src="images/down_arr.gif" /> ';
	}
}
function add_queryposts() 
{
	if(document.getElementById('add_reply_tr').style.display=='')
	{
	document.getElementById('add_reply_tr').style.display='none';
	}
	else
	{
	document.getElementById('add_reply_tr').style.display='';
	}
}
function query_action(mod)
{
  if(mod=='save_post')
  {  
	if (document.frmAddPricepromise.query_reply.value =='')
	{
		alert('Please Enter the Post');
		return false;
	}
	else
	{	
		document.frmAddPricepromise.fpurpose.value 	= 'submit_posts';
		document.frmAddPricepromise.submit();
	}
  }
}
function call_ajax_delete(query_id,search_id,sortby,sortorder,recs,start,pg)
{
	var atleastone 		= 0;
	var del_ids 		= '';
	var search_status	= '<?php echo $_REQUEST['search_status']?>';
	var search_status 	= '<?=$_REQUEST['search_status']?>';
	var sortby 			= '<?=$_REQUEST['sort_by']?>';
	var sortorder 		= '<?=$_REQUEST['sort_order']?>';
	var recs 			= '<?=$_REQUEST['records_per_page']?>';
	var start 			= '<?=$_REQUEST['start']?>';
	var pg 				= '<?=$_REQUEST['pg']?>';
	var product_id 		= '<?=$_REQUEST['product_id']?>';
	var prom_id			= '<?=$prom_id?>';
	var passpost_status	= '';
	if (document.getElementById('sel_post_stat'))
		passpost_status = document.getElementById('sel_post_stat').value;		
	var qrystr				= 'search_status='+search_status+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&passpost_status='+passpost_status;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmAddPricepromise.elements.length;i++)
	{ 
		if (document.frmAddPricepromise.elements[i].type =='checkbox' && document.frmAddPricepromise.elements[i].name=='checkbox[]')
		{

			if (document.frmAddPricepromise.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmAddPricepromise.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select Post to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Post(s)?'))
		{
			retobj 				= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 	= '<center><img src="images/loading.gif" alt="Loading"></center>';	
			Handlewith_Ajax('services/pricepromise_enquiries.php','fpurpose=delete_post&prom_id='+prom_id+'&product_id='+product_id+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function handle_search_go()
{
	document.frmAddPricepromise.fpurpose.value ='edit';
	document.frmAddPricepromise.submit();
}
function handle_buttonclick(mode)
{
	fieldRequired 		= Array('prom_max_usage','prom_admin_price','prom_admin_qty');
	fieldDescription 	= Array('Max no of times','Admin Approved Price','Admin Approved Qty');
	fieldEmail 			= Array();
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array('prom_max_usage','prom_admin_price','prom_admin_qty');
	if(Validate_Form_Objects(document.frmAddPricepromise,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
	{
		var maxusage = parseFloat(document.frmAddPricepromise.prom_max_usage.value);
		var prom_used = parseFloat(document.frmAddPricepromise.prom_used.value);
		if(prom_used>maxusage)
		{
			document.getElementById('save_mode').value = '';
			alert('Max no of times customer can use this should be greater than or equal to total used count');
			return false;
		}		
		var save_txt = save_mode = '';
		var msg = '';
		switch (mode)
		{
			case 'save':
				msg = 'Are you sure you want to save the details?';
				save_mode = 'Just_Save';
			break;
			case 'accept':
				msg = 'Are you sure you want to Save the details and Accept the offer?';
				save_mode = 'Save_Accept';
			break;
			case 'reject':
				msg = 'No operation wiil be allowed on this offer after Rejection\n\nAre you sure you want to Reject the offer?';
				save_mode = 'Save_Reject';
			break;
		};
		if(confirm(msg))
		{
			show_processing();
			document.getElementById('save_mode').value = save_mode;
			document.frmAddPricepromise.submit();
		}
	} else {
		document.getElementById('save_mode').value = '';
		return false;
	}
}
</script>
<form name='frmAddPricepromise' action='home.php?request=price_promise' method="post">
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=price_promise&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&search_cust=<?=$_REQUEST['search_cust']?>&status=<?=$_REQUEST['status']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&status=<?=$_REQUEST['status']?>">List Price Promise Enquiry</a> <span>Price Promise Enquiry Details</span></div></td>
</tr>
<tr>
  <td colspan="2" align="left" valign="middle" class="helpmsgtd_main">
  <?php 
	  Display_Main_Help_msg($help_arr,$help_msg);
  ?>
 </td>
</tr>
<?php 
if($alert)
{			
?>
<tr id="errormsg_tr">
	<td colspan="2" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
</tr>
<?
}
?>
<tr>
	<td  align="left" colspan="2">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabmenu_x">
	<tr>
		<td  align="left" onClick="handle_tabs('main_tab_td','querymain_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Query Details</span> </td>
		<td  align="left" onClick="handle_tabs('posts_tab_td','qrypost')" class="<?php if($curtab=='posts_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="posts_tab_td"><span> <div style="float:left; width:95%">Posts&nbsp;&nbsp;</div> <div id='new_cnt_div' style="float:left; width:5%"><?php echo $new_posts?></div></span> </td>
		<td  align="left" onClick="handle_tabs('postmenu_tab_td','post')" class="<?php if($curtab=='postmenu_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="postmenu_tab_td"><span>Notes </span> </td>
		<td  align="left" onClick="handle_tabs('order_tab_td','order_list')" class="<?php if($curtab=='order_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="order_tab_td"><span>Orders</span> </td>
		<td width="70%" align="left">&nbsp;</td>
	</tr>
	</table>				
	</td>
</tr>
<tr>
  <td colspan="2" >
  <div id='master_div'>
	<?php 
	if ($curtab=='main_tab_td')
	{
		show_pricequery_details_list($product_id,$prom_id,$alert);
	}
	elseif ($curtab=='posts_tab_td')
	{
		function_posts($product_id,$prom_id,$alert_sub);
	}
	elseif ($curtab=='postmenu_tab_td')
	{
		function_pricequery_post($product_id,$prom_id,$alert);
	}
	?>		
  </div>
  </td>
 </tr>
<tr>
  <td width="52%" align="left" valign="middle" class="tdcolorgray" >
	<input type="hidden" name="prom_id" id="prom_id" value="<?=$prom_id?>" />
	<input type="hidden" name="checkbox[0]" id="checkbox[0]" value="<?=$prom_id?>" />
	<input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
	<input type="hidden" name="search_cust" id="search_cust" value="<?=$_REQUEST['search_cust']?>" />
	<input type="hidden" name="status" id="status" value="<?=$_REQUEST['status']?>" />
	<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
	<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
	<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
	<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
	<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
	<input type="hidden" name="status" id="status" value="<?=$_REQUEST['status']?>" />
	<input type="hidden" name="fpurpose" id="fpurpose" value="update" />
	<input type="hidden" name="product_id" id="product_id" value="<?=$product_id?>" />
	<input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
	<input type="hidden" name="selected_tab" id="selected_tab" value="<?php echo $curtab?>" />
	<input type="hidden" name="del_note_id" id="del_note_id" value="" />
	<input type="hidden" name="ch_prom_id" id="ch_prom_id" value="" />
	<input type="hidden" name="ch_cur_poststatdiv" id="ch_cur_poststatdiv" value="" />
	<input type="hidden" name="save_mode" id="save_mode" value="" />
	<input type="hidden" name="show_new_post_cnt" id="show_new_post_cnt" value="<?php echo $new_cnt?>" />
 </td>
</tr>
</table>
</form>	  


<?php
	/*#################################################################
	# Script Name 	: list_order_query_details.php
	# Description 	: Page for listing the query posts
	# Coded by 		: LH
	# Created on	: 22-Apr-2008
	# Modified by	: LH
	# Modified On	:
	#################################################################*/
#Define constants for this page
$table_name ='order_queries_posts';
$page_type = 'Order Query Details';
$help_msg = get_help_messages('LIST_ORDER_QUERY_DET_MESS1');
$query_id=($_REQUEST['query_id']?$_REQUEST['query_id']:$_REQUEST['checkbox'][0]);
if($curtab=='')
{
	$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';
}
/*if($_REQUEST['alert_submit']==1)
{
$alert ="Post Added Successfully";
}
else if($_REQUEST['alert_delete']==1)
{
$alert ="Post(s) Deleted Successfully";
}
else if($_REQUEST['alert_submit_not']==1)
{
$alert ="Cannot Add Post!!!!.Query closed!!!";
}
elseif($_REQUEST['alert_chatatus']==1)
{
$alert ="Query Status Changed Successfully!!";

} */
?>
<script language="javascript" type="text/javascript">
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
function more_post_details(id,imgobj) 
{
var src = imgobj.src;
var retindx = src.search('plus.gif');
if (retindx!=-1)
	{
	imgobj.src = 'images/minus.gif';
	document.getElementById('post_details_id_'+id).style.display='';
	}
	else
	{
	imgobj.src = 'images/plus.gif';
	document.getElementById('post_details_id_'+id).style.display='none';
	}

}
function call_ajax_delete(query_id,search_id,sortby,sortorder,recs,start,pg)
{
	var atleastone 	= 0;
	var del_ids 	= '';
	var search_status		= '<?php echo $_REQUEST['search_status']?>';
	var search_status = '<?=$_REQUEST['search_status']?>';
		var sortby = '<?=$_REQUEST['sort_by']?>';
		var sortorder = '<?=$_REQUEST['sort_order']?>';
		var recs = '<?=$_REQUEST['records_per_page']?>';
		var start = '<?=$_REQUEST['start']?>';
		var pg = '<?=$_REQUEST['pg']?>';

	
	var qrystr				= 'search_status='+search_status+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistOrderquery_details.elements.length;i++)
	{ 
		if (document.frmlistOrderquery_details.elements[i].type =='checkbox' && document.frmlistOrderquery_details.elements[i].name=='checkbox[]')
		{

			if (document.frmlistOrderquery_details.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistOrderquery_details.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select Post to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected Post?'))
		{
		
            	retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
				retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';	
				Handlewith_Ajax('services/order_enquire.php','fpurpose=delete_post&query_id='+query_id+'&del_ids='+del_ids+'&'+qrystr);
			/*document.frmlistOrderquery_details.del_ids.value=del_ids;
			document.frmlistOrderquery_details.fpurpose.value='delete_post';
			document.frmlistOrderquery_details.query_id.value=query_id;
			document.frmlistOrderquery_details.submit();*/
		}	
	}	
}
function handle_tabs(id,mod)
{ 
	tab_arr 									= new Array('main_tab_td','postmenu_tab_td');
	var atleastone 						= 0;
	var enq_id										= '<?php echo $query_id?>';
	var fpurpose							= '';
	var retdiv_id								= '';
	var sortby								= '<?php echo $sort_by?>';
	var sortorder							= '<?php echo $sort_order?>';
	var recs									= '<?php echo $records_per_page?>';
	var start								= '<?php echo $start?>';
	var pg									= '<?php echo $pg?>';
	var curtab								= '<?php echo $curtab?>';
	//var qrystr									= 'pass_group_name='+customergroupname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&curtab='+curtab;
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
			fpurpose ='list_orderquery_details';
		break;
		case 'post': // Case of Categories in the group
			fpurpose	= 'list_postdetails';
			
		break;
		
	}
	retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	
	/* Calling the ajax function */
	Handlewith_Ajax('services/order_enquire.php','fpurpose='+fpurpose+'&query_id='+enq_id);	
	
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
		}
		else
		{
			show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}	
}
function query_action(mod)
{
  if(mod=='status')
  { 
   document.frmlistOrderquery_details.fpurpose.value = 'update_query';
   document.frmlistOrderquery_details.submit();
  }
  else if(mod=='save_post')
  {  
	  	if (document.frmlistOrderquery_details.query_reply.value =='')
		{
			alert('Please Enter the Reply content');
			return false;
		}
	  	else
	  	{	
	   		document.frmlistOrderquery_details.fpurpose.value = 'submit_posts';
	   		document.frmlistOrderquery_details.submit();
	  	}
  }
}
function handle_showdetailsdiv(trid,divid)
{
	trobj 	= eval("document.getElementById('"+trid+"')");
	divobj	= eval("document.getElementById('"+divid+"')");
	if(trobj.style.display=='')
	{
		trobj.style.display = 'none';
		divobj.innerHTML = 'Details<img src="images/right_arr.gif" />';
	}
	else
	{
		trobj.style.display ='';
		divobj.innerHTML = 'Details<img src="images/down_arr.gif" /> ';
	}
}
</script>
   <form name="frmlistOrderquery_details" action="home.php" method="post" >
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td  colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=order_enquiries&order_id=<?=$_REQUEST['order_id']?>&&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&search_status=<?=$_REQUEST['search_status']?>&srch_review_startdate=<?=$_REQUEST['pass_srch_review_startdate']?>&srch_review_enddate=<?=$_REQUEST['pass_srch_review_enddate']?>">List Order Queries</a><span> List Order Query Details</span></div></td>
        </tr>
		<tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="2">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<tr>
			<td  align="left" colspan="2">
				<table width="100%" border="0" cellspacing="1" cellpadding="1">
				<tr>
						<td  align="left" onClick="handle_tabs('main_tab_td','querymain_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Ouery Details</span></td>
						<td  align="left" onClick="handle_tabs('postmenu_tab_td','post')" class="<?php if($curtab=='postmenu_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="postmenu_tab_td"><span>Post Details</span></td>
						<td width="90%" align="left">&nbsp;</td>
				</tr>
				</table>				
			</td>
		</tr>
		<tr>
          <td >
		  <div id='master_div'>
			<?php 
			if ($curtab=='main_tab_td')
			{
				show_orderquery_details_list($query_id,$alert);
			}
			elseif ($curtab=='postmenu_tab_td')
			{
				function_orderquery_post($query_id,$alert);
			}
			?>		
		  </div>
		  </td>
		  </tr>
		
		<input type="hidden" name="fpurpose" value="list_details" />
		<input type="hidden" name="request" value="order_enquiries" />
		<input type="hidden" name="query_id" value="<?=$query_id?>" />
		<input type="hidden" name="order_id" value="<?=$_REQUEST['order_id']?>" />
		<input type="hidden" name="search_status" value="<?=$_REQUEST['search_status']?>" />
		<input type="hidden" name="pass_srch_review_startdate" value="<?=$_REQUEST['pass_srch_review_startdate']?>" />
		<input type="hidden" name="pass_srch_review_enddate" value="<?=$_REQUEST['pass_srch_review_enddate']?>" />
		<input type="hidden" name="pass_start" value="<?=$_REQUEST['pass_start']?>" />
		<input type="hidden" name="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
		<input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
		<input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
		<input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
		<input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
		<input type="hidden" name="del_ids" id="del_ids" value="" />
		 <tr>
		   <td align="left" valign="top" class="tdcolorgray" colspan="2" >&nbsp;</td>
   </tr>
</table>
</form>

	

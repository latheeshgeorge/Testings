<?php
	/*#################################################################
	# Script Name 	: list_prodetails.php
	# Description 	: Page for listing the product that is enquired
	# Coded by 		: LH
	# Created on	: 22-03-2008
	# Modified by	: LH
	# Modified On	: 22-04-2008
	#################################################################*/
#Define constants for this page
$page_type = 'Product Enquiry Details';
$help_msg = get_help_messages('LIST_PROD_ENQ_DET_MSS1');
$table_headers = array('Slno.','Product_name','Stock','Variable','Weight','Discount');
$header_positions=array('left','left','left','left','left','left');
$colspan = count($table_headers);
$enquiry_id=($_REQUEST['enquiry_id']?$_REQUEST['enquiry_id']:$_REQUEST['checkbox'][0]);
$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';

?>	
<script language="javascript" type="text/javascript">
function enquire_action(mod)
{
 if(mod=='go')
 {
    document.frmListProductEnquiries.fpurpose.value ='update_enquiry';
    document.frmListProductEnquiries.submit();
 }
 else if(mod=='send_mail')
 {
			fieldRequired 		= Array('mail_subject','mail_content');
			fieldDescription 	= Array('Subject for the mail','Mail Content');
			fieldEmail = Array();
			fieldConfirm = Array();
			fieldConfirmDesc  = Array();
			fieldNumeric = Array();
			frm = document.frmListProductEnquiries;
			if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
				show_processing();
				document.frmListProductEnquiries.fpurpose.value ='send_enquire_email';
    			document.frmListProductEnquiries.submit();
				return true;
			} 
			else
			{
			 return false;
			}
 	
 }
}
function handle_tabs(id,mod)
{ 
	tab_arr 									= new Array('main_tab_td','productmenu_tab_td','notemenu_tab_td');
	var atleastone 						= 0;
	var enq_id										= '<?php echo $enquiry_id?>';
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
		case 'customermain_info':
			fpurpose ='list_customer_maininfo';
		break;
		case 'product': // Case of Categories in the group
			fpurpose	= 'list_productdetails';
			
		break;
		case 'note': // Case of Display Products in the group
			fpurpose	= 'show_addnote';
		break;
		
	}
	retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	
	/* Calling the ajax function */
	Handlewith_Ajax('services/product_enquire.php','fpurpose='+fpurpose+'&enquiry_id='+enq_id);	
	
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
//function handle_expansion(imgobj,mod)
//{
//	var src = imgobj.src;
//	var retindx = src.search('plus.gif');
//	switch(mod)
//	{
//		case 'product_det':
//			if (retindx!=-1)
//			{
//				imgobj.src = 'images/minus.gif';
//				if(document.getElementById('product_details'))
//					document.getElementById('product_details').style.display = '';
//					call_ajax_showlistall('product_det');	
//				
//			}	
//			else
//			{
//				imgobj.src = 'images/plus.gif';
//				if(document.getElementById('product_details'))
//					document.getElementById('product_details').style.display = 'none';
//			}	
//		break;
//		case 'enquiry_note_add':
//		if (retindx!=-1)
//			{
//		    imgobj.src = 'images/minus.gif';
//				if(document.getElementById('enquirynote_add')){
//					document.getElementById('enquirynote_add').style.display = '';
//					}
//					if(document.getElementById('enquirynote_div'))
//					document.getElementById('enquirynote_div').style.display = '';
//			call_ajax_showlistall('enquirynote_add');
//			}	
//			else
//			{
//				imgobj.src = 'images/plus.gif';
//				if(document.getElementById('enquirynote_add'))
//					document.getElementById('enquirynote_add').style.display = 'none';
//				if(document.getElementById('enquirynote_div'))
//					document.getElementById('enquirynote_div').style.display = 'none';
//			}	
//		break;
//		case 'enquiry_note_view':
//		if (retindx!=-1)
//			{
//		    imgobj.src = 'images/minus.gif';
//				if(document.getElementById('enquirynote_show'))
//					document.getElementById('enquirynote_show').style.display = '';
//				if(document.getElementById('shownote_div'))
//				document.getElementById('shownote_div').style.display = '';
//			      call_ajax_showlistall('enquirynote_show');
//			}	
//			else
//			{
//				imgobj.src = 'images/plus.gif';
//				if(document.getElementById('enquirynote_show'))
//					document.getElementById('enquirynote_show').style.display = 'none';
//				if(document.getElementById('shownote_div'))	
//					document.getElementById('shownote_div').style.display = 'none';
//
//			}	
//		break;
//	 };
//}
/*function call_ajax_showlistall(mod)
{  
	var atleastone 										= 0;
	var enq_id										= '<?php echo $enquiry_id?>';
	var fpurpose										= '';
	var retdivid	= '';
	switch(mod)
	{
		case 'product_det': // Case of Products in the shelf
			retdivid   	= 'productDetails_div';
			fpurpose	= 'list_productdetails';
			 document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result //
			retobj 										= eval("document.getElementById('"+retdivid+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';															
			var qrystr									= '';
			Handlewith_Ajax('services/product_enquire.php','fpurpose='+fpurpose+'&enquiry_id='+enq_id);	
		break;
		case 'enquirynote_add':
				retdivid   	= 'enquirynote_div';
				fpurpose   =  'show_addnote';
				break;
		case 'enquirynote_show':
				retdivid   	= 'shownote_div';
				fpurpose   =  'view_note';
				break;		
	}	
	            document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result //
				retobj 										= eval("document.getElementById('"+retdivid+"')");
				retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';	
				Handlewith_Ajax('services/product_enquire.php','fpurpose='+fpurpose+'&enquiry_id='+enq_id);
}	*/
/*function ajax_return_contents() 
{
	var ret_val='';
		var disp 	= 'no';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val 	= req.responseText;
			targetdiv 	= document.getElementById('retdiv_id').value;
			norecdiv 	= document.getElementById('retdiv_more').value;
			//alert(targetdiv );
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			/* Decide the display of action buttons//
			switch(targetdiv)
			{
				case 'productDetails_div':
					if(document.getElementById('productDetails_norec'))
					{
						if(document.getElementById('productDetails_norec').value==1)
						{
							disp = 'none';
						}	
						else
							disp = '';
					}
					else
						disp = '';	
				  break;
				  case 'enquirynote_div':
					if(document.getElementById('enquiry_norec'))
					{
						if(document.getElementById('enquiry_norec').value==1)
						{
							disp = 'none';
						}	
						else
							disp = '';
					}
					else
						disp = '';	
				  break;
				   case 'shownote_div':
					if(document.getElementById('alert_mess'))
					{
						if(document.getElementById('alert_mess').value=='save')
						{
							document.getElementById('alert_save_div').style.display = '';
							document.getElementById('display_alert_save').style.display = '';
							document.getElementById('alert_delete_div').style.display = 'none';
							document.getElementById('display_alert_delete').style.display = 'none';
						}
						else if(document.getElementById('alert_mess').value=='delete')
						{
						document.getElementById('alert_save_div').style.display = 'none';
							document.getElementById('display_alert_save').style.display = 'none';
							document.getElementById('alert_delete_div').style.display = '';
							document.getElementById('display_alert_delete').style.display = '';
						}	
						
					}
					else
						disp = '';	
						document.getElementById('enquirynote_add').style.display = 'none';
						imgobj = document.getElementById('cat_imgtag_add');
						var src = imgobj.src;
						imgobj.src = 'images/plus.gif';
				  break;
				
			};	
			hide_processing();
		if (disp!='no')
			{
				norecobj 	= eval("document.getElementById('"+norecdiv+"')");
				if (norecobj)
					norecobj.style.display = disp;
			}
	
		}
		else
		{
		   show_request_alert(req.status);
		}
	}
}*/
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
function save_note_ajax(enqid)
{
		   var note = document.frmListProductEnquiries.txt_note.value;
		   	retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
				retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';	
				
 		   Handlewith_Ajax('services/product_enquire.php','fpurpose=save_note&enquiry_id='+enqid+'&enq_note='+note);
		   
}
function delete_note(delid,enqid)
{
            		   	retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
				retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';	
Handlewith_Ajax('services/product_enquire.php','fpurpose=delete_note&note_id='+delid+'&enquiry_id='+enqid);
}
function  select_mail()
{
	if(document.getElementById('send_mail_id').style.display=='')
	{
	document.getElementById('send_mail_id').style.display='none';
		document.getElementById('id_sendmail').style.display='';
	}
	else
	{
	document.getElementById('send_mail_id').style.display='';
	document.getElementById('id_sendmail').style.display='none';

	}
}
</script>
<form name='frmListProductEnquiries' action='home.php?request=product_enquire'  method="post"  >
<input type="hidden" name="txt_mod" value="" />
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td   align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=product_enquire&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&search_status=<?=$_REQUEST['search_status']?>&search_email=<?=$_REQUEST['search_email']?>&srch_review_startdate=<?=$_REQUEST['srch_review_startdate']?>&srch_review_enddate=<?=$_REQUEST['srch_review_enddate']?>">List Product Enquiries</a><span> List Product Enquiry Details</span></div></td>
        </tr>
       <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<tr>
			<td  align="left">
				<table width="100%" border="0" cellspacing="1" cellpadding="1">
				<tr>
						<td  align="left" onClick="handle_tabs('main_tab_td','customermain_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Customer Details</span></td>
						<td  align="left" onClick="handle_tabs('productmenu_tab_td','product')" class="<?php if($curtab=='productmenu_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="productmenu_tab_td"><span>Product Details</span></td>
						<td  align="left" onClick="handle_tabs('notemenu_tab_td','note')" class="<?php if($curtab=='notemenu_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="notemenu_tab_td"><span>Notes</span></td>
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
				show_customer_details($enquiry_id,$alert);
			}
			elseif ($curtab=='productmenu_tab_td')
			{
				show_product_details_list($enquiry_id,$alert);
			}
			elseif ($curtab=='notemenu_tab_td')
			{
				function_displaynote_add($enquiry_id,$alert);
			}
			?>		
		  </div>
		  </td>
		  </tr>
		<tr>
          <td  valign="middle" class="tdcolorgray" align="center" colspan="4" >
		  <input type="hidden" name="enquiry_id" id="enquiry_id" value="<?=$enquiry_id?>" />
		  <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
		  <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
		  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
		  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
		  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		  <input type="hidden" name="search_status" id="search_status" value="<?=$_REQUEST['search_status']?>" />
		  <input type="hidden" name="search_email" id="search_email" value="<?=$_REQUEST['search_email']?>" />
		  <input type="hidden" name="srch_review_startdate" id="srch_review_startdate" value="<?=$_REQUEST['srch_review_startdate']?>" />
		  <input type="hidden" name="srch_review_enddate" id="srch_review_enddate" value="<?=$_REQUEST['srch_review_enddate']?>" />
		  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="" />
		  <input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
		  <input type="hidden"  name="status_read"  id="status_read" value="1" />
		  
        </tr>
		<?
		/*$sqlp = "select p.product_name,p.product_actualstock,p.product_adddate,p.product_discount,p.product_weight, pr.products_product_id,pv.variable_name from products p ,product_enquiry_data pr,product_enquiry_data_vars pv where pr.product_enquiries_enquiry_id=".$row['enquiry_id']." AND p.product_id=pr.products_product_id AND pv.product_enquiry_data_id=pr.id";
		$resp=$db->query($sqlp);*/
		?>
      </table>
</form>	  
<script language="javascript"> 	
/*function display_prodtext(imgobj,row_id){
	var src = imgobj.src;
	var retindxprodtext = src.search('plus.gif');
	
	if (retindxprodtext!=-1){
//alert(retindxprodtext);
		imgobj.src = 'images/minus.gif';
		if(document.getElementById(row_id)){
		document.getElementById(row_id).style.display = '';
		}
	}else{
		imgobj.src = 'images/plus.gif';
		if(document.getElementById(row_id)){
		document.getElementById(row_id).style.display = 'none';
		}
	
}
}*/
</script>
	

<?php
	/*#################################################################
	# Script Name 	: edit_product_reviews.php
	# Description 	: Page for editing product Reviews
	# Coded by 		: ANU
	# Created on	: 13-Aug-2007
	# Modified by	: ANU
	# Modified On	: 13-Aug-2007
	#################################################################*/
#Define constants for this page
$page_type = 'Product Enquiry Details';
$help_msg = get_help_messages('LIST_PROD_ENQ_DET_MSS1');
$table_headers = array('Slno.','Product_name','Stock','Variable','Weight','Discount');
$header_positions=array('left','left','left','left','left','left');
$colspan = count($table_headers);
$enquiry_id=($_REQUEST['enquiry_id']?$_REQUEST['enquiry_id']:$_REQUEST['checkbox'][0]);

?>	
<script language="javascript" type="text/javascript">
function valform(frm,mod)
{
	var mod = frm.txt_mod.value;
	
	if(mod=='email')
		{ 
		 
			fieldRequired 		= Array('mail_subject','mail_content');
			fieldDescription 	= Array('Subject for the mail','Mail Content');
			fieldEmail = Array();
			fieldConfirm = Array();
			fieldConfirmDesc  = Array();
			fieldNumeric = Array();
			if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
				show_processing();
				frm.fpurpose.value ='send_enquire_email';
				return true;
			} 
			else
			{
			 return false;
			}
		}	
}
function handle_expansion(imgobj,mod)
{
	var src = imgobj.src;
	var retindx = src.search('plus.gif');
	switch(mod)
	{
		case 'product_det':
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('product_details'))
					document.getElementById('product_details').style.display = '';
					call_ajax_showlistall('product_det');	
				
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('product_details'))
					document.getElementById('product_details').style.display = 'none';
			}	
		break;
		case 'enquiry_note_add':
		if (retindx!=-1)
			{
		    imgobj.src = 'images/minus.gif';
				if(document.getElementById('enquirynote_add')){
					document.getElementById('enquirynote_add').style.display = '';
					}
					if(document.getElementById('enquirynote_div'))
					document.getElementById('enquirynote_div').style.display = '';
			call_ajax_showlistall('enquirynote_add');
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('enquirynote_add'))
					document.getElementById('enquirynote_add').style.display = 'none';
				if(document.getElementById('enquirynote_div'))
					document.getElementById('enquirynote_div').style.display = 'none';
			}	
		break;
		case 'enquiry_note_view':
		if (retindx!=-1)
			{
		    imgobj.src = 'images/minus.gif';
				if(document.getElementById('enquirynote_show'))
					document.getElementById('enquirynote_show').style.display = '';
				if(document.getElementById('shownote_div'))
				document.getElementById('shownote_div').style.display = '';
			      call_ajax_showlistall('enquirynote_show');
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('enquirynote_show'))
					document.getElementById('enquirynote_show').style.display = 'none';
				if(document.getElementById('shownote_div'))	
					document.getElementById('shownote_div').style.display = 'none';

			}	
		break;
	 };
}
function call_ajax_showlistall(mod)
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
			 document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */	
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
	            document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */	
				retobj 										= eval("document.getElementById('"+retdivid+"')");
				retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';	
				Handlewith_Ajax('services/product_enquire.php','fpurpose='+fpurpose+'&enquiry_id='+enq_id);
}	
function ajax_return_contents() 
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
			/* Decide the display of action buttons*/
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
}
function save_note_ajax(enqid)
{
		   var note = document.frmListProductEnquiries.txt_note.value;
		   var retdivid   	= 'shownote_div';
		   document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */	
				retobj 										= eval("document.getElementById('"+retdivid+"')");
				retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';	
				
 		   Handlewith_Ajax('services/product_enquire.php','fpurpose=save_note&enquiry_id='+enqid+'&enq_note='+note);
		   
}
function delete_note(delid,enqid)
{
             retdivid   	= 'shownote_div';
		     document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */	
				retobj 										= eval("document.getElementById('"+retdivid+"')");
				retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';	
Handlewith_Ajax('services/product_enquire.php','fpurpose=delete_note&note_id='+delid+'&enquiry_id='+enqid);
}
function  select_mail()
{
if(document.getElementById('send_mail_id').style.display=='')
document.getElementById('send_mail_id').style.display='none';
else
document.getElementById('send_mail_id').style.display='';
}
</script>
<form name='frmListProductEnquiries' action='home.php?request=product_enquire'  method="post" onsubmit="return valform(this);" >
<input type="hidden" name="txt_mod" value="" />
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td  colspan="3" align="left" valign="middle" class="treemenutd"><a href="home.php?request=product_enquire&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&search_name=<?=$_REQUEST['pass_search_name']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>">List Product Enquiries</a> &gt;&gt; List Product Enquiry Details </td>
        </tr>
        <tr>
          <td  colspan="3" align="left" valign="middle" class="helpmsgtd" ><?=$help_msg ?></td>
        </tr>
		<?php 
		if($alert)
		{			
		?>
        <tr>
          <td  align="center" valign="middle" class="errormsg"  colspan="2"><?=$alert?></td>
        </tr>
		
		<?
		}
		
		$sql="SELECT pe.enquiry_id,pe.enquiry_fname,pe.enquiry_middlename,pe.enquiry_lastname,pe.enqiury_address,pe.enquiry_postcode,pe.enquiry_email,pe.enquiry_phone,pe.enquiry_title,pe.enquiry_fax,pe.enquiry_mobile,pe.enquiry_hidden,pe.enquiry_status,pe.enquiry_text from product_enquiries pe WHERE pe.sites_site_id=$ecom_siteid AND pe.enquiry_id=".$enquiry_id." LIMIT 1";
		$res=$db->query($sql);
		$row=$db->fetch_array($res);
		?>
		<tr><td width="75%" class="tdcolorgrayleft" valign="top" ><table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
          <td align="left" valign="top" class="seperationtd" colspan="3" >Customer Details  <span class="redtext">*</span> </td>
		 </tr> 
		<tr>
		<td colspan="3" class="tdcolorgray" >
		<table cellspacing="0" cellpadding="0" width="100%">
        
		 <tr> 
          <td align="left" valign="middle" class="tdcolorgray" colspan="2"><strong>Name </strong></td><td width="36%" align="left" valign="middle" class="tdcolorgray" >:&nbsp; &nbsp;<?=$row['enquiry_fname'].$row['enquiry_middlename'].$row['enquiry_lname']?> </td>
		  <td width="18%" align="left" valign="middle" class="tdcolorgray" ><strong>Enquiry Status  </strong>&nbsp;</td>
		  <td width="38%" align="left" valign="middle" class="tdcolorgray" >:&nbsp;
            <select name="enquiry_statuss" class="dropdown" id="enquiry_statuss">
              <option value="NEW" <? if($_REQUEST['status_read']){($row['enquiry_status']=='NEW')?'selected':'';}?> >NEW</option>
              <option value="PENDING" <? if(!$_REQUEST['status_read']){if($row['enquiry_status']=='NEW') echo 'selected'; else '';}else{ if($row['enquiry_status']=='PENDING') echo 'selected'; else '';} ?>>PENDING</option>
              <option value="CLOSED" <?= ($row['enquiry_status']=='CLOSED')?'selected':''; ?>>CLOSED</option>
              <option value="CANCELLED" <?= ($row['enquiry_status']=='CANCELLED')?'selected':''; ?>>CANCELLED</option>
            </select>
&nbsp;&nbsp;
		    <input name="Submit" type="submit" class="red" value="Go" /></td>
		 </tr>
		  <tr>
		  <td height="21" colspan="2"  align="left" valign="middle" class="tdcolorgray"><div align="left"><strong>Phone </strong> </div></td>
		  <td width="36%"  align="left" valign="middle" class="tdcolorgray">:&nbsp;&nbsp;<?=$row['enquiry_phone'] ?></td>
		  <td width="18%"  align="left" valign="middle" class="tdcolorgray"><strong> Post Code</strong></td>
		  <td width="38%"  align="left" valign="middle" class="tdcolorgray">:
		    <?=$row['enquiry_postcode'] ?>		    &nbsp; &nbsp;&nbsp;</td>
		  </tr>
		   <tr>
		  <td align="left" valign="middle" class="tdcolorgray" colspan="2"><div align="left"><strong>Mobile</strong></div></td>
		  <td align="left" valign="middle" class="tdcolorgray">:
		    <?=$row['enquiry_mobile'] ?>		    &nbsp;&nbsp; </td>
	      <td align="left" valign="middle" class="tdcolorgray"><strong> Address</strong></td>
	      <td align="left" valign="middle" class="tdcolorgray">:
	        <?=$row['enqiury_address'].$row['enquiry_middlename'].$row['enquiry_lname']?>
	        &nbsp;&nbsp;</td>
	      </tr>
		  <tr>
		  <td  align="left" valign="middle" class="tdcolorgray" colspan="2"><div align="left"><strong>Fax</strong></div></td>
		  <td align="left" valign="middle" class="tdcolorgray">:&nbsp;
		    <?=$row['enquiry_fax'] ?>		    &nbsp;</td>
		  <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		  <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		  </tr>
		  
		 <tr> 
          <td align="left" valign="middle" class="tdcolorgray" colspan="2"><strong>Email</strong></td>
		  <td colspan="3" align="left" valign="middle" class="tdcolorgray">:&nbsp; &nbsp;
		    <?=$row['enquiry_email'] ?>
		    <input name="Submit2" type="button" class="red" value="Send Mail" onclick="select_mail()" /></td>
		 </tr>
		 <tr id="send_mail_id" style="display:none">
			 <td align="left" valign="middle" class="tdcolorgray" colspan="5">
				 <table border="0" cellpadding="0" cellspacing="0" width="100%">
					 <tr>
						 <td width="8%" align="right" valign="middle" class="tdcolorgray">Subject :</td>
					    <td width="92%" align="left" valign="middle" class="tdcolorgray"><input type="text" name="mail_subject" value="" id="mail_subject" /></td>
					</tr>
					<tr>	
						 <td align="right" valign="middle" class="tdcolorgray">Content :</td><td align="left" valign="middle" class="tdcolorgray"><textarea name="mail_content" id="mail_content" rows="4" cols="20"></textarea></td>
					 </tr>
					 <tr><td align="center" valign="middle" class="tdcolorgray" colspan="2"><input name="Submit2" type="submit" class="red" value="Send" onclick="document.frmListProductEnquiries.txt_mod.value='email';"/></td></tr>
				 </table>
			 </td>
 		 </tr>
		  </table>
		  </td>
		  </tr>
			<? 
			$sql_element_section = "SELECT DISTINCT section_name,element_sections_section_id FROM product_enquiry_dynamic_values WHERE sites_site_id=$ecom_siteid AND  product_enquiries_enquiry_id =".$enquiry_id."";
			//echo $sql_element_section;
			$res_section=$db->query($sql_element_section);
		 	if($db->num_rows($res_section)){
			        $cnt =1;	
					while($row_section = $db->fetch_array($res_section))
					{ 
					$sqld ="SELECT dynamic_label,dynamic_value from product_enquiry_dynamic_values where product_enquiries_enquiry_id =".$enquiry_id." AND element_sections_section_id=".$row_section['element_sections_section_id']."";
					$resd=$db->query($sqld);
					if($db->num_rows($resd)){
					?>
					<tr>
					<td class="seperationtd" align="left" colspan="2"><?=$row_section['section_name']?>&nbsp;&nbsp;<span class="redtext">*</span></td>
					</tr>
					<tr>
					  <td  colspan="2" class="tdcolorgray">
					  <table cellspacing="0" cellpadding="0" width="100%" border="0">
					  <tr>
						<td align="left" valign="middle" class="tdcolorgray" colspan="2">
					    </td>
					   <td class="tdcolorgray"  align="left" >&nbsp;&nbsp;</td>
						</tr>
			
					<?
					while($rowd=$db->fetch_array($resd)){
					$cnt++;
					?>
					<tr>
					<td align="left" valign="middle" class="tdcolorgray" colspan="2">
					  <div align="left"><strong><?=$rowd['dynamic_label']?> </strong></div></td>
					  <td class="tdcolorgray"  align="left" width="77%" >:&nbsp;<?=$rowd['dynamic_value']?>&nbsp;</td>
					</tr>
					<? } ?>
					   </table>
					  </td>
					</tr>
					   <?
				  	   }
					}
	 			} 
				?>
		<tr>
	<td class="tdcolorgray"   align="left">&nbsp;
	 
	</td>
	<td width="81%" align="left"  class="tdcolorgray">
&nbsp;&nbsp;</td>
	</tr>
	<tr>
	<td class="tdcolorgray"    align="left" width="19%">
	 <strong>Enquiry note </strong>	</td>
	<td class="tdcolorgray" align="left">
:&nbsp;&nbsp;<?=$row['enquiry_text']?></td>
	</tr>
		<tr >
          <td  align="left" valign="bottom"  colspan="2">&nbsp;
            <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="3%" class="seperationtd" ><img id="cat_imgtag" src="images/plus.gif" border="0" onclick="handle_expansion(this,'product_det')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd">Enquired Products Details</td>
            </tr>
          </table></td>
        </tr>
			 <tr  id="product_details" style="display:none" >
          		<td  align="left" valign="middle" class="tdcolorgray" colspan="2" >
				<div id="productDetails_div" style="text-align:center">			    </div>				</td>
			</tr>
		 
		<tr>
          <td  align="left" valign="middle" class="tdcolorgray" >		  </td>
        </tr>
				</table>
		</td>
		  <td width="25%" valign="top" class="tdcolorgrayleft" style=" border-left:1px solid #d9d9d9">
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr><td>				    </td>	</tr>
		<tr>
		<td  valign="top" class="seperationtd" align="left" colspan="2">Notes&nbsp;<span class="redtext">*</span> </td>
		</tr>
		<tr  id="display_alert_save" style="display:none" >
          		 <td  align="center" valign="middle" class="errormsg"  colspan="2">
				<div id="alert_save_div" style="text-align:center">	Note Saved successfully		    </div>			  </td>
			</tr>
			<tr  id="display_alert_delete" style="display:none" >
          		 <td  align="center" valign="middle" class="errormsg"  colspan="2">
				<div id="alert_delete_div" style="text-align:center">Note Deleted Successfully			    </div>			  </td>
			</tr>
			<tr id="addimage_tr">
              <td width="3%" class="seperationtd" ><img id="cat_imgtag_add" src="images/plus.gif" border="0" onclick="handle_expansion(this,'enquiry_note_add')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd">Click Here To Add Note</td>
            </tr>			 					
					<tr  id="enquirynote_add" style="display:none" >
                     <td valign="top"  colspan="2" > <div id="enquirynote_div" style="text-align:center">
						 					  </div></td>
                    </tr>
					<tr id="addimage_tr">
              <td width="3%" class="seperationtd" ><img id="cat_imgtag_show" src="images/plus.gif" border="0" onclick="handle_expansion(this,'enquiry_note_view')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd">Click Here To View Note</td>
            </tr>
					 <tr>
                      <td colspan="2" id="enquirynote_show" style="display:none" >
					<div id="shownote_div" style="text-align:center">
						 					  </div>
					   </td>
					</tr>						  
                    
		</table>
      </td>
	  </tr>
		<tr>
          <td  valign="middle" class="tdcolorgray" align="center" colspan="4" >
		  <input type="hidden" name="enquiry_id" id="enquiry_id" value="<?=$enquiry_id?>" />
		  <input type="hidden" name="pass_search_name" id="pass_search_name" value="<?=$_REQUEST['pass_search_name']?>" />
		  <input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['pass_start']?>" />
		  <input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
		  <input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
		  <input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
		  <input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="update_enquiry" />
		  <input type="hidden" name="retdiv_id" id="retdiv_id" value="maincontent" />
		  <input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
		  <input type="hidden"  name="status_read"  id="status_read" value="1" />
		  
        </tr>
		<?
		$sqlp = "select p.product_name,p.product_actualstock,p.product_adddate,p.product_discount,p.product_weight, pr.products_product_id,pv.variable_name from products p ,product_enquiry_data pr,product_enquiry_data_vars pv where pr.product_enquiries_enquiry_id=".$row['enquiry_id']." AND p.product_id=pr.products_product_id AND pv.product_enquiry_data_id=pr.id";
		$resp=$db->query($sqlp);
		?>
      </table>
</form>	  
<script language="javascript"> 	
function display_prodtext(imgobj,row_id){
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
}
</script>
	

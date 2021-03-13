<?php
	/*#################################################################
	# Script Name 	: add_email_notify.php
	# Description 	: Page for adding Newsletter
	# Coded by 		: ANU
	# Created on	: 29-Aug-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'Email Notifications';
$help_msg = get_help_messages('ADD_EMAIL_NOTIFICATION');

$newsletter_id=($_REQUEST['newsletter_id']?$_REQUEST['newsletter_id']:$_REQUEST['newsletter_id']);

$sql = "SELECT number_newproducts, category_newproducts, number_discproducts, category_discproducts, 
			   set_senttype, week_day, month_date, product_select_type,  discount_from, discount_to
			   			FROM customer_email_notification 
								WHERE sites_site_id=$ecom_siteid AND  news_id='".$newsletter_id."'";
$res = $db->query($sql);
if($db->num_rows($res)==0)  { echo " <font color='red'> You Are Not Authorised  </a>"; exit; } 
$row = $db->fetch_array($res);								

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
	fieldRequired = Array('txt_num_prod','txt_num_discprod' );
	fieldDescription = Array('Number Of new Products','Number Of discount Products');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	var reqcnt = fieldRequired.length;

	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		if(frm.week_rad.checked==true && frm.sel_week.value=='0') 
		{
			alert("Please Select Week Day To Send Newsletter");
			frm.sel_week.focus();
		} else if(frm.week_rad.checked==true && frm.sel_day.value=='0')
		{
			alert("Please Select Day Of the Month To send Newsletter");
			frm.sel_day.focus();
		} else { 
			show_processing();
			//frm.fpurpose.value='insert';
			frm.submit();
		}
	} else {
		return false;
	}
}
function tempale_change() 
{
	document.frmAddNewsletter.fpurpose.value='add'; 
	document.frmAddNewsletter.submit();
}
function change_type()
{
	if(document.frmAddNewsletter.sel_prod_selection.value=='discount') {
		document.getElementById('daterange').style.display = '';
	} else {
		document.getElementById('daterange').style.display = 'none';
	}
}
</script>
<form name='frmAddNewsletter' action='home.php?request=email_notify'  method="post" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=email_notify&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Notifications </a> <a href="home.php?request=email_notify&fpurpose=edit&newsletter_id=<?=$newsletter_id?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Edit Notification</a><span> Notification Settings</span></div></td>
        </tr>
       <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="3">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		 <tr>
          <td colspan="3" align="center" valign="middle" ><?PHP echo notification_tabs('settings_tab_td',$newsletter_id) ?></td>
        </tr>
		<?php 
		if($alert)
		{			
		?>
       
        <tr>
          <td colspan="3" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
        <tr>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray" ><table width="100%" border="0">
            <tr>
              <td width="50%" valign="top"><table width="100%" border="0">
                <tr>
                  <td width="48%">No. of new Products to be displayed </td>
                  <td width="52%"><input type="text" name="txt_num_prod" size="7" value="<?PHP echo $row['number_newproducts']; ?>" /></td>
                </tr>
                <tr>
                  <td valign="top">Select Category to select Product </td>
                  <td>
				  <?php
			   		$cat_arr = $extcat_arr	= array();
					$extcat_arr = explode(",",$row['category_newproducts']);
			  		$cat_arr = generate_category_tree(0,0,false,true);
					echo generateselectbox('newprod_category_id[]',$cat_arr,$extcat_arr,'','',5); 
				  ?>				  </td>
                </tr>
              </table></td>
              <td width="50%" valign="top"><table width="100%" border="0">
                <tr>
                  <td width="50%" >No. of Discount Products to be displayed </td>
                  <td width="50%" colspan="2"><input type="text" name="txt_num_discprod" size="7" value="<?PHP echo $row['number_discproducts']; ?>" /></td>
                </tr>
                <tr>
                  <td valign="top">Select Category </td>
                  <td><?PHP 
				  $cat_arr1 = $extcat_arr	= array();
				  $extcat_arr1 = explode(",",$row['category_discproducts']);
				  $cat_arr1 = generate_category_tree(0,0,false,true);
				  echo generateselectbox('disc_category_id[]',$cat_arr1,$extcat_arr1,'','',5); ?></td>
                </tr>
                <tr>
                  <td>Select type of selection </td>
                  <td>
				  <?PHP echo $sel_prod_selection = $row['product_select_type']; ?>
				  <select name="sel_prod_selection" onchange="javascript:change_type()">
				  <option value="random" <?PHP if($sel_prod_selection=='random') echo "selected"; ?>>Pick Randomly</option>
				  <option value="discount" <?PHP if($sel_prod_selection=='discount') echo "selected"; ?>>Discount Range</option>				  
                  </select>                  </td>
                </tr>
                <tr id="daterange">
				<td colspan="2"><table width="100%"><tr>
                  <td align="center">From</td>
                  <td align="left"><input type="text" name="discount_from" value="<?PHP echo $row['discount_from']; ?>" /></td>
                  <td align="right">To</td>
                  <td align="left"><input type="text" name="discount_to" value="<?PHP echo $row['discount_to']; ?>"  /></td>
				</tr></table></td>
                </tr>
              </table></td>
            </tr>
          </table></td>
        </tr>
		<tr>
		   <td width="24%" align="left" valign="middle" class="tdcolorgray" >Send Notification Mails </td>
           <td width="76%" colspan="-2" align="left" valign="middle" class="tdcolorgray" >
		   	 <input name="week_rad" type="radio" value="week" <?PHP if($row['set_senttype'] == 'Week') echo 'checked'; ?> onclick="document.getElementById('weekid').style.display='';document.getElementById('monthid').style.display='none';" />
             Weekly 
             <input name="week_rad" type="radio" value="month" <?PHP if($row['set_senttype'] == 'Month') echo 'checked'; ?> onclick="document.getElementById('monthid').style.display='';document.getElementById('weekid').style.display='none';" />
          Monthly </td>
    </tr>
		 <tr id="weekid">
		   <td align="left" valign="top" class="tdcolorgray" >Please Select Week Day </td>
           <td colspan="-2" align="left" valign="top" class="tdcolorgray" >
		   <select name="sel_week">
		   <option value="0"> - Select - </option>
           <option value="Sun" <?PHP if($row['week_day']=='Sun') echo 'selected'; ?>  > Sunday </option>
		   <option value="Mon" <?PHP if($row['week_day']=='Mon') echo 'selected'; ?> > Monday </option>
		   <option value="Tue" <?PHP if($row['week_day']=='Tue') echo 'selected'; ?> > Tuesday </option>
		   <option value="Wed" <?PHP if($row['week_day']=='Wed') echo 'selected'; ?> > Wednesday </option>
		   <option value="Thu" <?PHP if($row['week_day']=='Thu') echo 'selected'; ?> > Thursday </option>		   		   		   		   
		   <option value="Fri" <?PHP if($row['week_day']=='Fri') echo 'selected'; ?> > Friday </option>		   		   		   		   		   
		   <option value="Sat" <?PHP if($row['week_day']=='Sat') echo 'selected'; ?> > Saturday </option>		   		   		   		   		   
           </select>		   </td>
    </tr>
		 <tr id="monthid">
           <td align="left" valign="middle" class="tdcolorgray" >Please Select Day Of Month </td>
		   <td colspan="-3" align="left" valign="middle" class="tdcolorgray">
		   <? $sel_day= $row['month_date']; ?>
		  <select name="sel_day">
		  <option value="0"> - Select -  </option>
		  <?PHP
		  	for($i=1; $i<=28; $i++) {
				echo "<option value='$i'";
				if($sel_day==$i) echo 'Selected';
				echo "> $i </option>";
			}
		  ?>
		  </select>		   </td>
    </tr>
		 
		 
		 
		 <tr>
		   <td colspan="3" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
		 </tr>
	    <tr>
          <td colspan="3" align="center" valign="middle" class="tdcolorgray" >
		   <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
		   <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
		   <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
		   <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
		   <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		   <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" /> 
		   <input type="hidden" name="newsletter_id" id="newsletter_id" value="<?=$_REQUEST['newsletter_id']?>"  />
		   <input type="hidden" name="fpurpose" id="fpurpose" value="settings_update"  />
		   <input name="Submit" type="button" class="red" value=" Continue " onclick="valform(frmAddNewsletter)" /></td>
        </tr>
  </table>
</form>	  
	<?PHP if($row['set_senttype'] == 'Week') { ?>
<script type="text/javascript">
	//handletype_change('');
	document.getElementById('weekid').style.display = '';
	document.getElementById('monthid').style.display = 'none';	
</script>
<? } else { ?>
<script type="text/javascript">
	//handletype_change('');
	document.getElementById('weekid').style.display = 'none';
	document.getElementById('monthid').style.display = '';	
</script>
<? } ?>
<?PHP if($row['product_select_type']=='discount') { ?>
<script type="text/javascript">
	//handletype_change('');
	document.getElementById('daterange').style.display = '';

</script>
<? } else { ?>
<script type="text/javascript">
	//handletype_change('');
	document.getElementById('daterange').style.display = 'none';

</script>

<? } ?>


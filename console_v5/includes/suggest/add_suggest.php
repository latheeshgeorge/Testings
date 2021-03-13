<?php
	/*#################################################################
	# Script Name 	: add_callback.php
	# Description 	: Page for adding Customer
	# Coded by 		: Lathhesh
	# Created on	: 03-Sep-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'Suggestion';
$help_msg = 'Technical Support form

For a fast response to any problems you may be facing with the set-up program, we advise all customers to fill out the form below. We guarantee to respond to your query within 24hrs.'; 

?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{   
	if(frm.service.value<0) {
		alert("Please Enter Service");
		frm.service.focus();
	}
	fieldRequired = Array('email','title','comments');
	fieldDescription = Array('Email','Title','Comments');
	fieldEmail = Array('email');
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


function changefeature()
{
	var retdivid='feature';
	var service_id;
	var fpurpose;
	service_id=document.frmAddsuggest.service.value;
	
	if(document.frmAddsuggest.service.value==-1 || document.frmAddsuggest.service.value==0) {
		document.getElementById("feat_id").style.display='none';
	} else {
		document.getElementById("feat_id").style.display='';
	}
 	
	if(service_id > 0)
	{
		document.getElementById("feat_id").style.display='';
		fpurpose	= 'list_feature';
		document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */
		retobj 											= eval("document.getElementById('"+retdivid+"')");
		retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';															
		var qrystr										= '';
		/* Calling the ajax function */
		Handlewith_Ajax('services/suggest.php','fpurpose='+fpurpose+'&'+qrystr+'&service_id='+service_id);
	}
	else
	{
		
		document.getElementById('feature').style.display='none';
	}
}
function ajax_return_contents() 
{
	var ret_val = '';
//	var retdivid='feature';
	var disp 	= 'no';
	
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			document.getElementById("feature").style.display='';
			ret_val 	= req.responseText;
			targetdiv 	= document.getElementById('retdiv_id').value;
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			hide_processing();
		}
		else
		{
			 show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}
</script>
<form name='frmAddsuggest' action='home.php?request=suggest' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd">
			  <div class="treemenutd_div"><a href="home.php">Home</a> <span> Add Suggestion </span></td>
        </tr>
        <tr>
          <td colspan="2" align="left" valign="middle" class="helpmsgtd" ><?=$help_msg ?></td>
        </tr>
		<?php 
		if($alert)
		{			
		?>
        <tr>
          <td colspan="2" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
		 <tr>
		   <td colspan="2" class="helpmsgtd" align="left"><div class="helpmsg_divcls">Please note, customers who call customer services without first filling in this form will be advised to fill this form first.</div></td>
    </tr>
		<?php 
		/*
		 <tr>
		<td colspan="2" class="helpmsgtd" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please Enter Following Options </td>
		</tr>
		*/ ?> 
		<tr>
		<td valign="top"  class="tdcolorgrayleft" colspan="2">
		   <div class="editarea_div">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
          <td width="29%" align="left" valign="middle" class="tdcolorgray" >&nbsp;<strong>What is the nature of your problem ?</strong><span class="redtext"> *</span></td>
          <td width="71%" align="left" valign="middle" class="tdcolorgray">  </td>
        </tr>
		<tr>
		  <td colspan="2" align="left" valign="middle" class="tdcolorgray" style="padding-left:35px;" ><strong>&nbsp;</strong>&nbsp;
		  <select class="input" name="service" onchange="changefeature()" >
		  <option value="-1">-select-</option>
		  <?PHP
		  	$sql = "SELECT service_id, service_name 
							FROM services 
								WHERE hide='0' 
								    ORDER BY ordering";
			$res = $db->query($sql);
			while($row = $db->fetch_array($res)) {
				echo "<option value='$row[service_id]'";
				if($service==$row['service_id']) echo "selected";
				echo ">$row[service_name]</option>";
			}
		  ?>
		  <option value="0"> Other </option>
		  </select>		</td>
		  </tr>
		<tr id="feat_id" style="display:none;">  <? //if( (!$alert) || (!$_REQUEST['feature']) ) {  ?>  <? //} ?>
          <td width="29%" align="left" valign="middle" class="tdcolorgray" >&nbsp;<strong>Feature</strong></td>
          <td width="71%" align="left" valign="middle" class="tdcolorgray">
		   </td>
        </tr>
		<tr>
		  <td colspan="2" align="left" valign="top" class="tdcolorgray" style="padding-left:35px;"><div id="feature" >&nbsp;		</div>
		  </td>
		  </tr>
		<tr>
          <td align="left" valign="top" class="tdcolorgray" ><strong>&nbsp;Email Address<span class="redtext">*</span></strong></td>
		  <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		  </tr>
		<tr>
		  <td colspan="2" align="left" valign="middle" class="tdcolorgray" style="padding-left:35px;" ><strong>&nbsp;</strong>&nbsp;
		    <input class="input" type="text" name="email"  value="<?=$_REQUEST['email']?>" size="48" /></td>
		  </tr>
		<tr>
          <td width="29%" align="left" valign="middle" class="tdcolorgray" >&nbsp;<strong>Title <span class="redtext">*</span></strong></td>
          <td width="71%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
		<tr>
		  <td colspan="2" align="left" valign="top" class="tdcolorgray" style="padding-left:35px;" ><strong>&nbsp;</strong>&nbsp;
		  <input class="input" type="text" name="title"  value="<?=$_REQUEST['title']?>" size="48" /></td>
		  </tr>
		<tr>
		  <td colspan="2" align="left" valign="top" class="tdcolorgray" ><strong>Please explain in depth, the exact details of your problem? <span class="redtext">*</span></strong></td>
		  </tr>
		<tr>
          <td colspan="2" align="left" valign="top" class="tdcolorgray" style="padding-left:35px;" ><strong>&nbsp;</strong>&nbsp;
		    <textarea class="input"  name="comments" rows="10" cols="55"  /><?=$_REQUEST['comments']?></textarea>		  </td>
          </tr> 
		</table></div>		</td>
		</tr>
		<tr>
          <td  align="right" valign="middle" class="" colspan="2">
		  <div class="editarea_div">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
          <td  align="right" valign="middle" class="tdcolorgray">
		  <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
		  <input type="hidden" name="search_email" id="search_email" value="<?=$_REQUEST['search_email']?>" />
		   <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
		  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
		  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
		  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
		  <input type="hidden" name="status" id="status" value="<?=$status?>" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
		  <input type="hidden" name="retdiv_id" id="retdiv_id" value="" />
		  <input name="Submit" type="submit" class="red" value=" Send " /></td>
        </tr>
        </table>
        </div>
        </td>
        </tr>
  </table>
</form>	  
 <? /* if($feature) { echo "<script language='javascript'>
		  
		  changefeature() </script>"; } */ ?>

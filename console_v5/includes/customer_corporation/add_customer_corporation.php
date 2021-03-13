<?php
	/*#################################################################
	# Script Name 	: add_customer_corporation.php
	# Description 	: Page for adding Customer Corporation
	# Coded by 		: ANU
	# Created on	: 15-Aug-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'Business Customer';
$help_msg = get_help_messages('ADD_CUST_CORP_MESS1');

?>	
<script language="javascript" type="text/javascript">

function valform(frm)
{
	fieldRequired = Array('corporation_name');
	fieldDescription = Array('Business Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('corporation_discount');
	
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		 <?php /*?>if(frm.corporation_discount.value>99) {
			alert("Discount Value Should be less than 100%");
			return false;
		} 
		else if(frm.corporation_costplus.value>99) {
			alert("Corporation Costplus Value Should be less than 100%");
			return false;
		}<?php */?>
		show_processing();
		return true;
	} else {
		return false;
	}
}
</script>
<form name='frmAddCustomerCorporation' action='home.php?request=customer_corporation' enctype="multipart/form-data"  method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=customer_corporation&amp;sort_by=<?=$_REQUEST['pass_sort_by']?>&amp;sort_order=<?=$_REQUEST['pass_sort_order']?>&amp;records_per_page=<?=$_REQUEST['pass_records_per_page']?>&amp;search_name=<?=$_REQUEST['pass_search_name']?>&amp;start=<?=$_REQUEST['pass_start']?>&amp;pg=<?=$_REQUEST['pass_pg']?>">List Business Customers </a><span> Add Business Customer</span></div></td>
    </tr>
    <tr>
	  <td align="left" valign="middle" class="helpmsgtd_main">
	  <?php 
		  Display_Main_Help_msg($help_arr,$help_msg);
	  ?>	 </td>
	</tr>
    <?php 
		if($alert)
		{			
		?>
    <tr>
      <td align="center" valign="middle" class="errormsg" ><?=$alert?></td>
    </tr>
    <?
		}
		?>
	 <tr>
      <td align="center" valign="middle" class="sorttd" >
	  <div class="sorttd_div" >
	   <table class="tdcolorgray" width="100%" cellpadding="0" cellspacing="0">
    <tr>
      <td width="14%" align="left" valign="middle" class="tdcolorgray" >Business Name <span class="redtext">*</span> </td>
      <td width="32%" align="left" valign="middle" class="tdcolorgray"><input name="corporation_name" type="text" id="corporation_name" value="<?=$_REQUEST['corporation_name']?>"  maxlength="100"/></td>
      <td width="17%" align="left" valign="middle" class="tdcolorgray">Reg No </td>
      <td width="37%" align="left" valign="middle" class="tdcolorgray"><input name="corporation_regno" type="text" id="txt_imgloc2"  value="<?php  echo $_REQUEST['corporation_regno'];?>" /></td>
    </tr>
    <tr  >
      <td align="left" valign="middle" class="tdcolorgray" >Business Type</td>
      <td align="left" valign="middle" class="tdcolorgray"><?php
					  	$type_arr = array('Sole Trade'=>'Sole Trade','Partership'=>'Partership','Limited'=>'Limited');
						echo generateselectbox('corporation_type',$type_arr,$row['corporation_type'],'','handletype_change(this.value)');
					  ?>&nbsp;
					  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_CUST_CORP_TYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
      <td align="left" valign="middle" class="tdcolorgray">Vat No </td>
      <td align="left" valign="middle" class="tdcolorgray"><input name="corporation_vatno" type="text" id="corporation_vatno"  value="<?php  echo $_REQUEST['corporation_vatno'];?>" /></td>
    </tr>
   <?php /* <tr  >
      <td align="left" valign="middle" class="tdcolorgray" >Discount (%)<?php //Discount Method ?> </td>
      <td align="left" valign="middle" class="tdcolorgray"><input name="corporation_discount" type="text" id="corporation_discount"  value="<?php  echo $_REQUEST['corporation_discount'];?>" />
     <input type="hidden" name='corporation_discount_method' id='corporation_discount_method' value='Discount' />
      
      <?php
  					  	$type_arr = array('Discount'=>'Discount','Cost Plus'=>'Cost Plus');
						//echo generateselectbox('corporation_discount_method',$type_arr,$_REQUEST['corporation_discount_method'],'','handletype_change(this.value)');
					  ?>&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_CUST_CORP_DISCMTHD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
      <td align="left" valign="middle" class="tdcolorgray" >Allow Product Discount </td>
      <td align="left" valign="middle" class="tdcolorgray">
	  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_CUST_CORP_PRODDISC')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    </tr>
    <?php <tr  id="tr_disc"  >
      <td align="left" valign="middle" class="tdcolorgray"> </td>
      <td align="left" valign="middle" class="tdcolorgray"></td>
      <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
      <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
    </tr>
    <tr id="tr_cost" >
      <td width="14%" align="left" valign="middle" class="tdcolorgray"  >Cost Plus </td>
      <td width="32%" align="left" valign="middle" class="tdcolorgray"><input name="corporation_costplus" type="text" id="corporation_costplus"  value="<?php  echo $_REQUEST['corporation_costplus'];?>" />
      (%)</td>
      <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
      <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
    </tr>
    */?>
    <tr>
      <td align="left" valign="top" class="tdcolorgray" >Other Details </td>
      <td colspan="3" align="left" valign="middle" class="tdcolorgray" ><textarea name="corporation_otherdetails" cols="35" rows="5" id="otherdetails"></textarea></td>
    </tr>
	</table>
	</div>	</td>
	</tr>
    <tr>
      <td align="right" valign="middle" class="tdcolorgray">
		   <div class="sorttd_div" >
		   <table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="right" valign="middle" class="tdcolorgray" >
					<input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
					<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
					<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
					<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
					<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
					<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
					<input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
					<input type="hidden" name="retdiv_id" id="retdiv_id" value="maincontent" />
					<input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
					<input name="corporation_discount" type="hidden" id="corporation_discount"  value="0" />
					<input type="hidden" name='corporation_discount_method' id='corporation_discount_method' value='Discount' />
					<input class="input" type="hidden" name="corporation_allow_product_discount"  value="1"/>
					<input name="Submit" type="submit" class="red" value="Submit" />				</td>
			</tr>
			</table>
			</div>		</td>			
    </tr>
  </table>
  
</form>	  
<script type="text/javascript">
function handletype_change(vals)
{	
	if (vals=='')
		vals = 'Discount';
	switch(vals)
	{
		case 'Discount':
			document.getElementById('tr_disc').style.display = '';
			document.getElementById('tr_cost').style.display = 'none';
			
		break;
		case 'Cost Plus':
			document.getElementById('tr_disc').style.display = 'none';
			document.getElementById('tr_cost').style.display = '';
		
		break;
		
	};
}
	<?php /*handletype_change('<?=$_REQUEST['corporation_discount_method']?>');*/?>
</script>

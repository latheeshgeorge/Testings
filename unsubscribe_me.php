<?php
 if ($_REQUEST['unsub_button'])
 {
 	
 
 }
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Discount Mobility - Unsubscribe</title>
<meta http-equiv="Content-type" content="text/html; charset=Windows-1252" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<link href="http://www.discount-mobility.co.uk/images/www.discount-mobility.co.uk/css/discountmobility_new.css" media="screen" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="http://www.discount-mobility.co.uk/scripts/validation.js"></script>
<script type="text/javascript" src="http://www.discount-mobility.co.uk/images/www.discount-mobility.co.uk/scripts/javascript.js"></script>
<script type="text/javascript" src="http://www.discount-mobility.co.uk/images/www.discount-mobility.co.uk/scripts/date_picker.js"></script>
<script  type="text/javascript" src="http://www.discount-mobility.co.uk/images/www.discount-mobility.co.uk/scripts/jquery.js" ></script>
<script  type="text/javascript" src="http://www.discount-mobility.co.uk/images/www.discount-mobility.co.uk/scripts/jquery-ui-1.10.1.custom.js" ></script>
<script  type="text/javascript" src="http://www.discount-mobility.co.uk/images/www.discount-mobility.co.uk/scripts/jquery.innerfade.js" ></script>
<link href="http://www.discount-mobility.co.uk/images/www.discount-mobility.co.uk/css/searchfilter/jquery-ui.css" media="screen" type="text/css" rel="stylesheet" />
</head>
<body>
<div class="div_alert_main" id="alert_main_div" style="display:none" ></div>
<div class='external_main_wrapper'>
<center>
<div class="processing_divcls" id="processing_div" style="display:none;" align="center">
<br/>
Processing Please wait ...
<br/><br/>
</div>
<form name="frm_forcesubmit" id="frm_forcesubmit" action="" method="post" class="frm_cls">
<input type="hidden" name="prodimgdet" id="prodimgdet" value="" />
<input type="hidden" name="prod_curtab" id="prod_curtab" value="" />
<input type="hidden" name="submit_Compare_pdts" id="submit_Compare_pdts" value="1" />
<input type="hidden" name="compare_products" id="compare_products" value="" />
</form>
<form name="common_compare_list" id="common_compare_list" action="" method="post" class="frm_cls">
<input type="hidden" name="prodimgdet" id="prodimgdet" value="" />
<input type="hidden" name="prod_curtab" id="prod_curtab" value="" />
<input type="hidden" name="remove_compareid"  value="" />
</form>
<div class="proddet_loading_div_ajax" id="proddet_loading_div_ajax" style="height:15px;display:none;padding:5px;" >
<img src="http://www.discount-mobility.co.uk/images/www.discount-mobility.co.uk/site_images/ajax-loader_cart.gif" alt="loading..." >
</div>
<script type = "text/javascript">


function validate_allforms(frm)
{
fieldRequired 		= Array('txt_unemail');
fieldDescription 	= Array('Email id');	
fieldEmail 			= Array('txt_unemail');	
fieldConfirm 		= Array();
fieldConfirmDesc 	= Array();
fieldSpecChars 	= Array();
fieldCharDesc 	= Array();	
fieldNumeric 		= Array();
if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars,fieldCharDesc))
{
return true;
}
else
{
return false;	
}
}
</script>	
<div id="div_defaultFlash_outer" class="flashvideo_outer" style="display: none;"></div>
<div  id="cart_details_ajaxholder" ></div>
<div  id="prod_details_ajaxholder" ></div>
<table border="0" cellpadding="0" cellspacing="0" class="main">
<tr>
<td colspan="2" class="maintoptd" style="vertical-align:bottom;text-align:left">
<img src="http://www.discount-mobility.co.uk/images/www.discount-mobility.co.uk/site_images/logo.gif" border="0">
</td>
</tr>	
<tr>
<td colspan="2" class="maintoplink" style="text-align:right;vertical-align:top;height:500px">
<form method="post" action="" onsubmit="return validate_allforms(this)" name="frm_unscribe" id="frm_unscribe">
<table width="100%" border="0">
<tr>
<td colspan="4">&nbsp;</td>
</tr>
<tr>
<td width="24%">&nbsp;</td>
<td width="1%">&nbsp;</td>
<td colspan="2">&nbsp;</td>
</tr>
<tr>
<td align="right">&nbsp;</td>
<td align="left">&nbsp;</td>
<td colspan="2">&nbsp;</td>
</tr>
<tr>
<td align="right">&nbsp;</td>
<td align="left">&nbsp;</td>
<td colspan="2">&nbsp;</td>
</tr>
<tr>
<td align="right">&nbsp;</td>
<td align="left">&nbsp;</td>
<td colspan="2">&nbsp;</td>
</tr>
<?php
 if ($_REQUEST['unsub_button'])
 {
 ?>
 <tr>
	<td colspan="4" align="center" class="regiheader">Your request has been send.</td>
</tr>
<?php 
 }
 else
 {
?>
<tr>
<td colspan="3" align="center" class="regiheader">Enter you email address to unsubscribe from the mailing list</td>
<td align="left" class="regiheader">&nbsp;</td>
</tr>
<tr>
<td align="right">&nbsp;</td>
<td align="left">&nbsp;</td>
<td colspan="2">&nbsp;</td>
</tr>
<tr>
<td align="right" class="regiconent">Email Address</td>
<td align="left" class="regiconent">&nbsp;</td>
<td colspan="2" align="left">	
<input name="txt_unemail" type="text" id="txt_unemail" size="60" /></td>
</tr>
<tr>
<td align="right">&nbsp;</td>
<td align="left">&nbsp;</td>
<td colspan="2">&nbsp;</td>
</tr>
<tr>
<td align="right">&nbsp;</td>
<td align="left">&nbsp;</td>
<td width="29%" align="center"><input name="unsub_button" type="submit" class="buttonblackbig" id="unsub_button" value="Unsubscribe" /></td>
<td width="46%" align="left">&nbsp;</td>
</tr>
<?php
}
?>
</table>
</form>
</td>
</tr>
<tr>
<td colspan="2" class="bottomcopyright" style="text-align:right;vertical-align:top"><a href="http://www.thewebclinic.co.uk/e-commerce.html" class="copyrightlink" title="ecommerce" target="_blank">ecommerce solutions</a> and  <a href="http://www.thewebclinic.co.uk/seo.html" class="copyrightlink" title="Search engine optimisation" target="_blank">SEO Services</a> from <a href="http://www.thewebclinic.co.uk/" class="copyrightlink" title="The Web Clinic" target="_blank">The Web Clinic</a>. Copyright &copy; 2015.</td>
</tr>
</table>
</center>
</div>
</body>
</html>
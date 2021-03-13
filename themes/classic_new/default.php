<?php
	/*#################################################################
	# Script Name 	: default.php
	# Description 	: This is the default page for the theme.
	# Coded by 		: Sny
	# Created on	: 05-Dec-2007
	# Modified by	: Sny
	# Modified On	: 10-Jan-2008
	#################################################################*/
	// Layout Type
	$default_layout 		= '3col';
	// Including the website layout settings cached file (if any)
	$layout_cache_file_included = false;
	$filename = $image_path.'/cache/websitelayout/'.$default_layout.'.php';
	if(file_exists($filename))
	{
		include_once "$filename";
		$layout_cache_file_included = true;
	}

	// Calling the function to get all the captions set in the COMMON section
	$Captions_arr['COMMON']				= getCaptions('COMMON');
	// Get the captions for price
	$Captions_arr['PRICE_DISPLAY']	= getCaptions('PRICE_DISPLAY');

	// Calling the function to get the details of default currency
	$default_Currency_arr					= get_default_currency();

	// Assigning the current currency to the variable
	$sitesel_curr								= get_session_var('SITE_CURR');
	
	if($_REQUEST['cbo_selcurrency'])
	{
		$sitesel_curr = $_REQUEST['cbo_selcurrency'];
		set_session_var('SITE_CURR',$sitesel_curr);
		//Finding the symbol for current currency
		$sql_curr  	= "SELECT curr_sign_char FROM general_settings_site_currency WHERE currency_id = $sitesel_curr";
		$ret_curr	= $db->query($sql_curr);
		if($db->num_rows($ret_curr))
		{
			$row_curr  			= $db->fetch_array($ret_curr);
			$sitesel_currsign 		= $row_curr['curr_sign_char'];
		}
		set_session_var('SITE_CURR_SIGN',$sitesel_currsign);
		//clear_all_cache();
	}
	// If sitesel_curr have no value then set it as the default currency
	if (!$sitesel_curr)
	{
		$sitesel_curr		= $default_Currency_arr['currency_id'];// setting the default currency value
		//clear_all_cache();
	}
	// Get details of current currency
	$current_currency_details = get_current_currency_details();
	// Including the page which validates various section such as newsletter signup, bestsellers, login etc
	require ("actions.php");
	// Handling the case of logout
	if($_REQUEST['req'] == "logout")
	{
		clear_session_var('ecom_login_customer');
		clear_session_var('ecom_cust_group_exists');
		clear_session_var('ecom_cust_group_prod_array');
		clear_session_var('ecom_cust_group_array');
		clear_session_var('ecom_cust_direct_exists');
		clear_session_var('ecom_cust_direct_disc');
		// Check whether logout is coming by clicking the logout link in cart page. if so redirect to cart page itself
		if ($_REQUEST['rets']==1)
		{
	?>
			<script language="javascript" type="text/javascript">window.location ='http://<?php echo $ecom_hostname?>/cart.html';</script>
	<?php
		}
		else if ($_REQUEST['rets']==2)
		{
	?>
			<script language="javascript" type="text/javascript">window.location ='http://<?php echo $ecom_hostname?>/enquiry.html';</script>
	<?php
		}
		else
		{
	?>
			<script language="javascript" type="text/javascript">document.location.replace("/")</script>
	<?
		}
		exit;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?
	//ho $_SERVER['HTTP_USER_AGENT'];
	//if(strpos($_SERVER['HTTP_USER_AGENT'],'Linux')==false)
		require("head.php");
	//else
	//	require("head_new.php");
	// Including the header file
// Check whether a fraud click is registered
$fraud_detection = get_session_var('COST_PER_CLICK_FRAUD');
// Resetting the fraud click session variable to 0
set_session_var('COST_PER_CLICK_FRAUD',0);
if($fraud_detection==1) {
?>
<style>
.transparent
{
   filter:alpha(opacity=70); 
   -moz-opacity: 0.70; 
   opacity: 0.70; 
   z-index:1000;
   display:none;
   position:absolute;
   left:0;
   top:0;
   margin:0;
   width:100%;
   height:100%;
   background-color:#f2f2f2;
}
.def_div{
display:none;
position:absolute;
left:0;
top:0;
padding-top:12%;
width:100%;
z-index:2000;
}
</style>
<script language="javascript" type="text/javascript">
function  helpmsg() {
	document.getElementById('helpdiv').style.display='block';
	document.getElementById('def_content').style.display='block';
}
function  helpmsClse() {
	document.getElementById('helpdiv').style.display='none';
	document.getElementById('def_content').style.display='none';
}
</script>
<?php
}
/*if($ecom_siteid==60) // doorhandles
{
	
?>
<script type="text/javascript">
    var mobiletarget = "http://scoot.mymcart.com/m/locks-handles";
    document.write("<scr"+"ipt src='//www.mymcart.com/javascript/detect-mobile.js?tm="+ new Date().getTime()+"'><\/script>");
</script> 
<?php
}
if($ecom_siteid==60)
{
	?>
	<script type="text/javascript" src="http://81.94.198.10/js/16284.js"></script> 
	<?php
}
*/
?>
<link href="<?php echo url_head_link("images/".$ecom_hostname."/".$css_folder."/".$ecom_themename."_print.css",1)?>" media="print" type="text/css" rel="stylesheet" />
</head>
<body>
<div class="div_alert_main" id="alert_main_div" style="display:none" ></div>
<?php

if($fraud_detection==1) {
?>
<div class="transparent" id="helpdiv" ></div>
<div id="def_content" class="def_div" align="center">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="width:642px;">
  <tr>
    <td align="left" valign="top" style="width:148px;"><img src="http://<?=$ecom_hostname?>/images/<?=$ecom_hostname?>/fraud_images/warn.gif" width="148" height="85" alt="" /></td>
    <td align="left" valign="middle" style="background-color:#b3dcf1;width:478px;"><div style="padding:8px 0;" align="right"> <a href="#" onClick="javascript:helpmsClse()"><img src="http://<?=$ecom_hostname?>/images/<?=$ecom_hostname?>/fraud_images/close.gif" border="0" /></a></div>
        <div style="padding:4px 0;font-family:Arial, Helvetica, sans-serif;font-weight:bold;color:#000000;font-size:12px;"><?=$ecom_hostname?></div></td>
    <td align="right" valign="top" style="width:19px;"><img src="http://<?=$ecom_hostname?>/images/<?=$ecom_hostname?>/fraud_images/topr.gif" width="19" height="85" alt="" /></td>
  </tr>
  <tr>
    <td align="right" valign="top" style="background-color:#b3dcf1;width:148px;"><img src="http://<?=$ecom_hostname?>/images/<?=$ecom_hostname?>/fraud_images/shield_icon.gif" width="106" height="158" alt="" /></td>
    <td align="left" valign="top"  style="background-color:#b3dcf1;width:478px;"><div style="padding:5px 0;font-family:Arial, Helvetica, sans-serif;font-weight:bold;color:#FFFFFF;font-size:12px;">Thank you for visiting our website - we are very glad you find it useful.</div>
        <div style="padding:5px 0;font-family:Arial, Helvetica, sans-serif;font-weight:normal;color:#1f74aa;font-size:12px;">We have noticed that you have visited our site many times recently by clicking on adverts. In order to keep costs down and make sure that we can pass the savings on to you, our customer, we would like to ask that you bookmark our site.</div>
      <div style="padding:5px 0;font-family:Arial, Helvetica, sans-serif;font-weight:normal;color:#1f74aa;font-size:12px;">To bookmark our site, please <a href="javascript:window.external.AddFavorite(location.href, document.title);">click here</a>.</div>
      <div style="padding:5px 0;font-family:Arial, Helvetica, sans-serif;font-weight:normal;color:#1f74aa;font-size:12px;">Please note that your IP address has been logged: <?=$_SERVER['REMOTE_ADDR']?></div></td>
    <td align="left" valign="top" style="background-color:#b3dcf1;width:19px;">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="bottom" style="width:148px;"><img src="http://<?=$ecom_hostname?>/images/<?=$ecom_hostname?>/fraud_images/bottom.gif" width="148" height="24" alt="" /></td>
    <td align="right" valign="middle" style="background-color:#b3dcf1;width:478px;font-family:Arial, Helvetica, sans-serif;font-weight:normal;color:#4b99cb;font-size:10px;">Invalid click detection provided by BSHOP V4.0</td>
    <td align="left" valign="top" style="width:19px;"><img src="http://<?=$ecom_hostname?>/images/<?=$ecom_hostname?>/fraud_images/bottomr.gif" width="19" height="24" alt="" /></td>
  </tr>
</table>
</div>
<?php
}
?>
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
	<?php
	if($_REQUEST['disp_id']) 
	{ ?>
		<input type="hidden" name="disp_id" id="disp_id" value="<?=$_REQUEST['disp_id']?>" />
	<?
	}

//End 
if($_REQUEST['req']=='search')
{
?>
	<input type="hidden" name="quick_search" value="<?=$_REQUEST['quick_search']?>" />
	<input type="hidden" name="search_category_id" value="<?=$_REQUEST['search_category_id']?>" />
	<input type="hidden" name="search_model" value="<?=$_REQUEST['search_model']?>" />
	<input type="hidden" name="search_minstk" value="<?=$_REQUEST['search_minstk']?>" />
	<input type="hidden" name="search_minprice" value="<?=$_REQUEST['search_minprice']?>" />
	<input type="hidden" name="search_maxprice" value="<?=$_REQUEST['search_maxprice']?>" />
	<input type="hidden" name="searchVariableName" value="<?=$_REQUEST['searchVariableName']?>" />
	<input type="hidden" name="searchVariableOption" value="<?=$_REQUEST['searchVariableOption']?>" />
	<input type="hidden" name="cbo_keyword_look_option" value="<?=$_REQUEST['cbo_keyword_look_option']?>" />
	<input type="hidden" name="rdo_mainoption" value="<?=$_REQUEST['rdo_mainoption']?>" />
	<input type="hidden" name="rdo_suboption" value="<?=$_REQUEST['rdo_suboption']?>" />


	<?php 
   //Section for making hidden values labels
	if(count($_REQUEST['search_label_value'])>0){
		foreach($_REQUEST['search_label_value'] as $v)
		{
			?>
		<input type="hidden" name="search_label_value[]" value="<?=$v?>"   />
			<?
		}	
	}

}
?>
</form>
<form name="common_compare_list" id="common_compare_list" action="" method="post" class="frm_cls">
	<?php /*?><input type="hidden" name="prodimgdet" id="prodimgdet" value="" />
	<input type="hidden" name="prod_curtab" id="prod_curtab" value="" /><?php */?>
	<input type="hidden" name="remove_compareid"  value="" />
<?   
	if($_REQUEST['disp_id']) 
	{ ?>
	<input type="hidden" name="disp_id" id="disp_id" value="<?=$_REQUEST['disp_id']?>" />
	<?
	}
//End 
if($_REQUEST['req']=='search')
{
?>
	<input type="hidden" name="quick_search" value="<?=$_REQUEST['quick_search']?>" />
	<input type="hidden" name="category_id" value="<?=$_REQUEST['category_id']?>" />
	<input type="hidden" name="search_model" value="<?=$_REQUEST['search_model']?>" />
	<input type="hidden" name="search_minstk" value="<?=$_REQUEST['search_minstk']?>" />
	<input type="hidden" name="search_minprice" value="<?=$_REQUEST['search_minprice']?>" />
	<input type="hidden" name="search_maxprice" value="<?=$_REQUEST['search_maxprice']?>" />
	<input type="hidden" name="searchVariableName" value="<?=$_REQUEST['searchVariableName']?>" />
	<input type="hidden" name="searchVariableOption" value="<?=$_REQUEST['searchVariableOption']?>" />
	<?php 
	//Section for making hidden values labels
	if(count($_REQUEST['search_label_value'])>0)
	{
		foreach($_REQUEST['search_label_value'] as $v)
		{
		?>
		<input type="hidden" name="search_label_value[]" value="<?=$v?>"   />
		<?
		}	
	}
}
?>
</form>
<table border="0" cellpadding="0" cellspacing="0" class="main">
<?php
	// Check whether top table exists for current site. If exists then include it
	$toptable="./images/".$ecom_hostname."/otherfiles/toptable.php";
	if(file_exists($toptable))
	{
      include("$toptable");
	}
	// Decide whether to display the top menu or not
	if(get_session_var('ecom_login_customer'))
	{
		include "themes/$ecom_themename/modules/mod_topmenu.php";
	}
?>
  <tr>
    <td align="left" valign="top" class="compleftcontainer">
	<?php
		// Showing the left components
		$position = 'left';
		include("Components.php");
	?>

   </td>
   <td align="left" valign="top" class="compmiddlecontainer">
	<?php
   	// Including the page which decides the display logic for the middle section
   	include ("mainbody.php");
   ?>
   </td>
      <td align="left" valign="top" class="comprighttcontainer">
	  <?php
		// Showing the left components
		$position = 'right';
		include("Components.php");
	?></td>
    </tr>
	  <?php
		// Check whether bottom table exists for current site. If exists then include it
		$bottomtable="./images/".$ecom_hostname."/otherfiles/bottomtable.php";
		if(file_exists($bottomtable))
		{
		  include("$bottomtable");
		}
	?>
      </table>
<?php 
	//if($protectedUrl==false)
	//{
		// ##################################################################################################
		// Calling the function to display the web tracker bottom script. This is the webtracker from b-1st 
		// ##################################################################################################
		//get_WebtrackerBottomScript(); 
	//}
	
	// #######################################################################
	// google webtracker script
	// #######################################################################
	if($ecom_iswebtracker)
	{
?>
		<script type="text/javascript">
		var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
		document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
		</script>
		<script type="text/javascript">
		var pageTracker = _gat._getTracker("<?php echo $ecom_webtrackercode?>"); <?php /*UA-3702067-1*/?>
		pageTracker._initData();
		pageTracker._trackPageview();
		</script>
<?php
	}	
	// #######################################################################
	// urchin google webtracker code
	// #######################################################################
	if($ecom_isurchinwebtracker and $protectedUrl==false)
	{
?>
	
		<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
		</script>
		<script type="text/javascript">
		_uacct = "<?php echo $ecom_urchinwebtrackercode?>"; <?php /*UA-4022502-1*/?>
		urchinTracker();
		</script>
<?php		
	}
?>
  </center>
<?php
if($fraud_detection==1) {
?>
<script language="javascript" type="text/javascript"> helpmsg() </script>
<?php
}
?>
<?php
if(trim($ecom_footer_script)!='' and $protectedUrl==false)
	echo stripslashes($ecom_footer_script);

echo "
    <div id=\"bw\" style=\"display:none;\"></div>"; 	

if($ecom_siteid==60 and $_REQUEST['req']=='')
{	/*
?>
	<div id="eXTReMe"><a href="http://extremetracking.com/open?login=doorhand" target="_blank" rel="nofollow"><img src="http://t1.extreme-dm.com/i.gif" style="border: 0;" height="38" width="41" id="EXim" alt="eXTReMe Tracker" /></a> <script type="text/javascript"><!-- EXref="";top.document.referrer?EXref=top.document.referrer:EXref=document.referrer;//--> </script><script type="text/javascript"><!-- var EXlogin='doorhand' // Login var EXvsrv='s10' // VServer EXs=screen;EXw=EXs.width;navigator.appName!="Netscape"? EXb=EXs.colorDepth:EXb=EXs.pixelDepth;EXsrc="src"; navigator.javaEnabled()==1?EXjv="y":EXjv="n"; EXd=document;EXw?"":EXw="na";EXb?"":EXb="na"; EXref?EXref=EXref:EXref=EXd.referrer; EXd.write("<img "+EXsrc+"=http://e1.extreme-dm.com", "/"+EXvsrv+".g?login="+EXlogin+"&", "jv="+EXjv+"&j=y&srw="+EXw+"&srb="+EXb+"&", "l="+escape(EXref)+" height=1 width=1>");//--> </script><noscript><div id="neXTReMe"><img height="1" width="1" alt="" src="http://e1.extreme-dm.com/s10.g?login=doorhand&j=n&jv=n"/> </div></noscript></div>
<?php
*/ 
}
?>
</body>
<!-- phone insertion script begins -->

<script>
function ybFun_CustomFindAndReplace(searchText, replacement, searchNode) {
    if (country.localeCompare('uk') == 0) {
        ybFun_CustomFindAndReplaceUK(searchText, replacement, searchNode);
    } else {
        ybFun_CustomFindAndReplaceUS(searchText, replacement, searchNode);
    }
}

function ybFun_CustomFindAndReplaceUS(searchText, replacement, searchNode) {
    var qsParm = ybFun_RetreiveQueryParams();
    if (!searchText || typeof replacement === 'undefined') {
        return;
    }

    var targetNum = searchText.toString();
    var provisionNum = replacement.toString();
    var delims = new Array();
    delims[0] = "";
    delims[1] = "-";
    delims[2] = ".";
    for (var i = 0; i < delims.length; i++) {
        var delimToUse = delims[i];
        var newTargetNum = targetNum.substring(1, 4) + delimToUse + targetNum.substring(4, 7) + delimToUse + targetNum.substring(7, 11);
        var newProvisionNum = provisionNum.substring(1, 4) + delimToUse + provisionNum.substring(4, 7) + delimToUse + provisionNum.substring(7, 11);
        ybFun_GenericFindAndReplace(newTargetNum, newProvisionNum);
    }
}

function ybFun_CustomFindAndReplaceUK(searchText, replacement, searchNode) {
    if (!searchText || typeof replacement === 'undefined') {
        return;
    }

    var targetNum = searchText.toString();
    var provisionNum = replacement.toString();

    if (targetNum.length == provisionNum.length) {
        if (targetNum.length > 9) {
            ybFun_GenericFindAndReplace(ybUKareaCode(targetNum, 3), ybUKareaCode(provisionNum, 3));
        }
        if (targetNum.length >= 9) {
            ybFun_GenericFindAndReplace(ybUKareaCode(targetNum, 5), ybUKareaCode(provisionNum, 5));
            ybFun_GenericFindAndReplace(ybUKareaCode(targetNum, 6), ybUKareaCode(provisionNum, 6));
        }
        if (targetNum.length >= 7) {
            ybFun_GenericFindAndReplace(ybUKareaCode(targetNum, 4), ybUKareaCode(provisionNum, 4));
        }
    }
}

function ybUKareaCode(number, areaCodeLength) {
    var delim = " ";
    var result;

    result = number.substring(0, areaCodeLength) + delim;
    if ((number.length - areaCodeLength)> 6) {
        result = result + number.substring(areaCodeLength, (number.length - 4))
            + delim + number.substring((number.length - 4), (number.length));
    } else {
        result = result + number.substring(areaCodeLength, (number.length));
    }
    return result;
}

function ybFun_GenericFindAndReplace(searchText, replacement, searchNode) {
    var regex = typeof searchText === 'string' ? new RegExp(searchText, 'g') : searchText;
    var bodyObj = document.body;
    var content = bodyObj.innerHTML;
        content = content.replace(regex, replacement);
        bodyObj.innerHTML = content;
}

function searchByElementTypeWithAttribute(elementType, attributeType, expectedAttributeValue, doToFoundElements) {
    var matchingElements = [];
    var elements;
    if (elementType) {
        elements = document.getElementsByTagName(elementType);
    } else {
        elements = document.getElementsByTagName('*');
    }
    for (var i = 0, n = elements.length; i < n; i++) {
        var attributeObject = elements[i].getAttribute(attributeType);
        if (attributeObject && attributeObject == expectedAttributeValue) {
            // Element exists with attribute. Add to array.
            doToFoundElements(elements[i]);
        }
    }
    return matchingElements;
}

function yb_remove_image_by_src(imageSrc){
    searchByElementTypeWithAttribute('img', 'src', imageSrc, function(element){element.parentNode.removeChild(element);});
}

function ybFun_RetreiveQueryParams() {
    var qsParm = new Array();
    var query =parent.document.location.href;
    query = query.substring(query.indexOf('?') + 1, query.length);
    var parms = query.split('&');
    for (var i = 0; i < parms.length; i++) {

        var pos = parms[i].indexOf('=');
        if (pos > 0) {
            var key = parms[i].substring(0, pos);
            var val = parms[i].substring(pos + 1);
            val = val.replace("#", "");
            qsParm[key] = val;
        }
    }
    return qsParm;
}

var ybFindPhNums = [];
var ybReplacePhNums = [];

var ybFindPhImg = [];
var ybReplacePhImg = [];

var ybFindCustomText = [];
var ybReplaceCustomText = [];

var ybRemoveImgSrcs = [];

var ybReplaceDupe = [];
var country = 'us';

function ybFun_ReplaceTextUK() {
    ybFun_ReplaceText('uk');
}

function ybFun_ReplaceTextUS() {
    ybFun_ReplaceText('us');
}

function ybFun_ReplaceText(countryInput) {
    if(typeof countryInput !== 'undefined' || countryInput){
        country = countryInput.toLocaleLowerCase();
    }
    var qsParm = ybFun_RetreiveQueryParams();
    var useYB = qsParm['useYB'];
    var cookieUseYB = null;
    if (useYB == null) {
        cookieUseYB = ybFun_ReadCookie("useYB");
        if (cookieUseYB != null) {
            useYB = cookieUseYB;
        }
    }
    if (useYB != null) {
        ybFun_CreateCookie("useYB", useYB);
        if (ybFindPhNums == null || ybReplacePhNums == null
                || ybFindPhNums.length != ybReplacePhNums.length) {
            return;
        }
        if (ybFindPhImg != null && ybReplacePhImg != null) {
                if (ybFindPhImg.length != ybReplacePhImg.length) {
                return;
            }
        }
        if (ybFindCustomText != null && ybReplaceCustomText != null) {
		    if (ybFindCustomText.length != ybReplaceCustomText.length) {
                return;
            }
        }
        if (useYB == '') {
            for (var i = 0; i < ybFindPhNums.length; i++) {
                ybFun_CustomFindAndReplace(ybFindPhNums[i], ybReplacePhNums[i]);
            }
            for (var j = 0; j < ybFindPhImg.length; j++) {
                ybFun_GenericFindAndReplace(ybFindPhImg[j], ybReplacePhImg[j]);
            }
            for (var k = 0; k < ybFindCustomText.length; k++) {
                ybFun_GenericFindAndReplace(ybFindCustomText[k], ybReplaceCustomText[k]);
            }
            for (var l = 0; l < ybRemoveImgSrcs.length; l++) {
                yb_remove_image_by_src(ybRemoveImgSrcs[l]);
            }
        } else {
            var idxs = useYB.split(',');
            for (var i = 0; i < idxs.length; i++) {
                if (ybFun_IsDigit(idxs[i])) {
                    if (ybFindPhNums.length >= idxs[i]) {
                        ybFun_CustomFindAndReplace(ybFindPhNums[(idxs[i] - 1)], ybReplacePhNums[(idxs[i] - 1)]);
                    }
                    if (ybFindPhImg.length >= idxs[i]) {
                        ybFun_GenericFindAndReplace(ybFindPhImg[(idxs[i] - 1)], ybReplacePhImg[(idxs[i] - 1)]);
                    }
                    if (ybFindCustomText.length >= idxs[i]) {
                        ybFun_GenericFindAndReplace(ybFindCustomText[(idxs[i] - 1)], ybReplaceCustomText[(idxs[i] - 1)]);
                    }
                    if (ybRemoveImgSrcs.length >= idxs[i]) {
                        yb_remove_image_by_src(ybRemoveImgSrcs[(idxs[i] - 1)]);
                    }
                }
            }
        }
        var bodyObj = document.body;
        var content = bodyObj.innerHTML;
        bodyObj.innerHTML = content;
     }
}

function ybFun_IsDigit(strVal) {
    var reg = new RegExp("^[0-9]$");
    return (reg.test(strVal));
}

function ybFun_CreateCookie(name, value, days) {
    if (days == null) {
        days = 90;
    }
    var date = new Date();
    date.setTime( date.getTime() + (days * (24*60*60*1000)) );
    var expires = "; expires="+date.toGMTString();
    document.cookie = name + "=" + value + expires + "; path=/";
}

function ybFun_ReadCookie(name) {
      var nameLookup = name;
    var cookieArr = document.cookie.split(';');
      for(var i=0; i < cookieArr.length; i++) {
            var cookieNV = cookieArr[i];
            while (cookieNV.charAt(0) == " ") {
            cookieNV = cookieNV.substring(1, cookieNV.length);
        }
            if (cookieNV.indexOf(nameLookup + "=") == 0) {
            return cookieNV.substring( (nameLookup.length + 1) ,cookieNV.length);
        }
            if (cookieNV.indexOf(nameLookup) == 0) {
            return "";
        }
      }
      return null;
}

function ybFun_EraseCookie(name) {
      ybFun_CreateCookie(name, "", -1);
}

</script>
<script>

ybFindPhNums = ['02077513397'];
ybReplacePhNums = ['02078703723'];

ybFindCustomText = ['02077513397','02077519734','02073717240','7751 9734','7751 3397','7371 7240'];
ybReplaceCustomText = ['02078703723','02037649797','02035970075','3764 9797','7870 3723','3597 0075'];


ybFun_ReplaceTextUK();

</script>


<!-- phone insertion script ends -->
</html>

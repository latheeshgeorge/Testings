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
	$default_layout 		= 'home';
	// Including the website layout settings cached file (if any)
	$layout_cache_file_included = false;
	$filename = $image_path.'/cache/websitelayout/'.$default_layout.'.php';
	if(file_exists($filename))
	{
		include_once "$filename";
		$layout_cache_file_included = true;
	}
	// Calling the function to get all the captions set in the COMMON section
	$Captions_arr['COMMON'] = getCaptions('COMMON');
	// Get the captions for price
	$Captions_arr['PRICE_DISPLAY']	= getCaptions('PRICE_DISPLAY');

	// Calling the function to get the details of default currency
	$default_Currency_arr = get_default_currency();

	// Assigning the current currency to the variable
	$sitesel_curr = get_session_var('SITE_CURR');
	
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
	}
	// If sitesel_curr have no value then set it as the default currency
	if (!$sitesel_curr)
	{
		$sitesel_curr		= $default_Currency_arr['currency_id'];// setting the default currency value
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
	
		require("head.php");
		echo "<link href=\"".url_head_link("images/".$ecom_hostname."/".$css_folder."/liteaccordion.css",1)."\" media=\"screen\" type=\"text/css\" rel=\"stylesheet\" />
		<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/jquery.easing.1.3.js",1)."\"></script>
		<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/liteaccordion.jquery.js",1)."\"></script>";

		echo "<link href=\"".url_head_link("images/".$ecom_hostname."/css/scrollload.css",1)."\" media=\"screen\" type=\"text/css\" rel=\"stylesheet\" />";
		echo "<link href=\"".url_head_link("images/".$ecom_hostname."/scripts/jquery-1.2.6.pack.js",1)."\" media=\"screen\" type=\"text/javascript\"/>";
		
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
//if($ecom_siteid==61)//local
//{ 
 /*echo "<script  type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/scripts/accordian/jquery-1.4.2.min.js",1)."\" charset=\"utf-8\" ></script>\n";
 echo "<script  type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/scripts/accordian/jquery.accordion.2.0.js",1)."\" charset=\"utf-8\" ></script>\n";
 */
 
 echo "<script  type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/scripts/ddaccordion.js",1)."\" charset=\"utf-8\" ></script>\n";
 //echo " <link href=\"".url_head_link("images/".$ecom_hostname."/".$css_folder."/searchfilter/jquery-ui.css",1)."\" media=\"screen\" type=\"text/css\" rel=\"stylesheet\" />";

?>

<script type="text/javascript">


ddaccordion.init({
	headerclass: "expandable", //Shared CSS class name of headers group that are expandable
	contentclass: "categoryitems", //Shared CSS class name of contents group
	revealtype: "click",//"mouseenter", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
	mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
	collapseprev: true, //Collapse previous content (so only one open at any time)? true/false 
	defaultexpanded: [], //index of content(s) open by default [index1, index2, etc]. [] denotes no content
	onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
	animatedefault: false, //Should contents open by default be animated into view?
	persiststate: false, //persist state of opened contents within browser session?
	toggleclass: ["", "openheader"], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
	togglehtml: ["prefix", "", ""], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
	animatespeed: "fast", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
	oninit:function(headers, expandedindices){ //custom code to run when headers have initalized
		//do nothing
	},
	onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
		//do nothing
	}
})


</script>
<?php

//}
?>


</head>
<body>
<div id ="overlay"></div>
<div class='external_main_wrapper'>
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
	<input type="hidden" name="prodimgdet" id="prodimgdet" value="" />
	<input type="hidden" name="prod_curtab" id="prod_curtab" value="" />
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
<?php
// Check whether top_misc file exists for current site. If exists then include it
	$top_misc="./images/".$ecom_hostname."/otherfiles/top_misc.php";
	if(file_exists($top_misc))
	{
            include("$top_misc");
	}
?>
<table border="0" cellpadding="0" cellspacing="0" class="main">
<tbody>
<?php
	// Check whether top table exists for current site. If exists then include it
	$toptable="./images/".$ecom_hostname."/otherfiles/toptable.php";
	if(file_exists($toptable))
	{
      include("$toptable");
	}
	/*
	// Decide whether to display the top menu or not
	if(get_session_var('ecom_login_customer'))
	{
		include "themes/$ecom_themename/modules/mod_topmenu.php";
	}
	*/ 
	
?>

	
		<?php
		if($_REQUEST['req']!='')
		{/*
		?>
		<div style="width:580px;padding:0px">&nbsp;</div>
<?php	
 */
		}
// Including the page which decides the display logic for the middle section
		include ("mainbody.php");
		$position = 'middle-bottom';
		include("Components.php");
?>
<div class="bannerAdsmiddle">
        <div class="banneradswrap"><a href="http://<?php echo $ecom_hostname ?>/registration.html"><img src="<?PHP echo url_site_image('img_banner.png');?>" width="550" height="267" border="0" /></a></div>
        <div class="newsletter">
        
		<!-- Begin MailChimp Signup Form -->
<link href="//cdn-images.mailchimp.com/embedcode/classic-081711.css" rel="stylesheet" type="text/css">
<style type="text/css">
	#mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; }
	/* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
	   We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
	   #mc_embed_signup div#mce-responses {  padding: 0 0.5em; top:-1.4em; width:100%; margin:0; }
	   #mc_embed_signup div.response { margin:0; padding:0 0.5em 0.5em 0; width: 100%; font-size:12px; }
	   #mce-error-response { color: #CC6600; }
</style>
<div id="mc_embed_signup" class="mc_embed_signupA">
<form name="frm_newsletter" method="post" action="" class="frm_cls" onsubmit="return newsletter_validation(this)">
	<h2 class="Newsh2" id="Newsh2"><span class="Newsh2_span">Subscribe Below For New Products & Offers</span><br>
</span></h2>
<div class="indicates-required"><span class="asterisk">*</span> indicates required</div>
<div class="mc-field-group">
	<label for="mce-FNAME">Name  <span class="asterisk">*</span>
</label>
	<input type="text" value="" name="newsletter_name" class="required" id="newsletter_name">
</div>
<div class="mc-field-group">
	<label for="mce-EMAIL">Email Address  <span class="asterisk">*</span>
</label>
	<input type="email" value="" name="newsletter_email" class="required email" id="newsletter_email">
</div>
	<div id="mce-responses" class="clear">
		<div class="response" id="mce-error-response" style="display:none"></div>
		<div class="response" id="mce-success-response" style="display:none"></div>
	</div>	<div class="clear">
		<input type="hidden" name="newsletter_Submit" value="1" />
		<input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
</form>
</div>
<script type="text/javascript">
var fnames = new Array();var ftypes = new Array();fnames[1]='FNAME';ftypes[1]='text';fnames[0]='EMAIL';ftypes[0]='email';
try {
    var jqueryLoaded=jQuery;
    jqueryLoaded=true;
} catch(err) {
    var jqueryLoaded=false;
}
var head= document.getElementsByTagName('head')[0];
if (!jqueryLoaded) {
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = '//ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js';
    head.appendChild(script);
    if (script.readyState && script.onload!==null){
        script.onreadystatechange= function () {
              if (this.readyState == 'complete') mce_preload_check();
        }    
    }
}

var err_style = '';
try{
    err_style = mc_custom_error_style;
} catch(e){
    err_style = '#mc_embed_signup input.mce_inline_error{border-color:#6B0505;} #mc_embed_signup div.mce_inline_error{margin: 0 0 1em 0; padding: 1px 2px; background-color:#6B0505;font-size:10px;font-weight: bold; z-index: 1; color:#fff;}';
}
var head= document.getElementsByTagName('head')[0];
var style= document.createElement('style');
style.type= 'text/css';
if (style.styleSheet) {
  style.styleSheet.cssText = err_style;
} else {
  style.appendChild(document.createTextNode(err_style));
}
head.appendChild(style);
setTimeout('mce_preload_check();', 250);

var mce_preload_checks = 0;
function mce_preload_check(){
    if (mce_preload_checks>40) return;
    mce_preload_checks++;
    try {
        var jqueryLoaded=jQuery;
    } catch(err) {
        setTimeout('mce_preload_check();', 250);
        return;
    }
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = 'http://downloads.mailchimp.com/js/jquery.form-n-validate.js';
    head.appendChild(script);
    try {
        var validatorLoaded=jQuery("#fake-form").validate({});
    } catch(err) {
        setTimeout('mce_preload_check();', 250);
        return;
    }
    mce_init_form();
}
function mce_init_form(){
    jQuery(document).ready( function($) {
      var options = { errorClass: 'mce_inline_error', errorElement: 'div', onkeyup: function(){}, onfocusout:function(){}, onblur:function(){}  };
      var mce_validator = $("#mc-embedded-subscribe-form").validate(options);
      $("#mc-embedded-subscribe-form").unbind('submit');//remove the validator so we can get into beforeSubmit on the ajaxform, which then calls the validator
      options = { url: 'http://purogusto.us7.list-manage.com/subscribe/post-json?u=b66d30b601e7fb7617b740299&id=bfe38867f5&c=?', type: 'GET', dataType: 'json', contentType: "application/json; charset=utf-8",
                    beforeSubmit: function(){
                        $('#mce_tmp_error_msg').remove();
                        $('.datefield','#mc_embed_signup').each(
                            function(){
                                var txt = 'filled';
                                var fields = new Array();
                                var i = 0;
                                $(':text', this).each(
                                    function(){
                                        fields[i] = this;
                                        i++;
                                    });
                                $(':hidden', this).each(
                                    function(){
                                        var bday = false;
                                        if (fields.length == 2){
                                            bday = true;
                                            fields[2] = {'value':1970};//trick birthdays into having years
                                        }
                                    	if ( fields[0].value=='MM' && fields[1].value=='DD' && (fields[2].value=='YYYY' || (bday && fields[2].value==1970) ) ){
                                    		this.value = '';
									    } else if ( fields[0].value=='' && fields[1].value=='' && (fields[2].value=='' || (bday && fields[2].value==1970) ) ){
                                    		this.value = '';
									    } else {
									        if (/\[day\]/.test(fields[0].name)){
    	                                        this.value = fields[1].value+'/'+fields[0].value+'/'+fields[2].value;									        
									        } else {
    	                                        this.value = fields[0].value+'/'+fields[1].value+'/'+fields[2].value;
	                                        }
	                                    }
                                    });
                            });
                        $('.phonefield-us','#mc_embed_signup').each(
                            function(){
                                var fields = new Array();
                                var i = 0;
                                $(':text', this).each(
                                    function(){
                                        fields[i] = this;
                                        i++;
                                    });
                                $(':hidden', this).each(
                                    function(){
                                        if ( fields[0].value.length != 3 || fields[1].value.length!=3 || fields[2].value.length!=4 ){
                                    		this.value = '';
									    } else {
									        this.value = 'filled';
	                                    }
                                    });
                            });
                        return mce_validator.form();
                    }, 
                    success: mce_success_cb
                };
      $('#mc-embedded-subscribe-form').ajaxForm(options);
      
      
    });
}
function mce_success_cb(resp){
		var $mce_nc = jQuery.noConflict();
    $mce_nc('#mce-success-response').hide();
    $mce_nc('#mce-error-response').hide();
    if (resp.result=="success"){
        $mce_nc('#mce-'+resp.result+'-response').show();
        $mce_nc('#mce-'+resp.result+'-response').html(resp.msg);
        $mce_nc('#mc-embedded-subscribe-form').each(function(){
            this.reset();
    	});
    } else {
        var index = -1;
        var msg;
        try {
            var parts = resp.msg.split(' - ',2);
            if (parts[1]==undefined){
                msg = resp.msg;
            } else {
                i = parseInt(parts[0]);
                if (i.toString() == parts[0]){
                    index = parts[0];
                    msg = parts[1];
                } else {
                    index = -1;
                    msg = resp.msg;
                }
            }
        } catch(e){
            index = -1;
            msg = resp.msg;
        }
        try{
            if (index== -1){
                $mce_nc('#mce-'+resp.result+'-response').show();
                $mce_nc('#mce-'+resp.result+'-response').html(msg);            
            } else {
                err_id = 'mce_tmp_error_msg';
                html = '<div id="'+err_id+'" style="'+err_style+'"> '+msg+'</div>';
                
                var input_id = '#mc_embed_signup';
                var f = $mce_nc(input_id);
                if (ftypes[index]=='address'){
                    input_id = '#mce-'+fnames[index]+'-addr1';
                    f = $mce_nc(input_id).parent().parent().get(0);
                } else if (ftypes[index]=='date'){
                    input_id = '#mce-'+fnames[index]+'-month';
                    f = $mce_nc(input_id).parent().parent().get(0);
                } else {
                    input_id = '#mce-'+fnames[index];
                    f = $mce_nc().parent(input_id).get(0);
                }
                if (f){
                    $mce_nc(f).append(html);
                    $mce_nc(input_id).focus();
                } else {
                    $mce_nc('#mce-'+resp.result+'-response').show();
                    $mce_nc('#mce-'+resp.result+'-response').html(msg);
                }
            }
        } catch(e){
            $mce_nc('#mce-'+resp.result+'-response').show();
            $mce_nc('#mce-'+resp.result+'-response').html(msg);
        }
    }
}

</script>
<!--End mc_embed_signup-->
        
        </div>
        </div>
<?php
	// Check whether top table exists for current site. If exists then include it
	$bottomtable="./images/".$ecom_hostname."/otherfiles/bottomtable.php";
	if(file_exists($bottomtable))
	{
		include("$bottomtable");
	}
?>
	</td>
	</tr>
</tbody>
</table>
<?php 
	/*if($protectedUrl==false)
	{
		// ##################################################################################################
		// Calling the function to display the web tracker bottom script. This is the webtracker from b-1st 
		// ##################################################################################################
		get_WebtrackerBottomScript(); 
	}*/
	
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

function show_prod_det_more_link($link,$desc) 
{
	global $ecom_siteid;
	if ($ecom_siteid==104) //104
		echo substr(stripslashes($desc),0,140).' <a href="'.$link.'" title="click to view more" style="text-decoration:none;color:#ff7d06;white-space:nowrap;font-weight:bold;">[::read more::]</a>';
	else
		echo stripslashes($desc);
}

?>

</div>
  </body>
  </html>

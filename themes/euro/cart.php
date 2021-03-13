<?php
	/*#################################################################
	# Script Name 	: default.php
	# Description 		: This is the default page for the theme.
	# Coded by 		: Sny
	# Created on		: 13-May-2009
	# Modified by		: 
	# Modified On		: 
	#################################################################*/
	// Layout Type
	$default_layout 		= 'cart';
	// Including the website layout settings cached file (if any)
	$layout_cache_file_included = false;
	$filename = $image_path.'/cache/websitelayout/'.$default_layout.'.php';
	if(file_exists($filename))
	{
		include_once "$filename";
		$layout_cache_file_included = true;
	}
	
	//$search_only_category = 72125; // The category in which the search should only be done
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
?>
<script language="javascript" type="text/javascript">
function correctPNG() // correctly handle PNG transparency in Win IE 5.5 & 6.
{
   var arVersion = navigator.appVersion.split("MSIE")
   var version = parseFloat(arVersion[1])
   if ((version >= 5.5) && (document.body.filters) && version <7) 
   {
      for(var i=0; i<document.images.length; i++)
      {
         var img = document.images[i]
         var imgName = img.src.toUpperCase()
         if (imgName.substring(imgName.length-3, imgName.length) == "PNG")
         {
            var imgID = (img.id) ? "id='" + img.id + "' " : ""
            var imgClass = (img.className) ? "class='" + img.className + "' " : ""
            var imgTitle = (img.title) ? "title='" + img.title + "' " : "title='" + img.alt + "' "
            var imgStyle = "display:inline-block;" + img.style.cssText 
            if (img.align == "left") imgStyle = "float:left;" + imgStyle
            if (img.align == "right") imgStyle = "float:right;" + imgStyle
            if (img.parentElement.href) imgStyle = "cursor:hand;" + imgStyle
            var strNewHTML = "<span " + imgID + imgClass + imgTitle
            + " style=\"" + "width:" + img.width + "px; height:" + img.height + "px;" + imgStyle + ";"
            + "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader"
            + "(src=\'" + img.src + "\', sizingMethod='scale');\"></span>" 
            img.outerHTML = strNewHTML
            i = i-1
         }
      }
   }    
}
if(window.attachEvent)
	window.attachEvent("onload", correctPNG);
var path='<?php echo url_site_image('',1)?>';
</script>
<script src="<? url_head_link("images/".$ecom_hostname."/scripts/lightbox-euro.js")?>"></script>
</head>
<body>
<div id="blackout" style="display: none; cursor: pointer; width: 886px; height: 1009px; left: 0px; top: 0px; opacity: 0.7;" onClick="JavaScript:unload_lbox();">&nbsp;</div>
<div id="eurovideodiv" style="border: 2px solid rgb(0, 0, 0); display: none; top: 62px; left: 161px; background-color: rgb(255, 255, 255);">
	</div>
<?php
	// Check whether top_misc file exists for current site. If exists then include it
	$top_misc="./images/".$ecom_hostname."/otherfiles/top_misc.php";
	if(file_exists($top_misc))
	{
     	include("$top_misc");
	}
?>
<div class="maindiv" >
<?php
	// Check whether top table exists for current site. If exists then include it
	$toptable="./images/".$ecom_hostname."/otherfiles/toptable.php";
	if(file_exists($toptable))
	{
      include("$toptable");
	}
?>
	<div class="compleft">
	<div class="left_con">
	<div class="left_top_logo"><img src="<?php url_site_image('logo_top.gif')?>"  border="0" alt="" /></div>
	<div class="left_mid_logo">
	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="190" height="150">
            <param name="movie" value="<?php url_site_image('eurolabel-logo.swf')?>" />
            <param name="quality" value="high" />
            <embed src="<?php url_site_image('eurolabel-logo.swf')?>" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="190" height="150"></embed>
     </object>
	<!--a href="<?php url_link('')?>"><img src="<?php url_site_image('logo.gif')?>" width="189" height="130" border="0" alt="logo" /></a--></div>
	<div class="left_bottom"><img class="imggfil" src="<?php url_site_image('logo.png')?>"  border="0" alt="" /></div>
	</div>
	<?php
		//Showing the left components
		$position = 'left';
		include("Components.php");
	?>
	</div>
	<div class="comp_con_main">
	<div class="compright">
	<div class="hdr_div_inner"><div id="take-a-tour1"></div></div>
			<div class="search_div">
			<form method="post" name="frm_quicksearch" class="frm_cls" action="<?php url_link('search.html')?>"> 
			<ul class="serach_outer">
			<li><img src="<?php url_site_image('search-icon.gif')?>" class="serach_icon" alt="" /></li>
			<li><input name="quick_search" type="text"  class="search_txt_bx" value=""/></li>
			<li><input name="button_submit_search" type="submit" value="<?php echo $Captions_arr['COMMON']['SEARCH_GO']?>" onClick="show_wait_button(this,'Wait ...')"  class="search_btn"/></li>
			<li><a href="<?php url_link('advancedsearch.html')?>" class="ad_search" title="<?php echo $Captions_arr['COMMON']['ADVANCED_SEARCH']?>"><?php echo $Captions_arr['COMMON']['ADVANCED_SEARCH']?></a></li>
			</ul>
			<input type="hidden" name="search_category_id" value="<?php echo $search_only_category?>" />
			<input type="hidden" name="search_submit" value="search_submit" />
			</form>
			 <div class="top_mid_curr">
              <ul class="currency">
                <li class="currencyheader">
                <?php
                $curr_arr = get_currency_list();
                $comp_uniqid = uniqid('');
                ?>
                <form method="post" name="frm_maincurrency_<?=$comp_uniqid?>" enctype="multipart/form-data" class="frm_cls" action="">
                <?php
					//showing the currency selection drop down
					echo generateselectbox('cbo_selcurrency',$curr_arr,$sitesel_curr,'','document.frm_maincurrency_'.$comp_uniqid.'.submit()',0,'currencyselectordropdown');
				?>
                 </form>   
                    </li>
              </ul>
            </div>
		</div>
		<?php
		if(get_session_var('ecom_login_customer'))
		{
			include "themes/$ecom_themename/modules/mod_topmenu.php";
		}
	 	include("mainbody.php");
	?>
       <div class="sta_rt_lnk"><img src="<?php url_site_image('cnt.gif')?>" /></div>
  </div> 
   <div class="compbottom"></div>
   </div>   
</div>
<?php
		// Check whether bottom table exists for current site. If exists then include it
		$bottomtable="./images/".$ecom_hostname."/otherfiles/bottomtable.php";
		if(file_exists($bottomtable))
		{
		  include("$bottomtable");
		}
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
	
		<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
		<script type="text/javascript">_uacct = "<?php echo $ecom_urchinwebtrackercode?>"; urchinTracker();</script>
<?php		
	}
if($fraud_detection==1) {
?>
<script language="javascript" type="text/javascript"> helpmsg() </script>
<?php
}
?>
<?php
if(trim($ecom_footer_script)!='' and $protectedUrl==false)
	echo stripslashes($ecom_footer_script);
?>
</body>
</html>
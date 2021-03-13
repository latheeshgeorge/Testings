<?php
	/*#################################################################
	# Script Name 	: common.php
	# Description 		: This is the default page to used
	# Coded by 		: Sny
	# Created on		: 18-Nov-2008
	# Modified by		: 
	# Modified On		: 
	#################################################################*/
	// Layout Type
	$default_layout 				= 'common';
	$split_span						= 'colspan="2"'; // this variable is used to split the columns for items in top table
	// Including the website layout settings cached file (if any)
	$layout_cache_file_included = false;
	$filename = $image_path.'/cache/websitelayout/'.$default_layout.'.php';
	if(file_exists($filename))
	{
		include_once "$filename";
		$layout_cache_file_included = true;
	}

	// Calling the function to get all the captions set in the COMMON section
	$Captions_arr['COMMON']	= getCaptions('COMMON');
	// Get the captions for price
	$Captions_arr['PRICE_DISPLAY']	= getCaptions('PRICE_DISPLAY');

	// Calling the function to get the details of default currency
	$default_Currency_arr		= get_default_currency();

	// Assigning the current currency to the variable
	$sitesel_curr					= get_session_var('SITE_CURR');
	
	if($_REQUEST['cbo_selcurrency'])
	{
		$sitesel_curr = $_REQUEST['cbo_selcurrency'];
		set_session_var('SITE_CURR',$sitesel_curr);
		//Finding the symbol for current currency
		$sql_curr  = "SELECT curr_sign_char FROM general_settings_site_currency WHERE currency_id = $sitesel_curr";
		$ret_curr	= $db->query($sql_curr);
		if($db->num_rows($ret_curr))
		{
			$row_curr  			= $db->fetch_array($ret_curr);
			$sitesel_currsign 	= $row_curr['curr_sign_char'];
		}
		set_session_var('SITE_CURR_SIGN',$sitesel_currsign);
		//clear_all_cache();
	}
	if($_REQUEST['category_id'])
	{
		$top_cats = get_session_var('top_main_category_arr');
		if(count($top_cats))
		{
			if(in_array($_REQUEST['category_id'],$top_cats))
			{
				$_REQUEST['category_id'];
				set_session_var('top_main_category',$_REQUEST['category_id']);
			}	
		}		
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
		
		/* Clearing cart section on logout */
		set_session_var("cart_total", 0);	// Setting the cart total to session
		set_session_var("cart_total_items",0);				// Setting the cart total to session
		set_session_var("cart_subtotal", 0);
				
		$csession_id 	= Get_session_Id_from();
		$sql_cart 		= "SELECT cart_id FROM cart WHERE sites_site_id = $ecom_siteid AND session_id ='".$csession_id."'";
		$ret_cart 		= $db->query($sql_cart);
		if($db->num_rows($ret_cart))
		{
			while($row_cart = $db->fetch_array($ret_cart))
			{
				$cid = $row_cart['cart_id'];
				$del_sql = "DELETE FROM cart_messages WHERE cart_id=$cid";
				$db->query($del_sql);
				
				$del_sql = "DELETE FROM cart_variables WHERE cart_id=$cid";
				$db->query($del_sql);
			}	
		}
		$del_sql = "DELETE FROM cart_checkout_values WHERE session_id = '".$csession_id."' and sites_site_id = $ecom_siteid";
		$db->query($del_sql);
		$del_sql = "DELETE FROM cart_supportdetails WHERE session_id = '".$csession_id."' and sites_site_id = $ecom_siteid";
		$db->query($del_sql);
		$del_sql = "DELETE FROM cart WHERE session_id = '".$csession_id."' and sites_site_id = $ecom_siteid";
		$db->query($del_sql);
		
		/* Clearing cart complete */
		
		
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
?>
<script  type="text/javascript" src="<? url_head_link("images/".$ecom_hostname."/scripts/scroll_cat.js")?>" ></script>
<?php
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

if($ecom_siteid==61)//local
{ 
 /*echo "<script  type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/scripts/accordian/jquery-1.4.2.min.js",1)."\" charset=\"utf-8\" ></script>\n";
 echo "<script  type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/scripts/accordian/jquery.accordion.2.0.js",1)."\" charset=\"utf-8\" ></script>\n";
 */
 
 echo "<script  type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/scripts/ddaccordion.js",1)."\" charset=\"utf-8\" ></script>\n";
?>

<script type="text/javascript">


ddaccordion.init({
	headerclass: "expandable", //Shared CSS class name of headers group that are expandable
	contentclass: "categoryitems", //Shared CSS class name of contents group
	revealtype: "mouseenter", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
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

}
?>

</head>
<!--[if lt IE 7 ]> <html class="ie ie6 lte9 lte8 lte7 no-js"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7 lte9 lte8 lte7 no-js"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8 lte9 lte8 no-js"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9 lte9 no-js"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="notie no-js"> <!--<![endif]-->
<body>
<?php
// Check whether top_misc file exists for current site. If exists then include it
	$top_misc="./images/".$ecom_hostname."/otherfiles/top_misc.php";
	if(file_exists($top_misc))
	{
     	include("$top_misc");
	}
?>
<table class="maintable" border="0" cellspacing="0" cellpadding="0">
  <?php
	// Check whether top table exists for current site. If exists then include it
	$toptable="./images/".$ecom_hostname."/otherfiles/toptable.php";
	if(file_exists($toptable))
	{
     	include("$toptable");
	}
	// Showing the topband components
	$position = 'topband';
	include("Components.php");
?>
  <tr>
    <td align="left" valign="top" class="maintable_inner_out">&nbsp;</td>
	<td align="left" valign="top" class="maintable_inner_left">
     <?php
	
		include "themes/$ecom_themename/modules/mod_searchfilter.php";
	?>
     
	</td>
    <td align="left" valign="top" class="maintable_inner_content">
		<div class="static_content">
		<?php
			// Decide whether to display the top menu or not
			if(get_session_var('ecom_login_customer'))
			{
				include "themes/$ecom_themename/modules/mod_topmenu.php";
			}		
			?>
				<div id="result_filter">
			<?php
			// Including the page which decides the display logic for the middle section
			include ("mainbody.php");
			?></div>
			<?php
		?>
		</div>
    </td>
    <td align="left" valign="top" class="maintable_inner_out">&nbsp;</td>
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
 <script type="text/javascript">
  var div_scroll1 = new TextScroll('div_scroll1', 'scroll_box', 'scroll_up', 'scroll_down');
</script>
<?php 
	/*if($protectedUrl==false)
	{
		// ##################################################################################################
		// Calling the function to display the web tracker bottom script. This is the webtracker from b-1st 
		// ##################################################################################################
		get_WebtrackerBottomScript(); 
	}	*/
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
?>
</body>
</html>

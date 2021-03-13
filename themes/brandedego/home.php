<?
/*#################################################################
	# Script Name 	: mobilehome.php
	# Description 	: This is the home page for the mobile responsive theme.
	# Coded by 	: LSH
	# Created on	: 28-Jan-2016
	# Modified by	:
	# Modified On	:
	#################################################################*/
	
	// Layout Type
	$default_layout =	'home';
	
	if(check_IndividualSslActive())
	{
		$ecom_selfhttp = "https://";
	}
	else
	{
		$ecom_selfhttp = "http://";
	}
	
	// Including the website layout settings cached file (if any)
	$layout_cache_file_included = false;
	$filename = $image_path.'/cache/websitelayout/'.$default_layout.'.php';
	if(file_exists($filename))
	{
		include_once "$filename";
		$layout_cache_file_included = true;
	}
	// Calling the function to get all the captions set in the COMMON section
	$Captions_arr['COMMON']		= getCaptions('COMMON');
	// Get the captions for price
	$Captions_arr['PRICE_DISPLAY']	= getCaptions('PRICE_DISPLAY');

	// Calling the function to get the details of default currency
	$default_Currency_arr		= get_default_currency();

	// Assigning the current currency to the variable
	$sitesel_curr			= get_session_var('SITE_CURR');
	
	if($_REQUEST['cbo_selcurrency'])
	{
		$sitesel_curr   = $_REQUEST['cbo_selcurrency'];
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
                    echo "<script language=\"javascript\" type=\"text/javascript\">window.location ='".$ecom_selfhttp.$ecom_hostname."/cart.html';</script>";
		}
		else if ($_REQUEST['rets']==2)
		{
                    echo "<script language=\"javascript\" type=\"text/javascript\">window.location ='".$ecom_selfhttp.$ecom_hostname."/enquiry.html';</script>";
		}
		else
		{
                    echo "<script language=\"javascript\" type=\"text/javascript\">document.location.replace(\"/\")</script>";
		}
		exit;
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   
    <?php    
    require("responsive_head.php");
    if($_REQUEST['req'] =='prod_detail')
    {
	    //echo "<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/jquery.elevatezoom1.js",1)."\"></script>";

	}
	echo 
    "
     <link href=\"".url_head_link("images/".$ecom_hostname."/".$css_folder."/bootstrap.min.1.css",1)."\" rel=\"stylesheet\">";
    ?>
            <link href="<?php echo url_head_link("images/".$ecom_hostname."/".$css_folder."/responsive_unipad.min.css",1)?>" rel="stylesheet">

</head><!--/head-->
<body>
	<div class="modal fade" tabindex="-1" style="display:none;z-index:20000001;" role="dialog"  aria-hidden="true" id="myModal">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <div class="modal-body">
			<input type="hidden" name="form_modal_name"  id="form_modal_name" value="">
			<input type="hidden" name="form_prod_id"  id="form_prod_id" value="">
<?php echo $Captions_arr['COMMON']['ARRANGE_VIEW_CONFIRM']; ?>      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn modal-btA"  id="modal-btn-no" style="width:120px">Cancel</button>
        <button type="button" class="btn btn-warning modal-btB"  id="modal-btn-si" style="width:120px">Confirm</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
function Enquire_confirm(frma,prodid)
{  
	frm = eval('document.'+frma);
	var qty = frm.qty.value;
	if(qty!='')
	{
		if (isNaN(qty))	qty = 1;
		frm.qty.value = qty;
	}
	if(frm.prod_list_submit_common.value!='')
	{
		frm.fpurpose.value 		= frm.prod_list_submit_common.value;
		frm.fproduct_id.value	= prodid;
		frm.submit();
		return true;
	}
	else
	{
		var qty = frm.qty.value;
		if(qty!='')
		{
			if (isNaN(qty))	qty = 1;
			/*window.location 	= produrl+'?qty='+qty;*/
			frm.action = frm.fproduct_url.value;
			frm.submit();
		}
		return false;
	}
}
</script>
	<form name="frm_forcesubmit" id="frm_forcesubmit" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" class="frm_cls">
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

	<div id="inner-wrap">
	<div id="main" role="main">
	<?php
	
	// Check whether top_misc file exists for current site. If exists then include it
	$top_misc="./images/".$ecom_hostname."/otherfiles/top_misc.php";
	if(file_exists($top_misc))
	{
            include("$top_misc");
	}
    // Check whether top table exists for current site. If exists then include it
	$toptable="./images/".$ecom_hostname."/otherfiles/toptable.php";
	if(file_exists($toptable))
	{
           include("$toptable");
	}
	?>
	<script
			  src="https://code.jquery.com/jquery-2.2.4.min.js"
			  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
			  crossorigin="anonymous"></script>
							
			 
	 <?php	include("mainbody.php");
	 
	  // Check whether top table exists for current site. If exists then include it
	$bottomtable="./images/".$ecom_hostname."/otherfiles/bottomtable.php";
	if(file_exists($bottomtable))
	{
           include("$bottomtable");
	}
	 
	  ?>
      <!--/middle-->
       </div>
  </div> 
  <script type="text/javascript">
  <?php/*
			  
							function googleTranslateElementInit() {
							new google.translate.TranslateElement({ pageLanguage: 'en', includedLanguages: 'en,es,zh-CN', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, autoDisplay: false }, 'google_translate_element');
							}
							</script>
							<script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"
							type="text/javascript"></script>
							
							<script>
							function translate(lang) {
							var $frame = $('.goog-te-menu-frame:first');
							if (!$frame.size()) {
							alert("Error: Could not find Google translate frame.");
							return false;
							}
							$frame.contents().find('.goog-te-menu2-item span.text:contains(' + lang + ')').get(0).click();
							return false;
							}
							*/
							?> 
							$( document ).ready(function(e) {
		
		if($(window).width()==480)
		{
			$("#pads_control_width_div").addClass('divheight_480');
			$("#pads_control_width_div").css('display','block');
		}
		else
		{
			$("#pads_control_width_div").removeClass('divheight_480');
			$("#pads_control_width_div").css('display','none');
		}
		
		if($(window).width()>1023)
		{
			$("#topsection_mobileA").hide();
			$("#topsection_normal").show();
		}
		else
		{
			$("#topsection_normal").hide();
			$("#topsection_mobileA").show();
			
		}
		
		if($(window).width()<376)
		{
			$('#viewallpads_img').css({'width':'222px','height':'80px'});
		}
		else if($(window).width()<800)
		{
			$('#viewallpads_img').css({'height':'81px'});
		}
		else
		{
			$('#viewallpads_img').css({'height':'81px'});
		}
		
		if($(window).width()<700)
		{
			$(".tabs-7 ul li a .caret").css('display','');
		}
		else
		{
			$(".tabs-7 ul li a .caret").css('display','none');
		}
		
		<?php
		/*
		$('#spn_flg').click(function(){
		translate('Spanish');
		
	});
	$('#eng_flg').click(function(){
		translate('English');
	});
	$('#chn_flg').click(function(){
		translate('Chinese');
	});
	
	$('#spn_flgm').click(function(){
		translate('Spanish');
	});
	$('#eng_flgm').click(function(){
		translate('English');
	});
	$('#chn_flgm').click(function(){
		translate('Chinese');
	});
	
	
	$('#spn_flg').css('cursor','pointer');
	$('#eng_flg').css('cursor','pointer');
	$('#chn_flg').css('cursor','pointer');
	
	$('#spn_flgm').css('cursor','pointer');
	$('#eng_flgm').css('cursor','pointer');
	$('#chn_flgm').css('cursor','pointer');
	* */?> 		
	});
	
							</script>
							<script type="text/javascript">
      		document.write('<style type="text/css">');
      		
			document.write('#carousel1 {');
			document.write('	/*margin-top: -100px;*/');
			document.write('}');
			document.write('#carousel1 div {');
			document.write('	text-align: center;');
			document.write('	width: 403px;');
			document.write('	height: 102px;');
			document.write('	float: left;');
			document.write('	position: relative;');
			document.write('	background-color: #E3E3E3;');
			document.write('}');
			document.write('#carousel1 div img {');
			document.write('	border: none;');
			document.write('}');
			document.write('#carousel1 div span {');
			document.write('	display: none;');
			document.write('}');
			document.write('#carousel1 div:hover span {');
			document.write('	background-color: #333;');
			document.write('	color: #fff;');
			document.write('	font-family: Arial, Geneva, SunSans-Regular, sans-serif;');
			document.write('	font-size: 14px;');
			document.write('	line-height: 22px;');
			document.write('	display: inline-block;');
			document.write('	width: 100px;');
			document.write('	padding: 2px 0;');
			document.write('	margin: 0 0 0 -50px;');
			document.write('	position: absolute;');
			document.write('	bottom: 30px;');
			document.write('	left: 50%;');
			document.write('	border-radius: 3px;');
			document.write('}');
      		document.write('</style>');
      		</script>
      		<script src="<?php echo $ecom_selfhttp.$ecom_hostname?>/images/<?php echo $ecom_hostname?>/scripts/jquery.carouFredSel-6.0.4-packed.js" type="text/javascript"></script>
   	<script type="text/javascript">
			$(function() {

				var $c = $('#carousel1'),
					$w = $(window);

				$c.carouFredSel({
					align: false,
					items: 4,
					scroll: {
						items: 1,
						duration: 15000,
						timeoutDuration: 0,
						easing: 'linear',
						pauseOnHover: 'immediate'
					}
				});

			});
		</script>
		<script type="text/javascript">
		$(".enquire-confirm ").on("click", function(){
		var id = $(this).attr('id');
		var form_val = $("#form_name_"+id).val();
		$("#myModal").modal('show');
		$("#form_modal_name").val(form_val);
		$("#form_prod_id").val(id);

		});
		$("#modal-btn-si").on("click", function(){
		$("#myModal").modal('hide');
		var form_name = $("#form_modal_name").val();
		var prod_id   = $("#form_prod_id").val();
		Enquire_confirm(form_name,prod_id);
		$("#form_modal_name").val('');
		$("#form_prod_id").val('');
		});

		$("#modal-btn-no").on("click", function(){
		$("#form_modal_name").val('');
		$("#form_prod_id").val('');

		$("#myModal").modal('hide');
		return false;
		});

		</script>
	
	<?php
 //below one cdn added for  speed on o3 May2019
   echo "
                            <script type=\"text/javascript\" src=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js\" integrity=\"sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS\" crossorigin=\"anonymous\"></script>";
                           echo "<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/bootsnav.js",1)."\"></script>";
                           //below one commented for speed
//echo "<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/jQueryTab.js",1)."\"></script>";
                             
                             echo  "<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/owl.carousel.js",1)."\"></script>";


                             /*global $js_owl,$css_owl;
                             ?>
                              <style>
						<?php
						//echo $css_owl;
						?>
						.customNavigation{
						text-align: center;
						}
						.customNavigation a{
						-webkit-user-select: none;
						-khtml-user-select: none;
						-moz-user-select: none;
						-ms-user-select: none;
						user-select: none;
						-webkit-tap-highlight-color: rgba(0, 0, 0, 0);
						}
						</style>
						<script>
						$(document).ready(function () {
						<?php //echo $js_owl;?>
						 });
						</script>
<?php 
*/
		
echo "<link href=\"".url_head_link("images/".$ecom_hostname."/".$css_folder."/jquery-ui.css",1)."\" rel=\"stylesheet\">"; ?>
                         <? /*<script type="text/javascript" src="http://dbushell.github.io/Responsive-Off-Canvas-Menu/js/main.js"></script>*/?>
     <?php
     //
   /*
    * below one coomented for  speed on o3 May2019
    echo "<script src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/jquery.backstretch.min.js",1)."\"></script> 
          <script src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/backstretch_scripts.js",1)."\"></script>";
          */ 
 //echo " <link href=\"".url_head_link("images/".$ecom_hostname."/".$css_folder."/bootstrap.min.css",1)."\" rel=\"stylesheet\">";
       echo " <link href=\"".url_head_link("images/".$ecom_hostname."/".$css_folder."/font-awesome.min.css",1)."\" rel=\"stylesheet\">";

       //echo  "<link href=\"".url_head_link("images/".$ecom_hostname."/".$css_folder."/backstretch_style.css",1)."\" media=\"screen\" type=\"text/css\" rel=\"stylesheet\" />";

     echo "<link href=\"".url_head_link("images/".$ecom_hostname."/".$css_folder."/".$ecom_themename.".css",1)."\" media=\"screen\" type=\"text/css\" rel=\"stylesheet\" />";
               //echo "<link href=\"".url_head_link("images/".$ecom_hostname."/".$css_folder."/".$ecom_themename.".min.css",1)."\" media=\"screen\" type=\"text/css\" rel=\"stylesheet\" />";

     
       echo " <link href=\"".url_head_link("images/".$ecom_hostname."/".$css_folder."/responsive.css",1)."\" rel=\"stylesheet\">";
        echo "<link href=\"".url_head_link("images/".$ecom_hostname."/".$css_folder."/overwrite.css",1)."\" rel=\"stylesheet\">";

    echo "<link href=\"".url_head_link("images/".$ecom_hostname."/".$css_folder."/owl.carousel.css",1)."\" rel=\"stylesheet\">";
        //echo "<link href=\"".url_head_link("images/".$ecom_hostname."/".$css_folder."/owl.theme.css",1)."\" rel=\"stylesheet\">";
   
        //echo "<link href=\"".url_head_link("images/".$ecom_hostname."/".$css_folder."/responsive_bootstrap_carousel.css",1)."\" rel=\"stylesheet\">";
     echo "<script type=\"text/javascript\" src=\"".url_head_link("scripts/validation.js",1)."\"></script>
     <script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/javascript.js",1)."\"></script>";
     ?>
</body>
</html>

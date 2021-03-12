<?php
	set_time_limit(0); // done to avoid page getting timed out
	require('config.php');
	require('functions/functions.php');
?>
	<html>
	<head>
	<title>Data Import Wizard</title>
	<style type="text/css">
		.normal_text
		{	
			font-family:Arial, Helvetica, sans-serif;
			font-size:11px;
			font-weight:normal;
			color:#000000;
		}
		.color_text
		{	
			font-family:Arial, Helvetica, sans-serif;
			font-size:11px;
			font-weight:normal;
			color:#990000;
		}
		.color_text_bold
		{	
			font-family:Arial, Helvetica, sans-serif;
			font-size:11px;
			font-weight:bold;
			color:#990000;
		}
		.bold_text
		{	
			font-family:Arial, Helvetica, sans-serif;
			font-size:11px;
			font-weight:bold;
			color:#000000;
		}
		
		.main_heading_text
		{	
			font-family:Arial, Helvetica, sans-serif;
			font-size:14px;
			font-weight:bold;
			color:#000000;
		}
		
		.heading_text
		{	
			padding: 2px 5px 2px 10px;
			background-color:#990000;
			font-family:Arial, Helvetica, sans-serif;
			font-size:12px;
			font-weight:bold;
			color:#FFFFFF;
		}
		.link_text
		{
			font-size:12px;
			font-weight:bold;
			color:#990000;
		}
		.button_style
		{
			background-color:#990000;
			font-family:Arial, Helvetica, sans-serif;
			font-size:12px;
			font-weight:bold;
			color:#FFFFFF;
		}
	</style>
	</head>
	<body>
	<center>
<?php
	switch($_REQUEST['next_step'])
	{
		case 'step2': // customer details start page
			require('includes/import_step2.php');
		break;
		case 'step3': // customer details result page
			$import_cust_details = import_Customer_Details();
			require('includes/import_step3.php');
		break;
		case 'step4': // start page for keywords and saved searches
			require('includes/import_step4.php');
		break;
		case 'step5': // saved search result page
			$import_saved_search = import_Keyword_Savedsearches();
			require('includes/import_step5.php');
		break;
		
		case 'step6': // start categories and category keyword mappings
			require('includes/import_step6.php'); // showing next step
		break;
		
		case 'step7':// import categories result page
			$import_cat_cnt = import_Product_Categories();	
			require('includes/import_step7.php'); // showing next step	
		break;
		
		case 'step8': // start product vendors section
			require('includes/import_step8.php'); // showing next step
		break;
		
		case 'step9': // Import vendors, vendor contacts and sizechart headings 
			$import_vendor_chart_head = import_Product_Vendors_SizechartHeadings();
			require('includes/import_step9.php'); // showing next step
		break;
		
		case 'step10': // start custom form elements
			require('includes/import_step10.php'); // showing next step
		break;
		
		case 'step11': // Import custom form and elements
			$import_custom_forms = import_Custom_Forms();
			require('includes/import_step11.php'); // showing next step
		break;
		
		case 'step12': // Start Products and its related details
			require('includes/import_step12.php'); // showing next step
		break;
		
		case 'step13': // Import Products and its related details
			$import_prod = import_Products();
			require('includes/import_step13.php'); // showing next step
		break;
		
		case 'step14': // start featured, section product map, promotional, gift voucher
			require('includes/import_step14.php'); // showing next step
		break;
		
		case 'step15': // Import featured, section product map, promotional and product mapped with promotional code
			$import_misc = Import_Featured_Promo();
			require('includes/import_step15.php'); // showing next step
		break;
		
		case 'step16': // start shop details
			require('includes/import_step16.php'); 
		break;
		
		case 'step17': // Import shop by brand, products mapped with shop by brand, keywords mapped with shop by brand
			$import_shop = Import_ShopbyBrand();
			require('includes/import_step17.php'); 
		break;
		
		case 'step18': // start static pagep details
			require('includes/import_step18.php'); 
		break;
		
		case 'step19': // Import static pagep details
			$import_stat = Import_Staticpages();
			require('includes/import_step19.php'); 
		break;
		
		case 'step20': // Start Survey
			require('includes/import_step20.php'); 
		break;
		
		case 'step21': // Import Survey Details
			$import_survey = Import_Survey();
			require('includes/import_step21.php'); 
		break;
		
		case 'step22': // Start Console User Details
			require('includes/import_step22.php'); 
		break;
		
		case 'step23':
			$import_users = Import_ConsoleUsers();
			require('includes/import_step23.php'); 
		break;
		
		case 'step24': // Start Saved Search
			require('includes/import_step24.php'); 
		break;

		
		case 'step25': // Import Image gallery details
			$import_image = Import_Images();
			require('includes/import_step25.php'); 
		break;
		
		case 'step26': // Import Summary
			require('includes/import_step26.php'); 
		break;
		
		default:
			require('includes/import_step1.php');
		break;
	};
?>
	</center>
	</body>
	</html>
<?php
	mysql_close($src_link);
	mysql_close($dest_link);
?>

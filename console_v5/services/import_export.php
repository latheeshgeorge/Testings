<?php
if($ecom_siteid==101 || $ecom_siteid==108)
	{
		echo "<span style='font-size:12px;font-weight:bold;color:#FF0000;'><br><br><br>Sorry!! you are not authorized to view this page</span>";
		exit;
	}
	if($_REQUEST['fpurpose']=='')
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ("includes/import_export/import_export.php");
	}
	elseif ($_REQUEST['fpurpose']=='show_importorexport_options')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../import_export_variables.php');
		include ('../includes/import_export/ajax/import_export_ajax_functions.php');
		show_importexport_selectbox($_REQUEST['main_select']);
	}
	elseif ($_REQUEST['fpurpose']=='show_export_fields')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include '../import_export_variables.php';
		include ('../includes/import_export/ajax/import_export_ajax_functions.php');
		show_export_fields($_REQUEST['export_what'],'export_main');
	}
	elseif ($_REQUEST['fpurpose']=='show_import_fields')
	{
			//echo $_REQUEST['import_what'] ."test";exit;
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include '../import_export_variables.php';
		include ('../includes/import_export/ajax/import_export_ajax_functions.php');
		show_import_fields($_REQUEST['import_what']);
	}
	//echo $_REQUEST['fpurpose1'];exit;
	if($_REQUEST['fpurpose1']=='show_import_fields' && $_REQUEST['fpurpose']!='show_import_fields'){
	//echo $_REQUEST['fpurpose1'];exit;
	show_import_fields($_REQUEST['cur_what']);
	//header('Location:home.php?request=import_export');
	}//echo $_REQUEST['fpurpose1'];exit;
	if($_REQUEST['fpurpose1']=='show_export_fields' && $_REQUEST['fpurpose']!='show_export_fields'){
	//echo $_REQUEST['fpurpose1'];exit;
	$ids =array();
	$ids= $_REQUEST['ids'];
	$id_arrays = explode('~',$ids);
	show_export_fields($_REQUEST['cur_what'],'export_from');
	//header('Location:home.php?request=import_export');
	}
	
?>

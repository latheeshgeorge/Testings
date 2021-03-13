<?php
if($_REQUEST['fpurpose']=='')
{
    $ajax_return_function = 'ajax_return_contents';
    include "ajax/ajax.php";
    include("includes/common_product_tabs/list_common_product_tabs.php");
}
elseif($_REQUEST['fpurpose']=='list_tab_maininfo')
{
	include_once("../functions/functions.php");
    include_once('../session.php');
    include_once("../config.php");
	include("../includes/common_product_tabs/ajax/common_product_tabs_ajax_functions.php"); 
	show_maininfo($_REQUEST['tab_id']); 
}
elseif($_REQUEST['fpurpose']=='list_tab_products')
{
	include_once("../functions/functions.php");
    include_once('../session.php');
    include_once("../config.php");
	include("../includes/common_product_tabs/ajax/common_product_tabs_ajax_functions.php"); 
	show_tabproducts($_REQUEST['tab_id']);
}
elseif($_REQUEST['fpurpose']=='ProdAssign')
{
	include ('includes/common_product_tabs/list_assignproduct.php');
}
elseif($_REQUEST['fpurpose']=='save_ProdAssign')
{
	$tab_id = $_REQUEST['pass_tab_id'];
	// Get the details of current Common Tab
	$sql_tab = "SELECT common_tab_id, tab_title,tab_content, tab_hide, sites_site_id 
						FROM 
							product_common_tabs 
						WHERE 
							common_tab_id = $tab_id 
							AND sites_site_id = $ecom_siteid 
						LIMIT 
							1";
	$ret_tab = $db->query($sql_tab);
	if($db->num_rows($ret_tab))
	{
		$row_tab = $db->fetch_array($ret_tab);
	}	
	$existing_pdts_ids = get_existing_common_prods($tab_id);
	foreach($_REQUEST['checkbox'] as $v)
	{	
		if(!in_array($v,$existing_pdts_ids))
		{
			$insert_array										= array();
			$insert_array['products_product_id']				= $v;
			$insert_array['tab_title']							= addslashes(stripslashes($row_tab['tab_title']));
			$insert_array['tab_hide']							= $row_tab['tab_hide'];
			$insert_array['product_common_tabs_common_tab_id']	= $tab_id;
			$db->insert_from_array($insert_array, 'product_tabs');
		}
	}
	echo "
		<br><font color=\"red\"><b>Prouct(s) Assigned Successfully</b></font><br>
		<br />
		<a class=\"smalllink\" href=\"home.php?request=common_prod_tab&search_name=".$_REQUEST['search_name']."&sort_by=".$_REQUEST['sort_by']."&sort_order=".$_REQUEST['sort_order']."&records_per_page=".$_REQUEST['records_per_page']."&pg=".$_REQUEST['pg']."&start=".$_REQUEST['start']."\">Go Back to the Product Common Tab Listing page</a><br />
		<br />
		<a class=\"smalllink\" href=\"home.php?request=common_prod_tab&fpurpose=edit&tab_id=".$tab_id."&search_name=".$_REQUEST['search_name']."&sort_by=".$_REQUEST['sort_by']."&sort_order=".$_REQUEST['sort_order']."&records_per_page=".$_REQUEST['records_per_page']."&pg=".$_REQUEST['pg']."&start=".$_REQUEST['start']."&curtab=products_tab_td\">Go Back to the Product Common Tab Edit Page</a><br />
		<br />
		<a class=\"smalllink\" href=\"home.php?request=common_prod_tab&fpurpose=add&search_name=".$_REQUEST['search_name']."&sort_by=".$_REQUEST['sort_by']."&sort_order=".$_REQUEST['sort_order']."&records_per_page=".$_REQUEST['records_per_page']."&pg=".$_REQUEST['pg']."&start=".$_REQUEST['start']."\">Go Back to Product Common Tab Add page</a><br />
		";
}
elseif($_REQUEST['fpurpose']=='prodUnAssign') //Un assign products
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	
	$id_arr 			= explode('~',$_REQUEST['del_ids']);
	$products_to_remove	=	array();	
	$tabid			= $_REQUEST['tab_id'];
	
	for($i=0;$i<count($id_arr);$i++)
	{
		$id = $id_arr[$i];	
		if($id)
		{
			// Delete the entry in product_tabs table
			$sql_delete = "DELETE FROM 
								product_tabs  
							WHERE 
								products_product_id = $id 
								AND product_common_tabs_common_tab_id = $tabid 
							LIMIT 
								1";
			$db->query($sql_delete);
		}	
	}
	$alert = 'Product(s) Unassigned Successfully';
	include("../includes/common_product_tabs/ajax/common_product_tabs_ajax_functions.php"); 
	show_tabproducts($_REQUEST['tab_id'],$alert);
}
else if($_REQUEST['fpurpose']=='change_hide')
{
    include_once("../functions/functions.php");
    include_once('../session.php');
    include_once("../config.php"); 
    if ($_REQUEST['header_ids'] == '')
    {
        $alert = 'Sorry!! Tabs not selected';
    }
    else
    {
		$ch_stat = ($_REQUEST['ch_status'])?1:0;
		$ch_arr = explode("~",$_REQUEST['header_ids']);
		for($i=0;$i<count($ch_arr);$i++)
		{
			$sql_update = "UPDATE product_tabs 
								SET 
									tab_hide = $ch_stat 
								WHERE 
									product_common_tabs_common_tab_id = ".$ch_arr[$i];
			$db->query($sql_update);
			$sql_update = "UPDATE product_common_tabs 
								SET 
									tab_hide = $ch_stat 
								WHERE 
									common_tab_id = ".$ch_arr[$i]." 
									AND sites_site_id = $ecom_siteid 
								LIMIT 
									1";
			$db->query($sql_update);
		}
		$alert = "Status Changed Successfully";
	}
	 include ('../includes/common_product_tabs/list_common_product_tabs.php');
}
else if($_REQUEST['fpurpose']=='delete')
{
    include_once("../functions/functions.php");
    include_once('../session.php');
    include_once("../config.php"); 
    if ($_REQUEST['del_ids'] == '')
    {
        $alert = 'Sorry!! Tabs(s) not selected';
    }
    else
    {
        $del_arr = explode("~",$_REQUEST['del_ids']);
        for($i=0;$i<count($del_arr);$i++)
        {
            if(trim($del_arr[$i]))
            {
				$sql_del_values = "DELETE 
									FROM 
										product_tabs 
									WHERE 
										product_common_tabs_common_tab_id=".$del_arr[$i];
				$db->query($sql_del_values);
				$sql_del_values = "DELETE 
										FROM 
											product_common_tabs 
										WHERE 
											common_tab_id=".$del_arr[$i]." 
											AND sites_site_id = $ecom_siteid 
										LIMIT 
											1";
				$db->query($sql_del_values);
            }	
        }
        $alert = "Tab(s) deleted Sucessfully";
    }
     include ('../includes/common_product_tabs/list_common_product_tabs.php');
}
else if($_REQUEST['fpurpose']=='add')
{
    include("includes/common_product_tabs/add_common_product_tabs.php");
}
else if($_REQUEST['fpurpose']=='edit')
{	
   $ajax_return_function = 'ajax_return_contents';
   include "ajax/ajax.php";
   $tab_id     = ($_REQUEST['tab_id']?$_REQUEST['tab_id']:$_REQUEST['checkbox'][0]);
   include("includes/common_product_tabs/ajax/common_product_tabs_ajax_functions.php");
   include("includes/common_product_tabs/edit_common_product_tabs.php");
}
else if($_REQUEST['fpurpose']=='save_add')
{
    if($_REQUEST['prodtab_Submit'])
    {
        //Function to validate forms
		$alert 				= '';
        $fieldRequired 		= array($_REQUEST['tab_title']);
		$fieldDescription 	= array('Enter Title');
		$fieldEmail 		= array();
		$fieldConfirm 		= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 		= array();
		$fieldNumericDesc 	= array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
        if($alert)
        {
            echo "<br><font color=\"red\"><b>Error!!&nbsp;&nbsp;</b> <?php echo $alert?></font><br>";
        }
        else
        {
			// Check whether there already exists an tab with same title
			$sql_check = "SELECT common_tab_id 
							FROM 
								product_common_tabs 
							WHERE 
								sites_site_id = $ecom_siteid 
								AND tab_title ='".addslashes($_REQUEST['tab_title'])."' 
							LIMIT 
								1";
			$ret_check = $db->query($sql_check);
			if($db->num_rows($ret_check)==0)
			{
				$insert_array							= array();
				$insert_array['sites_site_id'] 			= $ecom_siteid;
				$insert_array['tab_title']				= add_slash($_REQUEST['tab_title']);
				$insert_array['tab_content']			= add_slash($_REQUEST['tab_content'],false);
				$insert_array['tab_hide']				= ($_REQUEST['tab_hide'])?1:0;
				$db->insert_from_array($insert_array, 'product_common_tabs');
				$insert_id	= $db->insert_id();
				echo "
				<br><font color=\"red\"><b>Common Tab Added Successfully</b></font><br>
				<br />
				<a class=\"smalllink\" href=\"home.php?request=common_prod_tab&search_name=".$_REQUEST['search_name']."&sort_by=".$_REQUEST['sort_by']."&sort_order=".$_REQUEST['sort_order']."&records_per_page=".$_REQUEST['records_per_page']."&pg=".$_REQUEST['pg']."&start=".$_REQUEST['start']."\">Go Back to the Product Common Tab Listing page</a><br />
				<br />
				<a class=\"smalllink\" href=\"home.php?request=common_prod_tab&fpurpose=edit&tab_id=".$insert_id."&search_name=".$_REQUEST['search_name']."&sort_by=".$_REQUEST['sort_by']."&sort_order=".$_REQUEST['sort_order']."&records_per_page=".$_REQUEST['records_per_page']."&pg=".$_REQUEST['pg']."&start=".$_REQUEST['start']."\">Go Back to the Product Common Tab Edit Page</a><br />
				<br />
				<a class=\"smalllink\" href=\"home.php?request=common_prod_tab&fpurpose=add&search_name=".$_REQUEST['search_name']."&sort_by=".$_REQUEST['sort_by']."&sort_order=".$_REQUEST['sort_order']."&records_per_page=".$_REQUEST['records_per_page']."&pg=".$_REQUEST['pg']."&start=".$_REQUEST['start']."\">Go Back to Product Common Tab Add page</a><br />";
			}
			else
			{
				$alert = 'Sorry!! a tab with same title already exists';
				include("includes/common_product_tabs/add_common_product_tabs.php");
			}
        }
    }
}
else if($_REQUEST['fpurpose'] == 'save_edit')
{  // for updating the size chart

    if($_REQUEST['tab_id'])
    {
		$tab_id 			= $_REQUEST['tab_id'];
		$alert 				= '';
        $fieldRequired 		= array($_REQUEST['tab_title']);
		$fieldDescription 	= array('Enter Title');
		$fieldEmail 		= array();
		$fieldConfirm 		= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 		= array();
		$fieldNumericDesc 	= array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
        if($alert)
        {
            echo "<br><font color=\"red\"><b>Error!!&nbsp;&nbsp;</b> <?php echo $alert?></font><br>";
        }
        else
        {
			// Check whether there already exists an tab with same title
			$sql_check = "SELECT common_tab_id 
							FROM 
								product_common_tabs 
							WHERE 
								sites_site_id = $ecom_siteid 
								AND tab_title ='".addslashes($_REQUEST['tab_title'])."' 
								AND common_tab_id <> $tab_id 
							LIMIT 
								1";
			$ret_check = $db->query($sql_check);
			if($db->num_rows($ret_check)==0)
			{
				$update_array						= array();
				$update_array['tab_hide']			= ($_REQUEST['tab_hide'])?1:0;
				$update_array['tab_title']			= add_slash($_REQUEST['tab_title']);
				$update_array['tab_content']		= add_slash($_REQUEST['tab_content'],false);
				$db->update_from_array($update_array, 'product_common_tabs', array('common_tab_id' => $tab_id));
				
				$update_array						= array();
				$update_array['tab_hide']			= ($_REQUEST['tab_hide'])?1:0;
				$update_array['tab_title']			= add_slash($_REQUEST['tab_title']);
				$db->update_from_array($update_array, 'product_tabs', array('product_common_tabs_common_tab_id' => $tab_id));
				echo "
					<br><font color=\"red\"><b>Common Product Tab Updated Successfully</b></font><br>
					<br />
					<a class=\"smalllink\" href=\"home.php?request=common_prod_tab&search_name=".$_REQUEST['pass_search_name']."&sort_by=".$_REQUEST['pass_sort_by']."&sort_order=".$_REQUEST['pass_sort_order']."&records_per_page=".$_REQUEST['pass_records_per_page']."&pg=".$_REQUEST['pass_pg']."&start=".$_REQUEST['pass_start']."\">Go Back to the Product Common Tab Listing page</a><br />
					<br />
					<a class=\"smalllink\" href=\"home.php?request=common_prod_tab&fpurpose=edit&tab_id=".$tab_id."&pass_search_name=".$_REQUEST['pass_search_name']."&pass_sort_by=".$_REQUEST['pass_sort_by']."&pass_sort_order=".$_REQUEST['pass_sort_order']."&pass_records_per_page=".$_REQUEST['pass_records_per_page']."&pass_pg=".$_REQUEST['pass_pg']."&pass_start=".$_REQUEST['pass_start']."\">Go Back to the Product Common Tab Edit Page</a><br />
					<br />
					<a class=\"smalllink\" href=\"home.php?request=common_prod_tab&fpurpose=add&search_name=".$_REQUEST['pass_search_name']."&sort_by=".$_REQUEST['pass_sort_by']."&sort_order=".$_REQUEST['pass_sort_order']."&records_per_page=".$_REQUEST['pass_records_per_page']."&pg=".$_REQUEST['pass_pg']."&start=".$_REQUEST['pass_start']."\">Go Back to Product Common Tab Add page</a><br />
					";
			}
			else
			{
				$alert = 'Sorry!! a tab with same title already exists';
				$ajax_return_function = 'ajax_return_contents';
				include "ajax/ajax.php";
				$tab_id     = ($_REQUEST['tab_id']?$_REQUEST['tab_id']:$_REQUEST['checkbox'][0]);
				 include("includes/common_product_tabs/ajax/common_product_tabs_ajax_functions.php");
				include("includes/common_product_tabs/edit_common_product_tabs.php");
			}
        }
    }
}
function get_existing_common_prods($tab_id)
{
	global $db,$ecom_hostname,$ecom_siteid;
	$existing_pdts_ids			= array();
	$sql_existing_pdts			= "SELECT products_product_id FROM product_tabs WHERE product_common_tabs_common_tab_id=".$tab_id;
	$ret_existing_pdts			= $db->query($sql_existing_pdts);
	while($existing_pdts		= $db->fetch_array($ret_existing_pdts))
	{
		$exist_prod_id = 0;
		$exist_prod_id = $existing_pdts['products_product_id'];
		if($exist_prod_id)
			$existing_pdts_ids[]	= $exist_prod_id;
	}
	return $existing_pdts_ids;
}
?>
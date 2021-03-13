<?php
if($_REQUEST['fpurpose']=='')
{
    $ajax_return_function = 'ajax_return_contents';
    include "ajax/ajax.php";
    include("includes/common_product_attachments/list_common_product_attachments.php");
}
elseif($_REQUEST['fpurpose']=='list_attach_maininfo')
{
	include_once("../functions/functions.php");
    include_once('../session.php');
    include_once("../config.php");
	include("../includes/common_product_attachments/ajax/common_product_attachment_ajax_functions.php"); 
	show_maininfo($_REQUEST['attach_id']); 
}
elseif($_REQUEST['fpurpose']=='list_attached_products')
{
	include_once("../functions/functions.php");
    include_once('../session.php');
    include_once("../config.php");
	include("../includes/common_product_attachments/ajax/common_product_attachment_ajax_functions.php"); 
	show_attachedproducts($_REQUEST['attach_id']);
}
elseif($_REQUEST['fpurpose']=='ProdAssign')
{
	include ('includes/common_product_attachments/list_assignproduct.php');
}
elseif($_REQUEST['fpurpose']=='save_ProdAssign')
{
	$attach_id = $_REQUEST['pass_attach_id'];
	// Get the details of current common attachment
	$sql_attach = "SELECT  common_attachment_id, attachment_title, attachment_orgfilename, attachment_filename, attachment_type, attachment_hide, sites_site_id 
						FROM 
							product_common_attachments 
						WHERE 
							common_attachment_id = $attach_id 
							AND sites_site_id = $ecom_siteid 
						LIMIT 
							1";
	$ret_attach = $db->query($sql_attach);
	if($db->num_rows($ret_attach))
	{
		$row_attach = $db->fetch_array($ret_attach);
	}	
	$existing_pdts_ids = get_existing_common_prods($attach_id);
	foreach($_REQUEST['checkbox'] as $v)
	{	
		if(!in_array($v,$existing_pdts_ids))
		{
			$insert_array															= array();
			$insert_array['products_product_id']									= $v;
			$insert_array['attachment_title']										= addslashes(stripslashes($row_attach['attachment_title']));
			$insert_array['attachment_orgfilename']									= addslashes(stripslashes($row_attach['attachment_orgfilename']));
			$insert_array['attachment_filename']									= addslashes(stripslashes($row_attach['attachment_filename']));
			$insert_array['attachment_type']										= addslashes(stripslashes($row_attach['attachment_type']));
			$insert_array['attachment_hide']										= $row_attach['attachment_hide'];
			$insert_array['product_common_attachments_common_attachment_id']		= $attach_id;
			$db->insert_from_array($insert_array, 'product_attachments');
		}
	}
	echo "
		<br><font color=\"red\"><b>Prouct(s) Assigned Successfully</b></font><br>
		<br />
		<a class=\"smalllink\" href=\"home.php?request=common_prod_attachment&search_name=".$_REQUEST['pass_searchname']."&sort_by=".$_REQUEST['pass_sortby']."&sort_order=".$_REQUEST['pass_sortorder']."&records_per_page=".$_REQUEST['pass_records_per_page']."&pg=".$_REQUEST['pass_pg']."&start=".$_REQUEST['pass_start']."\">Go Back to the Product Common Attachment Listing page</a><br />
		<br />
		<a class=\"smalllink\" href=\"home.php?request=common_prod_attachment&fpurpose=edit&attach_id=".$attach_id."&search_name=".$_REQUEST['pass_searchname']."&sort_by=".$_REQUEST['pass_sortby']."&sort_order=".$_REQUEST['pass_sortorder']."&records_per_page=".$_REQUEST['pass_records_per_page']."&pg=".$_REQUEST['pass_pg']."&start=".$_REQUEST['pass_start']."&curtab=products_tab_td\">Go Back to the Product Common Attachment Edit Page</a><br />
		<br />
		<a class=\"smalllink\" href=\"home.php?request=common_prod_attachment&fpurpose=add&search_name=".$_REQUEST['pass_searchname']."&sort_by=".$_REQUEST['pass_sortby']."&sort_order=".$_REQUEST['pass_sortorder']."&records_per_page=".$_REQUEST['pass_records_per_page']."&pg=".$_REQUEST['pass_pg']."&start=".$_REQUEST['pass_start']."\">Go Back to Product Common Attachment Add page</a><br />
		";
}
elseif($_REQUEST['fpurpose']=='prodUnAssign') //Un assign products
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	
	$id_arr 			= explode('~',$_REQUEST['del_ids']);
	$products_to_remove	=	array();	
	$attachid			= $_REQUEST['attach_id'];
	
	for($i=0;$i<count($id_arr);$i++)
	{
		$id = $id_arr[$i];	
		if($id)
		{
			// Delete the entry in product_attachment table
			$sql_delete = "DELETE FROM 
								product_attachments  
							WHERE 
								products_product_id = $id 
								AND product_common_attachments_common_attachment_id = $attachid 
							LIMIT 
								1";
			$db->query($sql_delete);
		}	
	}
	$alert = 'Product(s) Unassigned Successfully';
	include("../includes/common_product_attachments/ajax/common_product_attachment_ajax_functions.php"); 
	show_attachedproducts($_REQUEST['attach_id'],$alert);
}
else if($_REQUEST['fpurpose']=='change_hide')
{
    include_once("../functions/functions.php");
    include_once('../session.php');
    include_once("../config.php"); 
    if ($_REQUEST['header_ids'] == '')
    {
        $alert = 'Sorry!! Attachments not selected';
    }
    else
    {
		$ch_stat = ($_REQUEST['ch_status'])?1:0;
		$ch_arr = explode("~",$_REQUEST['header_ids']);
		for($i=0;$i<count($ch_arr);$i++)
		{
			$sql_update = "UPDATE product_attachments 
								SET 
									attachment_hide = $ch_stat 
								WHERE 
									product_common_attachments_common_attachment_id = ".$ch_arr[$i];
			$db->query($sql_update);
			$sql_update = "UPDATE product_common_attachments 
								SET 
									attachment_hide = $ch_stat 
								WHERE 
									common_attachment_id = ".$ch_arr[$i]." 
									AND sites_site_id = $ecom_siteid 
								LIMIT 
									1";
			$db->query($sql_update);
		}
		$alert = "Status Changed Successfully";
	}
	 include ('../includes/common_product_attachments/list_common_product_attachments.php');
}
else if($_REQUEST['fpurpose']=='delete')
{
    include_once("../functions/functions.php");
    include_once('../session.php');
    include_once("../config.php"); 
    if ($_REQUEST['del_ids'] == '')
    {
        $alert = 'Sorry!! Attachment(s) not selected';
    }
    else
    {
        $del_arr = explode("~",$_REQUEST['del_ids']);
        for($i=0;$i<count($del_arr);$i++)
        {
            if(trim($del_arr[$i]))
            {
				// Get the details of current attachment 
				$sql_sel = "SELECT attachment_filename 
								FROM 
									product_common_attachments 
								WHERE 
									common_attachment_id =".$del_arr[$i]." 
								LIMIT 
									1";
				$ret_sel = $db->query($sql_sel);
				if($db->num_rows($ret_sel))
				{
					$row_sel = $db->fetch_array($ret_sel);
					@unlink($image_path."/commonattachments/".$row_sel['attachment_filename']);
					$sql_del_values = "DELETE 
                                    FROM 
                                        product_attachments 
                                    WHERE 
                                        product_common_attachments_common_attachment_id=".$del_arr[$i];
                	$db->query($sql_del_values);
					$sql_del_values = "DELETE 
										FROM 
											product_common_attachments 
										WHERE 
											common_attachment_id=".$del_arr[$i]." 
											AND sites_site_id = $ecom_siteid 
										LIMIT 
											1";
					$db->query($sql_del_values);
				}
            }	
        }
        $alert = "Attachment(s) deleted Sucessfully";
    }
     include ('../includes/common_product_attachments/list_common_product_attachments.php');
}
else if($_REQUEST['fpurpose']=='add')
{
    include("includes/common_product_attachments/add_common_product_attachments.php");
}
else if($_REQUEST['fpurpose']=='edit')
{	
   $ajax_return_function = 'ajax_return_contents';
   include "ajax/ajax.php";
   $attach_id     = ($_REQUEST['attach_id']?$_REQUEST['attach_id']:$_REQUEST['checkbox'][0]);
   include("includes/common_product_attachments/ajax/common_product_attachment_ajax_functions.php");
   include("includes/common_product_attachments/edit_common_product_attachments.php");
}
else if($_REQUEST['fpurpose']=='save_add')
{
    if($_REQUEST['prodattach_Submit'])
    {
        //Function to validate forms
		$alert = '';
        $fieldRequired 	= array($_REQUEST['attach_title']);
		$fieldDescription 	= array('Enter Title');
		$fieldEmail 	= array();
		$fieldConfirm 	= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 	= array();
		$fieldNumericDesc 	= array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
        if($alert)
        {
            echo "<br><font color=\"red\"><b>Error!!&nbsp;&nbsp;</b> <?php echo $alert?></font><br>";
        }
        else
        {
			if(!validate_attachment('attach_file',$_REQUEST['attach_type']))
			{
				//$alert = 'Sorry!! An attachment with same title already exists.';
				include("includes/common_product_attachments/add_common_product_attachments.php");
			}
			else
			{
				// Check whether there already exists an attachment with same title
				$sql_check = "SELECT common_attachment_id 
								FROM 
									product_common_attachments 
								WHERE 
									sites_site_id = $ecom_siteid 
									AND attachment_title ='".addslashes($_REQUEST['attach_title'])."' 
								LIMIT 
									1";
				$ret_check = $db->query($sql_check);
				if($db->num_rows($ret_check)==0)
				{
					$insert_array							= array();
					$org_filename							= str_replace(" ","_",$_FILES['attach_file']['name']);
					$insert_array['sites_site_id'] 			= $ecom_siteid;
					$insert_array['attachment_title']		= add_slash($_REQUEST['attach_title']);
					$insert_array['attachment_orgfilename']	= add_slash($org_filename);
					$insert_array['attachment_hide']		= ($_REQUEST['attach_hide'])?1:0;
					$insert_array['attachment_type']		= $_REQUEST['attach_type'];
					$db->insert_from_array($insert_array, 'product_common_attachments');
					$insert_id = $db->insert_id();
					$ret_img 	= save_commonattachment('attach_file',$insert_id);// Returns an array
					$alert		= $ret_img['alert'];	
					if(!$alert)
					{
						$filename								= $ret_img['filename'];
						$update_array							= array();
						$update_array['attachment_filename'] 	= $filename;
						$db->update_from_array($update_array, 'product_common_attachments', array('common_attachment_id' => $insert_id));
						//$alert .= '<br><span class="redtext"><b>Common Attachment Added Successfully</b></span><br>';
						//echo $alert;				
						echo "
						<br><font color=\"red\"><b>Common Attachment Added Successfully</b></font><br>
						<br />
						<a class=\"smalllink\" href=\"home.php?request=common_prod_attachment&search_name=".$_REQUEST['search_name']."&sort_by=".$_REQUEST['sort_by']."&sort_order=".$_REQUEST['sort_order']."&records_per_page=".$_REQUEST['records_per_page']."&pg=".$_REQUEST['pg']."&start=".$_REQUEST['start']."\">Go Back to the Product Common Attachment Listing page</a><br />
						<br />
						<a class=\"smalllink\" href=\"home.php?request=common_prod_attachment&fpurpose=edit&attach_id=".$insert_id."&search_name=".$_REQUEST['search_name']."&sort_by=".$_REQUEST['sort_by']."&sort_order=".$_REQUEST['sort_order']."&records_per_page=".$_REQUEST['records_per_page']."&pg=".$_REQUEST['pg']."&start=".$_REQUEST['start']."\">Go Back to the Product Common Attachment Edit Page</a><br />
						<br />
						<a class=\"smalllink\" href=\"home.php?request=common_prod_attachment&fpurpose=add&search_name=".$_REQUEST['search_name']."&sort_by=".$_REQUEST['sort_by']."&sort_order=".$_REQUEST['sort_order']."&records_per_page=".$_REQUEST['records_per_page']."&pg=".$_REQUEST['pg']."&start=".$_REQUEST['start']."\">Go Back to Product Common Attachment Add page</a><br />
						";
					
						}
						else
						{
							$alert 		= "Upload Failed";
							$sql_del 	= "DELETE FROM product_common_attachments WHERE common_attachment_id = $insert_id LIMIT 1";
							$db->query($sql_del);
							include("includes/common_product_attachments/add_common_product_attachments.php");
						}
					}
				else
				{
					$alert = 'Sorry!! an attachment with same title already exists';
					include("includes/common_product_attachments/add_common_product_attachments.php");
				}
				
			}	
        }
    }
}
else if($_REQUEST['fpurpose'] == 'save_edit')
{  // for updating the size chart
    if($_REQUEST['attach_id'])
    {
		$attach_id = $_REQUEST['attach_id'];
		$alert = '';
        $fieldRequired 	= array($_REQUEST['attach_title']);
		$fieldDescription 	= array('Enter Title');
		$fieldEmail 	= array();
		$fieldConfirm 	= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 	= array();
		$fieldNumericDesc 	= array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
        if($alert)
        {
            echo "<br><font color=\"red\"><b>Error!!&nbsp;&nbsp;</b> <?php echo $alert?></font><br>";
        }
        else
        {
			if(!validate_attachment('attach_file',$_REQUEST['attach_type'],'edit'))
			{
				//$alert = 'Sorry!! An attachment with same title already exists.';
				$ajax_return_function = 'ajax_return_contents';
				include "ajax/ajax.php";
				$attach_id     = ($_REQUEST['attach_id']?$_REQUEST['attach_id']:$_REQUEST['checkbox'][0]);
				 include("includes/common_product_attachments/ajax/common_product_attachment_ajax_functions.php");
				include("includes/common_product_attachments/edit_common_product_attachments.php");
			}
			else
			{
				// Check whether there already exists an attachment with same title
				$sql_check = "SELECT common_attachment_id 
								FROM 
									product_common_attachments 
								WHERE 
									sites_site_id = $ecom_siteid 
									AND attachment_title ='".addslashes($_REQUEST['attach_title'])."' 
									AND common_attachment_id <> $attach_id 
								LIMIT 
									1";
				$ret_check = $db->query($sql_check);
				if($db->num_rows($ret_check)==0)
				{
					if ($_FILES['attach_file']['name']) // case if file 
					{
						// Get the name of attachment
						$sql_attach = "SELECT attachment_filename FROM product_common_attachments WHERE common_attachment_id=".$attach_id;
						$ret_attach = $db->query($sql_attach);
						if ($db->num_rows($ret_attach))
						{
							$row_attach 	= $db->fetch_array($ret_attach);
							$del_name		= $row_attach['attachment_filename'];
							$attach_path 	= "$image_path/commonattachments/".$del_name;
							if(file_exists($attach_path)) 
								unlink($attach_path);  // unlinking the previous file
						}		
											
						$org_filename							= str_replace(" ","_",$_FILES['attach_file']['name']);
						$update_array							= array();
						$update_array['attachment_orgfilename']	= add_slash($org_filename);
						$update_array['attachment_title']		= add_slash($_REQUEST['attach_title']);
						$update_array['attachment_hide']		= ($_REQUEST['attach_hide'])?$_REQUEST['attach_hide']:0;
						$update_array['attachment_type']		= add_slash($_REQUEST['attach_type']);
						$db->update_from_array($update_array, 'product_common_attachments', array('common_attachment_id' => $attach_id));
						
						$update_array							= array();
						$update_array['attachment_orgfilename']	= add_slash($org_filename);
						$update_array['attachment_hide']		= ($_REQUEST['attach_hide'])?$_REQUEST['attach_hide']:0;
						$update_array['attachment_type']		= add_slash($_REQUEST['attach_type']);
						$db->update_from_array($update_array, 'product_attachments', array('product_common_attachments_common_attachment_id' => $attach_id));
						
						$ret_img 	= save_commonattachment('attach_file',$attach_id);// Returns an array
						$alert		= $ret_img['alert'];	
						
						
						if(!$alert)
						{
							$filename								= $ret_img['filename'];
							$update_array							= array();
							$update_array['attachment_filename'] 	= $filename;
							$db->update_from_array($update_array, 'product_common_attachments', array('common_attachment_id' => $attach_id));
							
							$update_array							= array();
							$update_array['attachment_filename'] 	= $filename;
							$db->update_from_array($update_array, 'product_attachments', array('product_common_attachments_common_attachment_id' => $attach_id));
							
							echo "
							<br><font color=\"red\"><b>Common Product Attachment Updated Successfully</b></font><br>
							<br />
							<a class=\"smalllink\" href=\"home.php?request=common_prod_attachment&search_name=".$_REQUEST['search_name']."&sort_by=".$_REQUEST['sort_by']."&sort_order=".$_REQUEST['sort_order']."&records_per_page=".$_REQUEST['records_per_page']."&pg=".$_REQUEST['pg']."&start=".$_REQUEST['start']."\">Go Back to the Product Common Attachment Listing page</a><br />
							<br />
							<a class=\"smalllink\" href=\"home.php?request=common_prod_attachment&fpurpose=edit&attach_id=".$attach_id."&search_name=".$_REQUEST['search_name']."&sort_by=".$_REQUEST['sort_by']."&sort_order=".$_REQUEST['sort_order']."&records_per_page=".$_REQUEST['records_per_page']."&pg=".$_REQUEST['pg']."&start=".$_REQUEST['start']."\">Go Back to the Product Common Attachment Edit Page</a><br />
							<br />
							<a class=\"smalllink\" href=\"home.php?request=common_prod_attachment&fpurpose=add&search_name=".$_REQUEST['search_name']."&sort_by=".$_REQUEST['sort_by']."&sort_order=".$_REQUEST['sort_order']."&records_per_page=".$_REQUEST['records_per_page']."&pg=".$_REQUEST['pg']."&start=".$_REQUEST['start']."\">Go Back to Product Common Attachment Add page</a><br />
							";
						}
						else
						{
							$alert = "Upload Failed";
							$ajax_return_function = 'ajax_return_contents';
							include "ajax/ajax.php";
							$attach_id     = ($_REQUEST['attach_id']?$_REQUEST['attach_id']:$_REQUEST['checkbox'][0]);
							 include("includes/common_product_attachments/ajax/common_product_attachment_ajax_functions.php");
							include("includes/common_product_attachments/edit_common_product_attachments.php");
						}
					}	
					else
					{
						
						$update_array							= array();
						$update_array['attachment_hide']		= ($_REQUEST['attach_hide'])?1:0;
						$update_array['attachment_title']		= add_slash($_REQUEST['attach_title']);
						$db->update_from_array($update_array, 'product_common_attachments', array('common_attachment_id' => $attach_id));
						
						$update_array							= array();
						$update_array['attachment_hide']		= ($_REQUEST['attach_hide'])?1:0;
						$db->update_from_array($update_array, 'product_attachments', array('attachment_id' => $attach_id));
						echo "
							<br><font color=\"red\"><b>Common Product Attachment Updated Successfully</b></font><br>
							<br />
							<a class=\"smalllink\" href=\"home.php?request=common_prod_attachment&search_name=".$_REQUEST['search_name']."&sort_by=".$_REQUEST['sort_by']."&sort_order=".$_REQUEST['sort_order']."&records_per_page=".$_REQUEST['records_per_page']."&pg=".$_REQUEST['pg']."&start=".$_REQUEST['start']."\">Go Back to the Product Common Attachment Listing page</a><br />
							<br />
							<a class=\"smalllink\" href=\"home.php?request=common_prod_attachment&fpurpose=edit&attach_id=".$attach_id."&search_name=".$_REQUEST['search_name']."&sort_by=".$_REQUEST['sort_by']."&sort_order=".$_REQUEST['sort_order']."&records_per_page=".$_REQUEST['records_per_page']."&pg=".$_REQUEST['pg']."&start=".$_REQUEST['start']."\">Go Back to the Product Common Attachment Edit Page</a><br />
							<br />
							<a class=\"smalllink\" href=\"home.php?request=common_prod_attachment&fpurpose=add&search_name=".$_REQUEST['search_name']."&sort_by=".$_REQUEST['sort_by']."&sort_order=".$_REQUEST['sort_order']."&records_per_page=".$_REQUEST['records_per_page']."&pg=".$_REQUEST['pg']."&start=".$_REQUEST['start']."\">Go Back to Product Common Attachment Add page</a><br />
							";
					}
				}
				else
				{
					$alert = 'Sorry!! an attachment with same title already exists';
					$ajax_return_function = 'ajax_return_contents';
					include "ajax/ajax.php";
					$attach_id     = ($_REQUEST['attach_id']?$_REQUEST['attach_id']:$_REQUEST['checkbox'][0]);
					 include("includes/common_product_attachments/ajax/common_product_attachment_ajax_functions.php");
					include("includes/common_product_attachments/edit_common_product_attachments.php");

				}
				
			}	
        }
    }
}
function get_existing_common_prods($attach_id)
{
	global $db,$ecom_hostname,$ecom_siteid;
	$existing_pdts_ids			= array();
	$sql_existing_pdts			= "SELECT products_product_id FROM product_attachments WHERE product_common_attachments_common_attachment_id=".$attach_id;
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
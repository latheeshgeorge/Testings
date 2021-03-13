<?php
if($_REQUEST['fpurpose']=='')
{
    $ajax_return_function = 'ajax_return_contents';
    include "ajax/ajax.php";
    include("includes/colors/list_colors.php");
}
elseif ($_REQUEST['fpurpose']=='add_colorimg')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include "includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	include("includes/image_gallery/list_images.php");
}
elseif ($_REQUEST['fpurpose']=='rem_colorimg')
{
	$update_sql = "UPDATE 
						general_settings_site_colors  
					SET 
						images_image_id = 0 
					WHERE 
						color_id = ".$_REQUEST['src_id']." 
					LIMIT 
						1";
	$db->query($update_sql);
	$alert 					= 'Image unassigned successfully';
	$ajax_return_function 	= 'ajax_return_contents';
	include "ajax/ajax.php";
	$color_id     			= ($_REQUEST['color_id']?$_REQUEST['color_id']:$_REQUEST['checkbox'][0]);
	include("includes/colors/edit_colors.php");
}
else if($_REQUEST['fpurpose']=='delete')
{
    include_once("../functions/functions.php");
    include_once('../session.php');
    include_once("../config.php"); 
    if ($_REQUEST['del_ids'] == '')
    {
        $alert = 'Sorry colors not selected';
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
                                        general_settings_site_colors 
                                    WHERE 
                                        color_id=".$del_arr[$i]." 
                                        AND sites_site_id = ".$ecom_siteid." 
                                    LIMIT 
                                        1";
                $db->query($sql_del_values);
            }	
        }
        $alert = "Product variable color(s) deleted Sucessfully";
    }
    include ('../includes/colors/list_colors.php');
}
else if($_REQUEST['fpurpose']=='add')
{
    include("includes/colors/add_colors.php");
}
else if($_REQUEST['fpurpose']=='edit')
{	
    $ajax_return_function = 'ajax_return_contents';
    include "ajax/ajax.php";
    $color_id     = ($_REQUEST['color_id']?$_REQUEST['color_id']:$_REQUEST['checkbox'][0]);
    include("includes/colors/edit_colors.php");
}
else if($_REQUEST['fpurpose']=='insert')
{
    if($_REQUEST['Submit'])
    {
        //Function to validate forms
        validate_forms();
        if($alert)
        {
            echo "<br><font color=\"red\"><b>Error!!&nbsp;&nbsp;</b> <?php echo $alert?></font><br>";
        }
        else
        {
            $insert_array				= array();
            $insert_array['sites_site_id'] 		= $ecom_siteid;
            $insert_array['color_name'] 		= addslashes($_REQUEST['color_name']);
            $insert_array['color_hexcode']		= addslashes($_REQUEST['color_hexcode']);
            $db->insert_from_array($insert_array, 'general_settings_site_colors');
            $insert_id = $db->insert_id();
            echo "
                <br><font color=\"red\"><b>Product Variable Color Added Successfully</b></font><br>
                <br />
                <a class=\"smalllink\" href=\"home.php?request=colorcodes&search_name=".$_REQUEST['search_name']."&sort_by=".$_REQUEST['sort_by']."&sort_order=".$_REQUEST['sort_order']."&records_per_page=".$_REQUEST['records_per_page']."&pg=".$_REQUEST['pg']."&start=".$_REQUEST['start']."\">Go Back to the Product Variable Colors Listing page</a><br />
                <br />
                <a class=\"smalllink\" href=\"home.php?request=colorcodes&fpurpose=edit&color_id=".$insert_id."&search_name=".$_REQUEST['search_name']."&sort_by=".$_REQUEST['sort_by']."&sort_order=".$_REQUEST['sort_order']."&records_per_page=".$_REQUEST['records_per_page']."&pg=".$_REQUEST['pg']."&start=".$_REQUEST['start']."\">Go Back to the Product Colors Edit Page</a><br />
                <br />
                <a class=\"smalllink\" href=\"home.php?request=colorcodes&fpurpose=add&search_name=".$_REQUEST['search_name']."&sort_by=".$_REQUEST['sort_by']."&sort_order=".$_REQUEST['sort_order']."&records_per_page=".$_REQUEST['records_per_page']."&pg=".$_REQUEST['pg']."&start=".$_REQUEST['start']."\">Go Back to Product Colors  Add page</a><br />
                ";
        }
    }
}
else if($_REQUEST['fpurpose'] == 'update_colors')
{  // for updating the size chart
    if($_REQUEST['color_id'])
    {
        //Function to validate forms
        validate_forms();
        if (!$alert)
        {
            $update_array			= array();
            $update_array['sites_site_id'] 	= $ecom_siteid;
            $update_array['color_name']		= addslashes($_REQUEST['color_name']);
            $update_array['color_hexcode']	= addslashes($_REQUEST['color_hexcode']);
            $db->update_from_array($update_array, 'general_settings_site_colors', array('color_id' => $_REQUEST['color_id'] , 'sites_site_id' => $ecom_siteid));
            echo "
                <br><font color=\"red\"><b>Product Variable Color Updated Successfully</b></font><br>
                <br />
                <a class=\"smalllink\" href=\"home.php?request=colorcodes&search_name=".$_REQUEST['search_name']."&sort_by=".$_REQUEST['sort_by']."&sort_order=".$_REQUEST['sort_order']."&records_per_page=".$_REQUEST['records_per_page']."&pg=".$_REQUEST['pg']."&start=".$_REQUEST['start']."\">Go Back to the Product Variable Color Listing page</a><br />
                <br />
                <a class=\"smalllink\" href=\"home.php?request=colorcodes&fpurpose=edit&color_id=".$_REQUEST['color_id']."&search_name=".$_REQUEST['search_name']."&sort_by=".$_REQUEST['sort_by']."&sort_order=".$_REQUEST['sort_order']."&records_per_page=".$_REQUEST['records_per_page']."&pg=".$_REQUEST['pg']."&start=".$_REQUEST['start']."\">Go Back to the Product Variable Color Edit Page</a><br />
                <br />
                <a class=\"smalllink\" href=\"home.php?request=colorcodes&fpurpose=add&search_name=".$_REQUEST['search_name']."&sort_by=".$_REQUEST['sort_by']."&sort_order=".$_REQUEST['sort_order']."&records_per_page=".$_REQUEST['records_per_page']."&pg=".$_REQUEST['pg']."&start=".$_REQUEST['start']."\">Go Back to Product Variable Color Add page</a><br />
                <br />
                ";
        }
        else
        {
            echo "
                <br><font color=\"red\"><b>Error!!&nbsp;&nbsp;</b>".$alert."</font><br>
                ";
        }
    }
}
function validate_forms()
{
    global $alert,$db;
    //Validations
    $alert = '';
    $fieldRequired 	= array($_REQUEST['color_name'],$_REQUEST['color_hexcode']);
    $fieldDescription 	= array('Name of color','Color Code');
    $fieldEmail 	= array();
    $fieldConfirm 	= array();
    $fieldConfirmDesc 	= array();
    $fieldNumeric 	= array();
    $fieldNumericDesc 	= array();
    serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
}
?>
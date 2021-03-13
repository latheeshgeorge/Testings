<?php
if($_REQUEST['fpurpose']=='')
{
    include("includes/newsletter_default_product_layout/edit_newsletter_default_product_layout.php");
}
else if($_REQUEST['fpurpose']=='Save_details')
{
    $update_array                                 = array();
    $update_array['template_product_layout']      = addslashes($_REQUEST['template_product_layout']);
    $db->update_from_array($update_array,'sites',array('site_id'=>$ecom_siteid));
    $alert                                        = 'Template saved successfully';
    include("includes/newsletter_default_product_layout/edit_newsletter_default_product_layout.php");  
}
?>
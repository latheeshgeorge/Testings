<? require_once("sites.php");
	require_once("config.php");
include_once('classes/db_class.inc.php');

$sql_test = "SELECT * FROM general_settings_site_checkoutfields";
echo $sql_test;
$ret_test = $db->query($sql_test);$insert_array =array();
while($row_test=$db->fetch_array($ret_test))
{
/*$insert_array['field_key']					=	$row_test['field_key'];
$insert_array['field_name']					=	$row_test['field_name'];
$insert_array['field_req']					=	$row_test['field_req'];
$insert_array['field_order']					=	$row_test['field_order'];
$insert_array['field_type']					=	$row_test['field_type'];
$insert_array['field_error_msg']					=	$row_test['field_error_msg'];
$insert_array['field_orgname']					=	$row_test['field_orgname'];*/
//$db->insert_from_array($insert_array, 'common_checkoutfields');
}
print_r($insert_array);
?>
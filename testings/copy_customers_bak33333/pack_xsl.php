<?php
include_once('../../classes/db_class.inc.php');	// Page which holds the class for db operations
include '../../config_db.php';
$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
$db->connect();
$db->select_db();

$sql_tax = "SELECT google_taxonomy_id, google_taxonomy_keyword
					FROM 
						google_productcategory_taxonomy";
	$ret_tax = $db->query($sql_tax);

//$result=mysql_query("select * from tbl_name");
    function xlsBOF()
    {
    echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
    return;
    }
    function xlsEOF()
    {
    echo pack("ss", 0x0A, 0x00);
    return;
    }
    function xlsWriteNumber($Row, $Col, $Value)
    {
    echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
    echo pack("d", $Value);
    return;
    }
    function xlsWriteLabel($Row, $Col, $Value )
    {
    $L = strlen($Value);
    echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
    echo $Value;
    return;
    }
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");;
    header("Content-Disposition: attachment;filename=list.xls");
    header("Content-Transfer-Encoding: binary ");
    xlsBOF();
     
    xlsWriteLabel(0,0,"Heading1");
    xlsWriteLabel(0,1,"Heading2");
    xlsWriteLabel(0,2,"Heading3");
    $xlsRow = 1;
    while($row = $db->fetch_array($ret_tax))
    {
    xlsWriteLabel($xlsRow,0,$row['google_taxonomy_id']);
    xlsWriteLabel($xlsRow,1,$row['google_taxonomy_keyword']);
    //xlsWriteNumber($xlsRow,2,$row['field3']);
    $xlsRow++;
    }
    xlsEOF();
?>

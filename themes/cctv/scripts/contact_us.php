<?
$title = $_REQUEST['title'];
$fname = $_REQUEST['fname'];
$sname = $_REQUEST['sname']; 
$address = $_REQUEST['address'];
$phone = $_REQUEST['phone'];
$fax = $_REQUEST['fax'];
$email = $_REQUEST['email']; 
$stock_val = $_REQUEST['stock_val'];
$business_type = $_REQUEST['business_type'];
$value_cost = $_REQUEST['value_cost'];
$buying_grp = $_REQUEST['buying_grp']; 
$mem_det = $_REQUEST['mem_det'];

$cont_submit = "Title  :$title <br>";
$cont_submit . = "First Name  : $fname <br>";
$cont_submit .= "Sur Name  : $sname <br>";
$cont_submit .= "Address  : $title <br>";
$cont_submit .= "Phone  : $phone <br>";
$cont_submit .= "Fax  :$fax <br>"; 
$cont_submit . = "Email  :$email <br>";
$cont_submit .= "Type of stock valuation  :$stock_val <br>";
$cont_submit .= "Type of business   :$business_type <br>";
$cont_submit .= "To assist us in providing the correct staff levels please indicate the expected value at cost   :$value_cost <br>";
$cont_submit .= "Do you belong to any trade bodies or buying groups   :$buying_grp <br>"; 
$cont_submit .= "PLEASE SHOW YOUR MEMBERSHIP NUMBER /DETAILS   :$mem_det <br>"; 
//$to      = 'stocktakers@ntlworld.com';
$to      = 'latheeshgeorge@gmail.com';
$subject = 'Contact Us';
$message = $cont_submit;
$headers = 'From: http://gandy.b-shop.co.uk' . "\r\n";
mail($to, $subject, $message, $headers);
 
?>

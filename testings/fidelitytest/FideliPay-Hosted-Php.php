<?PHP 
if (isset($_POST['responseCode'])) {
	echo '<p><strong>FideliPay Response</strong></p>';
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
}
$action = "https://gateway.fidelipay.co.uk/paymentform/";
$sig_key = "Custom29Simple14Marker";

$fields = array(	
	"merchantID" => '101094', 
	"action" => "SALE",
	"type" => 1,
	"amount" => 1203,
	"transactionUnique" => uniqid(),
	"orderRef" => "Test purchase",
	"currencyCode" => 826,
	"countryCode" => '',
	 "custom" => 'this_this',
	"redirectURL" => ($_SERVER['HTTPS'] == 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] 
);
	
// Function to create a message signature
function createSignature(array $data, $key) {
	// Sort by field name
ksort($data);
// Create the URL encoded signature string 
$ret= http_build_query($data, '', '&');
// Normalise all line endings (CRNL|NLCR|NL|CR) to just NL (%0A)
$ret= str_replace(array('%0D%0A', '%0A%0D', '%0D'), '%0A', $ret);
// Hash the signature string and the key together
return hash('SHA512',$ret . $key);
}
?>

<form action="<?= $action ?>" method="post">
	
	<?	foreach ($fields as $key => $value) { ?>
			<input type="hidden" name="<?= $key ?>" value="<?= $value ?>">			
	<?	}
	
		if (isset($sig_key)) { ?>
			<input type="hidden" name="signature" value="<?= createSignature($fields, $sig_key, 'SHA512') ?>" />
	<?	} ?>
	<input type="submit" value="Pay Now">
</form>

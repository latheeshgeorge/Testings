<?php

// reference the Dompdf namespace
use Dompdf\Dompdf;
//use Dompdf\Options;
// include autoloader
require_once 'dompdf/autoload.inc.php';

$dompdf = new Dompdf();

$contxt = stream_context_create([ 
    'ssl' => [ 
        'verify_peer' => FALSE, 
        'verify_peer_name' => FALSE,
        'allow_self_signed'=> TRUE
    ] 
]);
$dompdf->setHttpContext($contxt);



//$dompdf = new Dompdf();


$context = stream_context_create(array('http' => array('header'=>'Connection: close\r\n')));
//$html = file_get_contents("pdf-content.html",false,$context);
$html = file_get_contents("pdf-content.html");

$html1 = ' 

										<table style="background-color: #FFF; width: 80%;" border="1" cellspacing="0" cellpadding="0">
										<tr>
											<td class="class_short_new">
												<img src="http://demo1.bshop4.co.uk/images/demo1.bshop4.co.uk/adverts/rotate/544f89c8a518a.jpg" width="200px" alt="test"/>
											</td>
											</tr>
											<tr>
											
										</table>';
$dompdf->loadHtml(convertimg($html));

// (Optional) Setup the paper size and orientation
//$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();


// Output the generated PDF (1 = download and 0 = preview)
$dompdf->stream("test",array("Attachment"=>0));

//file_put_contents('/var/www/html/webclinic/bshop4/testings/sony/test.pdf', $dompdf->output());


function urlmodified($htm)
{
	$modf = 'http://demo1.bshop4.co.uk';
	$modt = '../../';
	//return $htm	;
	return str_replace($modf,$modt,$htm);
}


function convertimg($htm)
{
	
	$pattern = '~(http.*\.)(jpe?g|png|[tg]iff?|svg)~i';

    $m = preg_match_all($pattern,$htm,$matches);

    	
	if(count($matches[0])>0)
	{
		for($i=0;$i<count($matches[0]);$i++)
		{
			$avatarUrl = $matches[0][$i];
			$arrContextOptions=array(
						"ssl"=>array(
							"verify_peer"=>false,
							"verify_peer_name"=>false,
						),
					);
			$type = pathinfo($avatarUrl, PATHINFO_EXTENSION);
			//$avatarData = file_get_contents($avatarUrl, false, stream_context_create($arrContextOptions));
			$avatarData = file_get_contents($avatarUrl);
			/*$ch = curl_init();
			curl_setopt ($ch, CURLOPT_URL, $avatarUrl);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
			$contents = curl_exec($ch);
			if (curl_errno($ch)) {
			   echo curl_error($ch);
			   echo "\n<br />";
			   $contents = '';
			} else {
			  curl_close($ch);
			}

			$avatarData =  $contents;*/
			
			
			$avatarBase64Data = base64_encode($avatarData);
			$imageData = 'data:image/' . $type . ';base64,' . $avatarBase64Data;
			$htm = str_replace($matches[0][$i],$imageData,$htm);
		}	
	}
	return $htm;
}

?>

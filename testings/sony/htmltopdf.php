<?php

// reference the Dompdf namespace
use Dompdf\Dompdf;
//use Dompdf\Options;
// include autoloader
require_once 'dompdf/autoload.inc.php';



// instantiate and use the dompdf class
/*$options = new Options();
$options->set('isRemoteEnabled', TRUE);*/

//if(!defined("DOMPDF_ENABLE_REMOTE")){
  //define("DOMPDF_ENABLE_REMOTE", true);
  //define("DOMPDF_ENABLE_AUTOLOAD", false);
  
 
 //} 


//$dompdf = new Dompdf($options);
$dompdf = new Dompdf(array('DOMPDF_ENABLE_REMOTE' => true,'DOMPDF_PDF_BACKEND' => 'CPDF'));

/*$contxt = stream_context_create([ 
    'ssl' => [ 
        'verify_peer' => FALSE, 
        'verify_peer_name' => FALSE,
        'allow_self_signed'=> TRUE
    ] 
]);
$dompdf->setHttpContext($contxt);*/



//$dompdf = new Dompdf();


//$context = stream_context_create(array('http' => array('header'=>'Connection: close\r\n')));
//file_get_contents("http://www.something.com/somepage.html",false,$context);
$html = file_get_contents("pdf-content.html",false,$context);

$html1 = ' 

										<table style="background-color: #FFF; width: 80%;" border="1" cellspacing="0" cellpadding="0">
										<tr>
											<td class="class_short_new">
												<img src="http://demo1.bshop4.co.uk/images/demo1.bshop4.co.uk/adverts/rotate/544f89c8a518a.jpg" width="200px" alt="test"/>
											</td>
											</tr>
											<tr>
											
										</table>';
$dompdf->loadHtml(urlmodified($html));

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

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

?>

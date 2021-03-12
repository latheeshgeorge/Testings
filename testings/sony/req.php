<?php


$html = file_get_contents("pdf-content.html");
$htm = convertimg($html);
echo $htm;
function convertimg($htm)
{
	
	$pattern = '~(http.*\.)(jpe?g|png|[tg]iff?|svg)~i';

    $m = preg_match_all($pattern,$htm,$matches);

    	
	if(count($matches[0])>0)
	{
		for($i=0;$i<count($matches[0]);$i++)
		{
			echo "<br>".$avatarUrl = $matches[0][$i];
			$arrContextOptions=array(
						"ssl"=>array(
							"verify_peer"=>false,
							"verify_peer_name"=>false,
						),
					);
			$type = pathinfo($avatarUrl, PATHINFO_EXTENSION);
			//$avatarData = file_get_contents($avatarUrl, false, stream_context_create($arrContextOptions));
			
			$ch = curl_init();
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

			$avatarData =  $contents;
			
			
			$avatarBase64Data = base64_encode($avatarData);
			$imageData = 'data:image/' . $type . ';base64,' . $avatarBase64Data;
			$htm = str_replace($matches[0][$i],$imageData,$htm);
		}	
	}
	return $htm;
}
?>

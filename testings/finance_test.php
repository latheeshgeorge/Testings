<?php
$interface = "http://www.4minutefinance.com/api/test/api.php";
$postFields = Array(
"action" => "NewApp",
"Identification[api_key]" => "e27384a95926ba02d6a40ebb296f4abc",
"Identification[InstallationID]" => "4546",
"Identification[RetailerUniqueRef]" => "2000",
"Goods[Description]" => "My Test order",
"Goods[Price]" => "300",
"Finance[Code]" => "ONIF6",
"Finance[Deposit]" => "30"
);
$curlSession = curl_init();
curl_setopt($curlSession, CURLOPT_URL, $interface);
curl_setopt($curlSession, CURLOPT_HEADER, 0);
curl_setopt($curlSession, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curlSession, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($curlSession, CURLOPT_POST, 1);
curl_setopt($curlSession, CURLOPT_POSTFIELDS, $postFields);
$curl_response = curl_exec($curlSession);
echo $curl_response;
?>

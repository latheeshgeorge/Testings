<?php
$uniqid_store = 311545;//uniqid (rand ());
$interface = "http://www.4minutefinance.com/api/test/api.php";
$postFields = Array(
"action" => "NewApp",
"Identification[api_key]" => "e27384a95926ba02d6a40ebb296f4abc",
"Identification[InstallationID]" => "4546",
"Identification[RetailerUniqueRef]" => "$uniqid_store",
"Goods[Description]" => "Latest Order",
"Goods[Price]" => "35000",
"Finance[Code]" => "ONIB24-19.5",
"Finance[Deposit]" => "3500"
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
echo $curl_response;//.' ----- '.$uniqid_store;
header("Location:$curl_response");
?>

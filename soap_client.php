<?php

$options = array("location" => "http://localhost/SoapServerWebService/soap_server.php",
	"uri" => "http://localhost/SoapServerWebService");

try
{
	$client = new SoapClient(null, $options);
	$client_all_info = $client->getAllInfo();
//	echo var_dump($client_all_info);
}
catch(SoapFault $e)
{
	var_dump($e);
}

?>
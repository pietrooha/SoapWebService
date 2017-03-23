<?php

require('ClientData.php');

$options = array("uri" => "http://localhost/");

$server = new SoapServer(null, $options);
$server->setClass('ClientData');
//$server->addFunction('getClientIp');
$server->handle();

?>
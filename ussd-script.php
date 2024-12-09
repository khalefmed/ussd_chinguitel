<?php

require_once 'vendor/autoload.php';

$service = new \PhpSmpp\Service\Sender(['172.17.10.54:2776'], 'BMIussd', 'BMIussd','transceiver');
$service->client->debug = true;
//$service->client->setTransport(new \PhpSmpp\Transport\FakeTransport());
$smsId = $service->sendUSSD(22220556351,"message","Smpp", []);

?>

<?php

require_once 'vendor/autoload.php';

$service = new \PhpSmpp\Service\Sender(['172.17.10.54:2776'], 'BMIussd', 'BMIussd','transmitter');
$service->client->debug = true;
//$service->client->setTransport(new \PhpSmpp\Transport\FakeTransport());
$smsId = $service->send("22220662105", 'You have stage in google congratulations', 'Google');

?>


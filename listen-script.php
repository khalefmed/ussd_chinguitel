<?php

require_once 'vendor/autoload.php';

$listener = new \PhpSmpp\Service\Listener(['172.17.10.54:2776'], 'BMIussd', 'BMIussd', \PhpSmpp\Client::BIND_MODE_TRANSCEIVER);
$listener->client->debug = true;
$sender = new \PhpSmpp\Service\Sender(['172.17.10.54:2776'], 'BMIussd', 'BMIussd', \PhpSmpp\Client::BIND_MODE_TRANSCEIVER);
$sender->client->debug = true;
$listener->listenOnce(function (PhpSmpp\Pdu\Pdu $pdu) use ($sender) {
$smsId = $sender->sendUSSD('22220556351', 'Bienvenu sur Sedad bank', 'Next', []);
});
?>




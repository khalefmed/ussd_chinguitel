<?php

require_once 'vendor/autoload.php';

$listener = new \PhpSmpp\Service\Listener(['41.223.99.72:5000'], 'bmiuser', 'BmPw@mattel.0823', \PhpSmpp\Client::BIND_MODE_TRANSCEIVER);
$listener->client->debug = true;
$sender = new \PhpSmpp\Service\Sender([['41.223.99.72:5000']], 'bmiuser', 'BmPw@mattel.0823', \PhpSmpp\Client::BIND_MODE_TRANSCEIVER);
$sender->client->debug = true;
$listener->listen(function (PhpSmpp\Pdu\Pdu $pdu) use ($sender) {
var_dump($pdu->message);
var_dump($pdu->source->value);
var_dump($pdu->message);
var_dump($pdu->status);
var_dump($pdu->sequence);
var_dump($pdu->destination->value);
if ($pdu instanceof \PhpSmpp\Pdu\Ussd) {
$destinationValueAsString = (string) $pdu->source->value;
var_dump($pdu->source->value);
var_dump($pdu->message);
var_dump($pdu->status);
var_dump($pdu->sequence);
var_dump($pdu->destination->value);

$tag1 = PhpSmpp\Pdu\Part\TagUssdServiceOp::build(1,2);

if ($pdu->message=="*777#"){
$smsId = $sender->sendUSSD($destinationValueAsString, "Acceptez-vous les termes et Conditins d'utilisation de Sedad Bank\x0D\x0A1. Accepter et Continuer\x0D\x0A2. Annuler", 'Next', []);
}
else{
$smsId = $sender->sendUSSD($destinationValueAsString, "Choix non valide. Veuillez ressayer.", 'Next', []);
}}});
?>




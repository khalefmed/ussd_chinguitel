<?php

require_once 'vendor/autoload.php';

$listener = new \PhpSmpp\Service\Listener(['41.223.99.72:5000'], 'bmiuser', 'BmPw@mattel.0823', \PhpSmpp\Client::BIND_MODE_TRANSCEIVER);
$listener->client->debug = true;
$sender = new \PhpSmpp\Service\Sender(['41.223.99.72:5000'], 'bmiuser', 'BmPw@mattel.0823', \PhpSmpp\Client::BIND_MODE_TRANSCEIVER);
$sender->client->debug = true;

//list of phones sessions 
$phoneCodeList = array();
$phoneTryList = array();
function addOrUpdatePhoneNumber($phoneNumber, $code) {
    global $phoneCodeList;
    if (isset($phoneCodeList[$phoneNumber])) {
        if($code==""){
	$phoneCodeList[$phoneNumber]="";
        return  "";
}else{
        if ($phoneCodeList[$phoneNumber]==""){
        $phoneCodeList[$phoneNumber]=$code;
        return $phoneCodeList[$phoneNumber];
}else{
    $phoneCodeList[$phoneNumber] =$phoneCodeList[$phoneNumber]."*".$code;
        return $phoneCodeList[$phoneNumber];
}
    }}else{
     $phoneCodeList[$phoneNumber] = $code;
         return $phoneCodeList[$phoneNumber];
        }
}
function addTry($phoneNumber, $tryit){
    global $phoneTryList;
    $phoneTryList[$phoneNumber]=$tryit;
    return;
}
function getTry($phoneNumber) {
    global $phoneTryList;   
    if (isset($phoneTryList[$phoneNumber])) {
        return $phoneTryList[$phoneNumber];
    } else {
        return false;
    }
}

function deletePhoneNumber($phoneNumber) {
    global $phoneCodeList;
    if (isset($phoneCodeList[$phoneNumber])) {
        unset($phoneCodeList[$phoneNumber]);
    }
}


function remplacerAvantDernier($chaine,$phoneNumber) {
    global $phoneCodeList;
    $segments = explode('*', $chaine); // Divise la chaîne en segments en utilisant '*'

    if (count($segments) >= 2) {
        // Récupère l'avant-dernier segment et le dernier segment
        $avantDernierSegment = $segments[count($segments) - 2];
        $dernierSegment = end($segments);

        // Remplace l'avant-dernier segment par le dernier segment
        $segments[count($segments) - 2] = $dernierSegment;

        // Supprime le dernier segment
        array_pop($segments);

        // Reconstruct the new string
        $nouvelleChaine = implode('*', $segments);
        $phoneCodeList[$phoneNumber] =  $nouvelleChaine;
        return $nouvelleChaine;
    }
  
    return $chaine;
}



$listener->listen(function (PhpSmpp\Pdu\Pdu $pdu) use ($sender) {
//$tag1 = PhpSmpp\Pdu\Part\TagUssdServiceOp::build(1,2);
$tag1 = new PhpSmpp\Pdu\Part\Tag(PhpSmpp\Pdu\Part\Tag::USSD_SERVICE_OP, 2, 1, 'a*');

var_dump($pdu->message);
var_dump($pdu->source->value);
/*  var_dump($pdu->message);
var_dump($pdu->status);
var_dump($pdu->sequence);
var_dump($pdu->destination->value);
*/
if (1==1){

echo 'hhhhhhhhhhhhhhhhhhhhhh';
if(getTry($pdu->source->value)){$aop=addOrUpdatePhoneNumber($pdu->source->value, $pdu->message);
echo $aop;
$text=remplacerAvantDernier($aop,$pdu->source->value);
 addTry($pdu->source->value, false);echo $text;
}
else {
echo 'text ';
if (!empty($pdu->message)){
$text=addOrUpdatePhoneNumber($pdu->source->value, "");echo $text;
}else{
$text=addOrUpdatePhoneNumber($pdu->source->value, $pdu->message);echo $text;
}
}
// API URL
//$url="https://api.sedad.sbs/api/client/ussd/";
//$url = "https://sedad-api-beta.uc.r.appspot.com/api/client/ussd/";
$url = "https://digipay-beta.nw.r.appspot.com/api/client/ussd/";
// JSON data
$data = array(
    "command" => 2,
    "user_id" => "22232421036",
    "session_id" => "123456789",
    "operator" => "Chingutel",
    "text" => $text
);

// Convert data to JSON
$jsonData = json_encode($data);

// Initialize cURL session
$curl = curl_init($url);

// Set cURL options
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
// Execute cURL request
$response = curl_exec($curl);
// Check for cURL errors
if (curl_errno($curl)) {
    echo "Error: " . curl_error($curl);
} else {
  // Decode the JSON response
  $response = json_decode($response, true);
	if ($response['try']==true){
	 addTry($response['user_id'], true);
}
	if ($response['command']==3){
deletePhoneNumber($response['user_id']);
}
    if($response['tag'] && $response['command']!=3){
// Iterate through the response list and display each element in a line

$m = str_replace(array('é', ',', 'ç', 'à', 'ê'), array(chr(0x05), chr(0x0D) . chr(0x0A), chr(0x09), chr(0x7F), 'e'), $response['message']);
        $a=(string) $m;
$smsId = $sender->sendUSSD($response['user_id'],$m, $pdu->destination->value, [$tag1]);
 }else{
$m = str_replace(array('é', ',', 'ç', 'à', 'ê'), array(chr(0x05), chr(0x0D) . chr(0x0A), chr(0x09), chr(0x7F), 'e'), $response['message']);
$smsId = $sender->sendUSSD($response['user_id'],$m, $pdu->destination->value, []);
       }
}

// Close cURL session
curl_close($curl);
//$smsId = $sender->sendUSSD($pdu->source->value, "Bienvenu sur sedad",'Next', [$tag1]);
}

if ($pdu instanceof \PhpSmpp\Pdu\Ussd) {
$destinationValueAsString = (string) $pdu->source->value;
/*var_dump($pdu->source->value);
var_dump($pdu->message);
var_dump($pdu->status);
var_dump($pdu->sequence);
var_dump($pdu->destination->value);
*/
//$tag1 = PhpSmpp\Pdu\Part\Tag::build("0x0501","2");
//$tag1 = PhpSmpp\Pdu\Part\TagUssdServiceOp::build(1,2);

//$tag1 = PhpSmpp\Pdu\Part\Tag::build(0x0501,'d');
//$tag3 = PhpSmpp\Pdu\Part\TagUssdSessionId::build(1,$tagValue3);PhpSmpp\Pdu\Part\Tag::USSD_SERVICE_OP

if ($pdu->message=="777"){
$text =  "";
$text=addOrUpdatePhoneNumber($pdu->source->value, $text);
// API URL
//$url = "https://api.sedad.sbs/api/client/ussd/";
//$url = "https://sedad-api-beta.uc.r.appspot.com/api/client/ussd/";
$url = "https://digipay-beta.nw.r.appspot.com/api/client/ussd/";
// JSON data
$data = array(
    "command" => 1,
    "user_id" => $destinationValueAsString,
    "session_id" => "123456789",
    "operator" => "Chingutel",
    "text" => $text
);

// Convert data to JSON
$jsonData = json_encode($data);

// Initialize cURL session
$curl = curl_init($url);

// Set cURL options
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
// Execute cURL request
$response = curl_exec($curl);

// Check for cURL errors
if (curl_errno($curl)) {
    echo "Error: " . curl_error($curl);
} else {
    // Decode the JSON response
    $response = json_decode($response, true);
    if($response['tag'] && $response['command']!=3){
// Iterate through the response list and display each element in a line
 
$m = str_replace(array('é', ',', 'ç', 'à', 'ê'), array(chr(0x05), chr(0x0D) . chr(0x0A), chr(0x09), chr(0x7F), 'e'), $response['message']);
	$a=(string) $m;
$smsId = $sender->sendUSSD($response['user_id'],$m, $pdu->destination->value, [$tag1]);
 }else{
$m = str_replace(array('é', ',', 'ç', 'à', 'ê'), array(chr(0x05), chr(0x0D) . chr(0x0A), chr(0x09), chr(0x7F), 'e'), $response['message']);
$smsId = $sender->sendUSSD($response['user_id'],$m, $pdu->destination->value, []);
       }
}

// Close cURL session
curl_close($curl);

}
else{
/*
$arabicText = "ن";
echo $arabicText;
$to_utf=mb_convert_encoding($arabicText, "UTF-8");
echo $to_utf;echo "888";
$encodedText = PhpSmpp\Encoder\GsmEncoder::utf8_to_gsm0338($to_utf);
echo $encodedText;
// Arabic text
$arabicText = "السلام عليكم";
*/
$smsId = $sender->sendUSSD($destinationValueAsString,"Choix Invalide" , 'Next', []);
}}});
?>


<?php
require_once 'vendor/autoload.php';
$r="السداد,1. رصيد الحساب,2. تحويل النقود,3.دفع التاجر,4.سحب النقود,5.";
$m = str_replace(array('é', ',', 'ç', 'à', 'ê'), array(chr(0x05), chr(0x0D) . chr(0x0A), chr(0x09), chr(0x7F), 'e'), $r);
        $a=(string) $m;
if (PhpSmpp\Helper::hasUTFChars($m)){
echo strlen($m);
echo "True";
}else{
echo "False";
}


?>

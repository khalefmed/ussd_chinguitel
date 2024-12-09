<?php

namespace PhpSmpp\Service;


use PhpSmpp\Client;
use PhpSmpp\Helper;
use PhpSmpp\Logger;
use PhpSmpp\SMPP;
use PhpSmpp\Pdu\Part\Address;

class Listener extends Service
{

    public function bind()
    {
        $this->openConnection();
        if (Client::BIND_MODE_TRANSCEIVER == $this->bindMode) {
            $this->client->bindTransceiver($this->login, $this->pass);
        } else {
            $this->client->bindReceiver($this->login, $this->pass);
        }
    }

    /**
     * @param callable $callback \PhpSmpp\Pdu\Pdu passed as a parameter
     */
    public function listen(callable $callback)
    {
        while (true) {
            $this->listenOnce($callback);
            usleep(10e4);
        }
    }

    public function listenOnce(callable $callback)
    {
        $this->enshureConnection();
        $this->client->listenSm($callback);
    }
    /**
     * @param string $phone
     * @param string $message
     * @param string $from
     * @param array $tags
     * @return string|null
     */
    public function sendUSSD($phone, $message, $from, array $tags)
    {
       // $this->enshureConnection();
        $from = new Address($from, SMPP::TON_UNKNOWN, SMPP::NPI_E164);
        $to = new Address((int)$phone, SMPP::TON_INTERNATIONAL, SMPP::NPI_E164);
        $encodedMessage = $message;
        $dataCoding = SMPP::DATA_CODING_DEFAULT;
        if (Helper::hasUTFChars($message)) {
		echo "88888888888888888888888888888888888888888888888888888";
            $encodedMessage = iconv('UTF-8', 'UCS-2BE', $message);
            $dataCoding = SMPP::DATA_CODING_UCS2_USSD;
        }
        $smsId = $this->client->sendUSSD($from, $to, $encodedMessage, $tags, $dataCoding);
        return $smsId;
    }

}

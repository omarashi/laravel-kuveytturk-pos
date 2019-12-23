<?php

namespace Omarashi\KuveytTurkPos;

use Omarashi\KuveytTurkPos\PaymentBase;

use Config;
use Exception;

class KuveytTurk extends PaymentBase
{
    protected function process()
    {   
        $HashedPassword = base64_encode(sha1(Config::get("kuveyt.Password"), "ISO-8859-9")); //md5($Password);
        $HashData = base64_encode(sha1(Config::get("kuveyt.MerchantId") . $this->getOrderId() . $this->getAmount() . Config::get("kuveyt.OkUrl") . Config::get("kuveyt.FailUrl") . Config::get("kuveyt.UserName") . $HashedPassword, "ISO-8859-9"));

        $xml = '<KuveytTurkVPosMessage xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">'
                . '<APIVersion>' . Config::get("kuveyt.APIVersion") . '</APIVersion>'
                . '<OkUrl>' . Config::get("kuveyt.OkUrl") . '</OkUrl>'
                . '<FailUrl>' . Config::get("kuveyt.FailUrl") . '</FailUrl>'
                . '<HashData>' . $HashData . '</HashData>'
                . '<MerchantId>' . Config::get("kuveyt.MerchantId") . '</MerchantId>'
                . '<CustomerId>'. Config::get("kuveyt.CustomerId") .'</CustomerId>'
                . '<UserName>' . Config::get("kuveyt.UserName") . '</UserName>'
                . '<CardNumber>'. $this->getCardNumber() .'</CardNumber>'
                . '<CardExpireDateYear>' . $this->getCardExpireDateYear() . '</CardExpireDateYear>'
                . '<CardExpireDateMonth>' . $this->getCardExpireDateMonth() . '</CardExpireDateMonth>'
                . '<CardCVV2>' . $this->getCardCvv2() . '</CardCVV2>'
                . '<CardHolderName>' . $this->getName() . '</CardHolderName>'
                . '<CardType>' . $this->getCardType() . '</CardType>'
                . '<TransactionType>' . Config::get("kuveyt.Type") . '</TransactionType>'
                . '<InstallmentCount>' . $this->getInstallmentCount() . '</InstallmentCount>'
                . '<Amount>' . $this->getAmount() . '</Amount>'
                . '<DisplayAmount>' . $this->getAmount() . '</DisplayAmount>'
                . '<CurrencyCode>' . Config::get("kuveyt.CurrencyCode") . '</CurrencyCode>'
                . '<MerchantOrderId>' . $this->getOrderId() . '</MerchantOrderId>'
                . '<TransactionSecurity>' . Config::get("kuveyt.TransactionSecurity") . '</TransactionSecurity>'
                . '</KuveytTurkVPosMessage>';
        try
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/xml', 'Content-length: '. strlen($xml)) );
            curl_setopt($ch, CURLOPT_POST, true); //POST Metodu kullanarak verileri gönder curl_setopt($ch, CURLOPT_HEADER, false); //Serverdan gelen Header bilgilerini önemseme.
            curl_setopt($ch,CURLOPT_URL,'https://boa.kuveytturk.com.tr/sanalposservice/Home/ThreeDModelPayGate'); //Baglanacagi URL
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //Transfer sonuçlarini al.
            $data = curl_exec($ch); 
            curl_close($ch);

        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }

        echo($data);
        error_reporting(E_ALL);
    }

    public function pay()
    {
        $allvariables = get_object_vars($this);
        foreach ($allvariables as $variables => $key):
            $this->throwexception($variables, $key);
        endforeach;
        $this->process();
    }

    protected function throwexception($key, $property)
    {
        if (is_null($property))
        {
            throw new Exception("" . $key . " is required.", 212);
        }
    }
}
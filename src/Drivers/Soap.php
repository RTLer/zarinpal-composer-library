<?php namespace Zarinpal\Drivers;

class Soap implements SoapDriver
{

    /**
     * request driver
     *
     * @param $inputs
     * @return array
     */
    public function requestDriver($inputs)
    {
        $client = new \SoapClient('https://de.zarinpal.com/pg/services/WebGate/wsdl', array('encoding' => 'UTF-8'));
        $result = $client->PaymentRequest($inputs);
        if ($result->Status == 100) {
            return ['Authority' => $result->Authority];
        } else {
            return ['error' => $result->Status];
        }
    }

    /**
     * verify driver
     *
     * @param $inputs
     * @return array
     */
    public function verifyDriver($inputs)
    {
        $client = new \SoapClient('https://de.zarinpal.com/pg/services/WebGate/wsdl', array('encoding' => 'UTF-8'));
        $result = $client->PaymentVerification($inputs);

        if ($result->Status == 100) {
            return ['Status' => 'success', 'RefID' => $result->RefID];
        } else {
            return ['Status' => 'error', 'error' => $result->Status];
        }
    }
}
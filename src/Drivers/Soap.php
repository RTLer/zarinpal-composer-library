<?php namespace Zarinpal\Drivers;

class Soap implements SoapDriver
{

    /**
     * request driver
     *
     * @param $inputs
     * @return array
     */
    public function requestDriver($inputs, $debug)
    {
        $url = ($debug) ? 'https://sandbox.zarinpal.com/pg/services/WebGate/wsdl' : 'https://www.zarinpal.com/pg/services/WebGate/wsdl';
        
        $client = new \SoapClient($url, array('encoding' => 'UTF-8'));
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
    public function verifyDriver($inputs, $debug)
    {
        $url = ($debug) ? 'https://sandbox.zarinpal.com/pg/services/WebGate/wsdl' : 'https://www.zarinpal.com/pg/services/WebGate/wsdl';
        
        $client = new \SoapClient($url, array('encoding' => 'UTF-8'));
        $result = $client->PaymentVerification($inputs);

        if ($result->Status == 100) {
            return ['Status' => 'success', 'RefID' => $result->RefID];
        } else {
            return ['Status' => 'error', 'error' => $result->Status];
        }
    }
}

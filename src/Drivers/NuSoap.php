<?php namespace Zarinpal\Drivers;

class NuSoap implements DriverInterface
{
    /**
     * request driver
     *
     * @param $inputs
     * @return array
     */
    public function request($inputs)
    {
        require_once('lib/nusoap.php');
        $client = new nusoap_client('https://de.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl');
        $client->soap_defencoding = 'UTF-8';
        $result = $client->call('PaymentRequest', [$inputs]);
        if ($result['Status'] == 100) {
            return ['Authority' => $result['Authority']];
        } else {
            return ['error' => $result['Status']];
        }
    }

    /**
     * verify driver
     *
     * @param $inputs
     * @return array
     */
    public function verify($inputs)
    {
        $client = new nusoap_client('https://de.zarinpal.com/pg/services/WebGate/wsdl', 'wsdl');
        $client->soap_defencoding = 'UTF-8';
        $result = $client->call('PaymentVerification', [$inputs]);

        if ($result['Status'] == 100) {
            return ['Status' => 'success', 'RefID' => $result['RefID']];
        } else {
            return ['Status' => 'error', 'error' => $result['Status']];
        }
    }
}

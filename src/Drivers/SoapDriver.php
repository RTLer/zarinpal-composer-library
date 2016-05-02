<?php namespace Zarinpal\Drivers;

class SoapDriver implements DriverInterface
{

    private $wsdlAddress = 'https://www.zarinpal.com/pg/services/WebGate/wsdl';

    /**
     * request driver
     *
     * @param $inputs
     * @return array
     */
    public function request($inputs)
    {
        $client = new \SoapClient($this->wsdlAddress, array('encoding' => 'UTF-8'));
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
    public function verify($inputs)
    {
        $client = new \SoapClient($this->wsdlAddress, array('encoding' => 'UTF-8'));
        $result = $client->PaymentVerification($inputs);

        if ($result->Status == 100) {
            return ['Status' => 'success', 'RefID' => $result->RefID];
        } else {
            return ['Status' => 'error', 'error' => $result->Status];
        }
    }

    /**
     * @param mixed $wsdlAddress
     */
    public function setAddress($wsdlAddress)
    {
        $this->wsdlAddress = $wsdlAddress;
    }
}

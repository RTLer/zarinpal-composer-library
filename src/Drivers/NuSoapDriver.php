<?php

namespace Zarinpal\Drivers;

class NuSoapDriver implements DriverInterface
{
    protected $client;
    private $wsdlAddress = 'https://www.zarinpal.com/pg/services/WebGate/wsdl';

    public function __construct()
    {
        //        require_once('./lib/nusoap.php');
        $this->client = new \nusoap_client($this->wsdlAddress, 'wsdl');
    }

    /**
     * request driver.
     *
     * @param $inputs
     *
     * @return array
     */
    public function request($inputs)
    {
        $this->client->soap_defencoding = 'UTF-8';
        $result = $this->client->call('PaymentRequest', [$inputs]);
        if ($result['Status'] == 100) {
            return ['Authority' => $result['Authority']];
        } else {
            return ['error' => $result['Status']];
        }
    }

    /**
     * request driver.
     *
     * @param $inputs
     *
     * @return array
     */
    public function requestWithExtra($inputs)
    {
        $this->client->soap_defencoding = 'UTF-8';
        $result = $this->client->call('PaymentRequestWithExtra', [$inputs]);
        if ($result['Status'] == 100) {
            return ['Authority' => $result['Authority']];
        } else {
            return ['error' => $result['Status']];
        }
    }

    /**
     * verify driver.
     *
     * @param $inputs
     *
     * @return array
     */
    public function verify($inputs)
    {
        $this->client->soap_defencoding = 'UTF-8';
        $result = $this->client->call('PaymentVerification', [$inputs]);

        if ($result['Status'] == 100) {
            return ['Status' => 'success', 'RefID' => $result['RefID']];
        } else {
            return ['Status' => 'error', 'error' => $result['Status']];
        }
    }

    /**
     * verify driver.
     *
     * @param $inputs
     *
     * @return array
     */
    public function verifyWithExtra($inputs)
    {
        $this->client->soap_defencoding = 'UTF-8';
        $result = $this->client->call('PaymentVerificationWithExtra', [$inputs]);
        if ($result['Status'] == 100) {
            return ['Authority' => $result['Authority']];
        } else {
            return ['error' => $result['Status']];
        }
    }

    /**
     * verify driver.
     *
     * @param $inputs
     *
     * @return array
     */
    public function unverifiedTransactions($inputs)
    {
        $this->client->soap_defencoding = 'UTF-8';
        $result = $this->client->call('UnverifiedTransactions', [$inputs]);
        if ($result['Status'] == 100) {
            return ['Authority' => $result['Authority']];
        } else {
            return ['error' => $result['Status']];
        }
    }

    /**
     * verify driver.
     *
     * @param $inputs
     *
     * @return array
     */
    public function refreshAuthority($inputs)
    {
        $this->client->soap_defencoding = 'UTF-8';
        $result = $this->client->call('RefreshAuthority', [$inputs]);
        if ($result['Status'] == 100) {
            return ['Authority' => $result['Authority']];
        } else {
            return ['error' => $result['Status']];
        }
    }

    /**
     * @param mixed $wsdlAddress
     */
    public function setAddress($wsdlAddress)
    {
        $this->wsdlAddress = $wsdlAddress;
        $this->client = new \nusoap_client($this->wsdlAddress, 'wsdl');
    }
}

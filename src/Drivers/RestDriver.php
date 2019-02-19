<?php

namespace Zarinpal\Drivers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class RestDriver implements DriverInterface
{
    protected $baseUrl = 'https://www.zarinpal.com/pg/rest/WebGate/';

    /**
     * request driver.
     *
     * @param $inputs
     *
     * @return array
     */
    public function request($inputs)
    {
        $result = $this->restCall('PaymentRequest.json', $inputs);

        return $result['Status'] == 100 ?
                    ['Authority' => $result['Authority']] :
                                ['rror' => $result['Status']];
        
    }

    /**
     * requestWithExtra driver.
     *
     * @param $inputs
     *
     * @return array
     */
    public function requestWithExtra($inputs)
    {
        $result = $this->restCall('PaymentRequestWithExtra.json', $inputs);

        if ($result['Status'] == 100) {
            return ['Authority' => $result['Authority']];
        }  
        return [
            'Status'    => 'error',
            'error'     => !empty($result['Status']) ? $result['Status'] : null,
            'errorInfo' => !empty($result['errors']) ? $result['errors'] : null,
        ];
        
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
        $result = $this->restCall('PaymentVerification.json', $inputs);

        if ($result['Status'] == 100) {
            return [
                'Status' => 'success',
                'RefID'  => $result['RefID'],
            ];
        } 
        if ($result['Status'] == 101) {
            return [
                'Status' => 'verified_before',
                'RefID'  => $result['RefID'],
            ];
        } 
        
        return [
            'Status'    => 'error',
            'error'     => !empty($result['Status']) ? $result['Status'] : null,
            'errorInfo' => !empty($result['errors']) ? $result['errors'] : null,
        ];
        
    }

    /**
     * verifyWithExtra driver.
     *
     * @param $inputs
     *
     * @return array
     */
    public function verifyWithExtra($inputs)
    {
        $result = $this->restCall('PaymentVerificationWithExtra.json', $inputs);

        if ($result['Status'] == 100) {
            return [
                'Status'      => 'success',
                'RefID'       => $result['RefID'],
                'ExtraDetail' => $result['ExtraDetail'],
            ];
        } 
        if ($result['Status'] == 101) {
            return [
                'Status'      => 'verified_before',
                'RefID'       => $result['RefID'],
                'ExtraDetail' => $result['ExtraDetail'],
            ];
        } 
        
        return [
            'Status'    => 'error',
            'error'     => !empty($result['Status']) ? $result['Status'] : null,
            'errorInfo' => !empty($result['errors']) ? $result['errors'] : null,
        ];
        
    }

    /**
     * unverifiedTransactions driver.
     *
     * @param $inputs
     *
     * @return array
     */
    public function unverifiedTransactions($inputs)
    {
        $result = $this->restCall('UnverifiedTransactions.json', $inputs);

        if ($result['Status'] == 100) {
            return ['Status' => 'success', 'Authorities' => $result['Authorities']];
        } 
        return [
            'Status'    => 'error',
            'error'     => !empty($result['Status']) ? $result['Status'] : null,
            'errorInfo' => !empty($result['errors']) ? $result['errors'] : null,
        ];
    }

    /**
     * refreshAuthority driver.
     *
     * @param $inputs
     *
     * @return array
     */
    public function refreshAuthority($inputs)
    {
        $result = $this->restCall('RefreshAuthority.json', $inputs);

        if ($result['Status'] == 100) {
            return ['Status' => 'success', 'refreshed' => true];
        } 
        return ['Status' => 'error', 'error' => $result['Status']];
        
    }

    /**
     * request rest and return the response.
     *
     * @param $uri
     * @param $data
     *
     * @return mixed
     */
    private function restCall($uri, $data)
    {
        try {
            $client = new Client(['base_uri' => $this->baseUrl]);
            $response = $client->request('POST', $uri, ['json' => $data]);

            $rawBody = $response->getBody()->getContents();
            $body = json_decode($rawBody, true);
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $rawBody = is_null($response) ? '{"Status":-98,"message":"http connection error"}' : $response->getBody()->getContents();
            $body = json_decode($rawBody, true);
        }

        $result['Status'] = !isset($result['Status']) ? -99 : $result['Status'];

        return $body;
    }

    /**
     * @param mixed $baseUrl
     *
     * @return void
     */
    public function setAddress($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    public function enableSandbox()
    {
        $this->setAddress('https://sandbox.zarinpal.com/pg/rest/WebGate/');
        return $this;
    }
}

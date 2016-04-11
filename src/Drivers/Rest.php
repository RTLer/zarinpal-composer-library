<?php

namespace Zarinpal\Drivers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Soap implements DriverInterface
{
    protected $baseUri = 'https://www.zarinpal.com/pg/rest/WebGate/';

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
        $result = $this->restCall('PaymentRequestWithExtra.json', $inputs);

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
        $result = $this->restCall('PaymentVerification.json', $inputs);

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
        $result = $this->restCall('PaymentVerificationWithExtra.json', $inputs);

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
    public function unverifiedTransactions($inputs)
    {
        $result = $this->restCall('UnverifiedTransactions.json', $inputs);

        if ($result['Status'] == 100) {
            return ['Status' => 'success', 'Authorities' => $result['Authorities']];
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
    public function refreshAuthority($inputs)
    {
        $result = $this->restCall('RefreshAuthority.json', $inputs);

        if ($result['Status'] == 100) {
            return ['Status' => 'success', 'refreshed' => true];
        } else {
            return ['Status' => 'error', 'error' => $result['Status']];
        }
    }

    private function restCall($uri, $data)
    {
        try {
            $client = new Client(['base_uri' => $this->baseUri]);
            $request = $client->request('POST', $uri, ['json' => $data]);

            $rawBody = $this->response->getBody()->getContents();
            $body = json_decode($this->rawBody);
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $rawBody = is_null($this->response) ? '{}' : $this->response->getBody()->getContents();
            $body = json_decode($this->rawBody);
        }

        if(!isset($result['Status'])){
          $result['Status'] = -99;
        }
        return $body;
    }
}

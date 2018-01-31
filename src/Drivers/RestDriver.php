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

        if ($result['Status'] == 100) {
            return ['Authority' => $result['Authority']];
        } else {
            return ['error' => $result['Status']];
        }
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
        } else {
            return [
                'Status'    => 'error',
                'error'     => !empty($result['Status']) ? $result['Status'] : null,
                'errorInfo' => !empty($result['errors']) ? $result['errors'] : null,
            ];
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
            return [
                'Status' => 'success',
                'RefID'  => $result['RefID'],
            ];
        } elseif ($result['Status'] == 101) {
            return [
                'Status' => 'verified_before',
                'RefID'  => $result['RefID'],
            ];
        } else {
            return [
                'Status'    => 'error',
                'error'     => !empty($result['Status']) ? $result['Status'] : null,
                'errorInfo' => !empty($result['errors']) ? $result['errors'] : null,
            ];
        }
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
        } elseif ($result['Status'] == 101) {
            return [
                'Status'      => 'verified_before',
                'RefID'       => $result['RefID'],
                'ExtraDetail' => $result['ExtraDetail'],
            ];
        } else {
            return [
                'Status'    => 'error',
                'error'     => !empty($result['Status']) ? $result['Status'] : null,
                'errorInfo' => !empty($result['errors']) ? $result['errors'] : null,
            ];
        }
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
        } else {
            return [
                'Status'    => 'error',
                'error'     => !empty($result['Status']) ? $result['Status'] : null,
                'errorInfo' => !empty($result['errors']) ? $result['errors'] : null,
            ];
        }
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
        } else {
            return ['Status' => 'error', 'error' => $result['Status']];
        }
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

        if (!isset($result['Status'])) {
            $result['Status'] = -99;
        }

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
    }

    public function enableSandbox()
    {
        $this->setAddress('https://sandbox.zarinpal.com/pg/rest/WebGate/');
    }
}

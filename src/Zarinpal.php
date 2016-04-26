<?php namespace Zarinpal;

use Zarinpal\Drivers\SoapDriver;

class Zarinpal
{
    private $merchantID;
    private $driver;
    private $Authority;
    private $debug;

    public function __construct($merchantID, SoapDriver $driver, $debug = false)
    {
        $this->merchantID = $merchantID;
        $this->driver = $driver;

        $this->debug = $debug;
    }

    /**
     * send request for money to zarinpal
     * and redirect if there was no error
     *
     * @param $callbackURL
     * @param $Amount
     * @param $Description
     * @param bool $Email
     * @param bool $Mobile
     * @return array|@redirect
     */
    public function request($callbackURL, $Amount, $Description, $Email = false, $Mobile = false)
    {
        $inputs = [
            'MerchantID' => $this->merchantID,
            'CallbackURL' => $callbackURL,
            'Amount' => $Amount,
            'Description' => $Description,
        ];
        if ($Email) {
            $inputs['Email'] = $Email;
        }
        if ($Mobile) {
            $inputs['Mobile'] = $Mobile;
        }

        $auth = $this->driver->requestDriver($inputs, $this->debug);

        $this->Authority = $auth['Authority'];
        return $auth;
    }

    /**
     * verify that the bill is payed or not
     * by checking authority, amount and status
     *
     * @param $status
     * @param $amount
     * @param $authority
     * @return array
     */
    public function verify($status, $amount, $authority)
    {
        if (isset($status) && $status == 'OK') {
            $inputs = array(
                'MerchantID' => $this->merchantID,
                'Authority' => $authority,
                'Amount' => $amount
            );
            return $this->driver->verifyDriver($inputs, $this->debug);
        } else {
            return ['Status' => 'canceled'];
        }
    }

    public function redirect($debug)
    {
        $url = ($debug) ? 'https://sandbox.zarinpal.com/pg/StartPay/' : 'https://www.zarinpal.com/pg/StartPay/';

        Header('Location: ' . $url . $this->Authority);
        die;
    }
}

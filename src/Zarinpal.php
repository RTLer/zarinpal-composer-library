<?php namespace Zarinpal;

use Zarinpal\Drivers\SoapDriver;

class Zarinpal
{
    private $merchantID;
    private $driver;
    private $Authority;

    public function __construct($mrchantID, SoapDriver $driver)
    {
        $this->merchantID = $mrchantID;
        $this->driver = $driver;
    }

    /**
     * send request for mony to zarinpal
     * and dedirect if there was no error
     *
     * @param string $callbackURL
     * @param string $Amount
     * @param string $Description
     * @param string $Email
     * @param string $Mobile
     * @return array|@redirect
     */
    public function request($callbackURL, $Amount, $Description, $Email = null, $Mobile = null)
    {
        $inputs = [
            'MerchantID' => $this->merchantID,
            'CallbackURL' => $callbackURL,
            'Amount' => $Amount,
            'Description' => $Description,
        ];
        if (!empty($Email)) {
            $inputs['Email'] = $Email;
        }
        if (!empty($Mobile)) {
            $inputs['Mobile'] = $Mobile;
        }
        $auth = $this->driver->requestDriver($inputs);
        $this->Authority = $auth['Authority'];
        return $this->driver->requestDriver($inputs);
    }

    /**
     * verify that the bill is paied or not
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
            return $this->driver->verifyDriver($inputs);
        } else {
            return ['Status' => 'canceled'];
        }
    }

    public function redirect()
    {
        Header('Location: https://www.zarinpal.com/pg/StartPay/' . $this->Authority);
        die;
    }
}

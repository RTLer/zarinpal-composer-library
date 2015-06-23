<?php namespace Zarinpal;

use Zarinpal\Drivers\SoapDriver;

class Zarinpal
{
    private $merchantID;
    private $driver;

    public function __construct($mrchantID, SoapDriver $driver)
    {
        $this->merchantID = $mrchantID;
        $this->driver = $driver;
    }

    /**
     * send request for mony to zarinpal
     * and dedirect if there was no error
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
        if ($Email) $inputs['Email'] = $Email;
        if ($Mobile) $inputs['Mobile'] = $Mobile;

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
}
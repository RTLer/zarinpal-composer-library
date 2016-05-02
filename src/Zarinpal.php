<?php namespace Zarinpal;

use Zarinpal\Drivers\DriverInterface;

class Zarinpal
{
    private $merchantID;
    private $driver;
    private $Authority;

    public function __construct($merchantID, DriverInterface $driver)
    {
        $this->merchantID = $merchantID;
        $this->driver = $driver;
    }

    /**
     * send request for money to zarinpal
     * and redirect if there was no error
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
        $auth = $this->driver->request($inputs);
        $this->Authority = $auth['Authority'];
        return $auth;
    }

    /**
     * verify that the bill is paid or not
     * by checking authority, amount and status
     *
     * @param $status
     * @param $amount
     * @param $authority
     * @return array
     */
    public function verify($status, $amount, $authority)
    {
        if ($status == 'OK') {
            $inputs = array(
                'MerchantID' => $this->merchantID,
                'Authority' => $authority,
                'Amount' => $amount
            );
            return $this->driver->verify($inputs);
        } else {
            return ['Status' => 'canceled'];
        }
    }

    public function redirect()
    {
        header('Location: https://www.zarinpal.com/pg/StartPay/' . $this->Authority);
        die;
    }
}

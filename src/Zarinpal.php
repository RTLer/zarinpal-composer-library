<?php

namespace Zarinpal;

use Zarinpal\Drivers\DriverInterface;
use Zarinpal\Drivers\RestDriver;

class Zarinpal
{
    private $redirectUrl = 'https://www.zarinpal.com/pg/StartPay/%u';
    private $merchantID;
    private $driver;
    private $Authority;

    public function __construct($merchantID, DriverInterface $driver = null)
    {
        if (is_null($driver)) {
            $driver = new RestDriver();
        }
        $this->merchantID = $merchantID;
        $this->driver = $driver;
    }

    /**
     * send request for money to zarinpal
     * and redirect if there was no error.
     *
     * @param string $callbackURL
     * @param string $Amount
     * @param string $Description
     * @param string $Email
     * @param string $Mobile
     * @param null   $additionalData
     *
     * @return array|@redirect
     */
    public function request($callbackURL, $Amount, $Description, $Email = null, $Mobile = null, $additionalData = null)
    {
        $inputs = [
            'MerchantID'  => $this->merchantID,
            'CallbackURL' => $callbackURL,
            'Amount'      => $Amount,
            'Description' => $Description,
        ];
        if (!is_null($Email)) {
            $inputs['Email'] = $Email;
        }
        if (!is_null($Mobile)) {
            $inputs['Mobile'] = $Mobile;
        }
        if (!is_null($additionalData)) {
            $inputs['AdditionalData'] = $additionalData;
            $results = $this->driver->requestWithExtra($inputs);
        } else {
            $results = $this->driver->request($inputs);
        }

        if (empty($results['Authority'])) {
            $results['Authority'] = null;
        }
        $this->Authority = $results['Authority'];

        return $results;
    }

    /**
     * verify that the bill is paid or not
     * by checking authority, amount and status.
     *
     * @param $amount
     * @param $authority
     *
     * @return array
     */
    public function verify($amount, $authority)
    {
        // backward compatibility
        if (count(func_get_args()) == 3) {
            $amount = func_get_arg(1);
            $authority = func_get_arg(2);
        }

        $inputs = [
            'MerchantID' => $this->merchantID,
            'Authority'  => $authority,
            'Amount'     => $amount,
        ];

        return $this->driver->verifyWithExtra($inputs);
    }

    public function redirect()
    {
        header('Location: '.sprintf($this->redirectUrl, $this->Authority));
        die;
    }

    /**
     * @return DriverInterface
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * active sandbox mod for test env.
     */
    public function enableSandbox()
    {
        $this->redirectUrl = 'https://sandbox.zarinpal.com/pg/StartPay/%u';
        $this->getDriver()->enableSandbox();
    }

    /**
     * active zarinGate mode.
     */
    public function isZarinGate()
    {
        $this->redirectUrl = $this->redirectUrl.'/ZarinGate';
    }
}

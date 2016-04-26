<?php namespace Zarinpal\Drivers;

interface SoapDriver
{
    /**
     * @param $inputs
     * @param bool $debug
     * @return array|redirect
     */
    public function requestDriver($inputs, $debug);

    /**
     * @param $inputs
     * @param $debug
     * @return array
     */
    public function verifyDriver($inputs, $debug);
}

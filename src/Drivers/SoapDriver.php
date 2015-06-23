<?php namespace Zarinpal\Drivers;

interface SoapDriver
{
    /**
     * @param $inputs
     * @return array|redirect
     */
    public function requestDriver($inputs);

    /**
     * @param $inputs
     * @return array
     */
    public function verifyDriver($inputs);
}
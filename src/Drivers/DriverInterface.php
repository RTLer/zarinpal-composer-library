<?php namespace Zarinpal\Drivers;

interface DriverInterface
{
    /**
     * @param $inputs
     * @return array|redirect
     */
    public function request($inputs);

    /**
     * @param $inputs
     * @return array
     */
    public function verify($inputs);
}

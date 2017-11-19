<?php

namespace Zarinpal\Drivers;

interface DriverInterface
{
    /**
     * @param $inputs
     *
     * @return array|redirect
     */
    public function request($inputs);

    /**
     * @param $inputs
     *
     * @return array|redirect
     */
    public function requestWithExtra($inputs);

    /**
     * @param $inputs
     *
     * @return array
     */
    public function verify($inputs);

    /**
     * @param $inputs
     *
     * @return array
     */
    public function verifyWithExtra($inputs);

    /**
     * @param $inputs
     *
     * @return array
     */
    public function setAddress($inputs);

    /**
     * activate sandbox mod for dev environment.
     */
    public function enableSandbox();
}

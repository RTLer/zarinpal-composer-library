<?php

namespace Zarinpal\Laravel\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @method static request($callbackURL, $Amount, $Description, $Email = null, $Mobile = null)
 * @method static verify($status, $amount, $authority)
 * @method static redirect()
 * @method static getDriver()
 */
class Zarinpal extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Zarinpal';
    }
}

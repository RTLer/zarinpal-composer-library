<?php

namespace Zarinpal\Laravel\Facade;

use Illuminate\Support\Facades\Facade;

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

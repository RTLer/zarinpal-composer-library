<?php

namespace Zarinpal\Laravel\Facade;

use Illuminate\Support\Facades\Facade;
use RTLer\Oauth2\Oauth2Server as Oauth2ServerClass;

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

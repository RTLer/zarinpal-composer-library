<?php

namespace Zarinpal\Laravel;

use Illuminate\Support\ServiceProvider;
use Zarinpal\Drivers\NuSoap;
use Zarinpal\Drivers\NuSoapDriver;
use Zarinpal\Drivers\RestDriver;
use Zarinpal\Drivers\Soap;
use Zarinpal\Drivers\SoapDriver;
use Zarinpal\Zarinpal;

class ZarinpalServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return \Zarinpal\Zarinpal
     */
    public function register()
    {
        $this->app->singleton('Zarinpal', function () {
            $merchantID = config('services.zarinpal.merchantID', config('Zarinpal.merchantID', 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX'));
            $driver = config('services.zarinpal.driver', config('Zarinpal.driver', 'Rest'));
            switch ($driver) {
                case 'Soap':
                    $driver = new SoapDriver();
                    break;
                case 'NuSoap':
                    $driver = new NuSoapDriver();
                    break;
                default:
                    $driver = new RestDriver();
                    break;
            }

            return new Zarinpal($merchantID, $driver);
        });
    }

    /**
     * Publish the plugin configuration.
     */
    public function boot()
    {
        //
    }
}

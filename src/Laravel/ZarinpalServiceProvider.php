<?php

namespace Zarinpal\Laravel;

use Illuminate\Support\ServiceProvider;
use Zarinpal\Drivers\DriverInterface;
use Zarinpal\Drivers\RestDriver;
use Zarinpal\Zarinpal;

class ZarinpalServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(DriverInterface::class, function () {
            return new RestDriver();
        });

        $this->app->singleton('Zarinpal', function () {
            $merchantID = config('services.zarinpal.merchantID', config('Zarinpal.merchantID', 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX'));

            $zarinpal = new Zarinpal($merchantID, $this->app->make(DriverInterface::class));

            if (config('services.zarinpal.sandbox', false)) {
                $zarinpal->enableSandbox();
            }
            if (config('services.zarinpal.zarinGate', false)) {
                $zarinpal->isZarinGate();
            }

            return $zarinpal;
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

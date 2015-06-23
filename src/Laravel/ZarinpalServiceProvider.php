<?php namespace Zarinpal\Laravel;

use Illuminate\Support\ServiceProvider;
use Zarinpal\Drivers\NuSoap;
use Zarinpal\Drivers\Soap;
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
        $this->app->bind('Zarinpal', function () {
            $mrchantID = config('Zarinpal.mrchantID', 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX');
            $driver = config('Zarinpal.Soap', 'Soap');
            switch ($driver) {
                case 'Soap':
                    $driver = new Soap();
                    break;
                case 'NuSoap':
                    $driver = new NuSoap();
                    break;
            }
            return new Zarinpal($mrchantID, $driver);
        });
    }

    /**
     * Publish the plugin configuration.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/Zarinpal.php' => config_path('Zarinpal.phpphp')
        ]);
    }
}
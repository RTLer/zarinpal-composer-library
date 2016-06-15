# zarinpal-composer-library 
[![Build Status](https://travis-ci.org/RTLer/zarinpal-composer-library.svg?branch=master)](https://travis-ci.org/RTLer/zarinpal-composer-library) 
[![StyleCI](https://styleci.io/repos/37937280/shield)](https://styleci.io/repos/37937280)
[![Coverage Status](https://coveralls.io/repos/github/RTLer/zarinpal-composer-library/badge.svg?branch=master)](https://coveralls.io/github/RTLer/zarinpal-composer-library?branch=master)


transaction request library for zarinpal

##laravel ready
this package is going to work with all kinds of projects, but for laravel i add provider to make it as easy as possible.
just add :
```php
'providers' => array(
    ...
    Zarinpal\Laravel\ZarinpalServiceProvider::class
    ...
)
```
to providers list in "config/app.php". and run
'`php artisan vendor:publish --provider="Zarinpal\Laravel\ZarinpalServiceProvider"`'
to add config file to laravel configs directory config it and you are good to go
now you can access the zarinpal lib like this:
```php
use Zarinpal\Laravel\Facade\Zarinpal;

Zarinpal::request("example.com/testVerify.php",1000,'testing');
Zarinpal::verify('OK',1000,$answer['Authority']);
```


##usage
###installation 
``composer require zarinpal/zarinpal``
or
```json
"require": {
    ...
    "zarinpal/zarinpal" : "1.*",
    ...
},
```

###request
```php
use Zarinpal\Zarinpal;

$test = new Zarinpal('XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX');
echo json_encode($answer = $test->request("example.com/testVerify.php",1000,'testing'));
if(isset($answer['Authority'])) {
    file_put_contents('Authority',$answer['Authority']);
    $test->redirect();
}
//it will redirect to zarinpal to do the transaction or fail and just echo the errors.
//$answer['Authority'] must save somewhere to do the verification  
```

###verify
```php
use Zarinpal\Zarinpal;

$test = new Zarinpal('XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX');
$answer['Authority'] = file_get_contents('Authority');
echo json_encode($test->verify('OK',1000,$answer['Authority']));
//'Status'(index) going to be 'success', 'error' or 'canceled'
```
##change driver
driver can be changed between restAPI , soap and NuSoap with using:

restAPI (recommended):
```php
$test = new Zarinpal('XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX');
```
or soap:
```php
use Zarinpal\Drivers\SoapDriver;
$test = new Zarinpal('XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX',new soapDriver());
```
or nuSoap:
```php
use Zarinpal\Drivers\NuSoapDriver;
$test = new Zarinpal('XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX',new NuSoapDriver());
```



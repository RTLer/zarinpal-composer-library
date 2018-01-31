# zarinpal-composer-library 
[![Build Status](https://travis-ci.org/RTLer/zarinpal-composer-library.svg?branch=master)](https://travis-ci.org/RTLer/zarinpal-composer-library) 
[![StyleCI](https://styleci.io/repos/37937280/shield)](https://styleci.io/repos/37937280)
[![Coverage Status](https://coveralls.io/repos/github/RTLer/zarinpal-composer-library/badge.svg?branch=master)](https://coveralls.io/github/RTLer/zarinpal-composer-library?branch=master)


transaction request library for zarinpal

## usage
### installation
``composer require zarinpal/zarinpal``

### request
```php
use Zarinpal\Zarinpal;

$zarinpal = new Zarinpal('XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX');
$zarinpal->enableSandbox(); // active sandbox mod for test env
// $zarinpal->isZarinGate(); // active zarinGate mode
$results = $zarinpal->request(
    "example.com/testVerify.php",          //required
    1000,                                  //required
    'testing',                             //required
    'me@example.com',                      //optional
    '09000000000',                         //optional
    json_encode([                          //optional
        "Wages" => [
            "zp.1.1"'=> [
                "Amount"'=> 120,
                "Description"'=> "part 1"
            ],
            "zp.2.5"'=> [
                "Amount"'=> 60,
                "Description"'=> "part 2"
            ]
        ]
    ])
);
echo json_encode($results);
if (isset($results['Authority'])) {
    file_put_contents('Authority', $results['Authority']);
    $zarinpal->redirect();
}
//it will redirect to zarinpal to do the transaction or fail and just echo the errors.
//$results['Authority'] must save somewhere to do the verification
```

### verify
```php
use Zarinpal\Zarinpal;

$zarinpal = new Zarinpal('XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX');
$authority = file_get_contents('Authority');
echo json_encode($zarinpal->verify('OK', 1000, $authority));
//'Status'(index) going to be 'success', 'error' or 'canceled'
```

## laravel ready
this package is going to work with all kinds of projects, but for laravel i add provider to make it as easy as possible.
just add **(if you are using laravel 5.5 or higher skip this one)**:
```php
'providers' => [
    ...
    Zarinpal\Laravel\ZarinpalServiceProvider::class
    ...
]
``` 
to providers list in "config/app.php". then add this to `config/services.php`
```php
'zarinpal' => [
    'merchantID' => 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX',
    'zarinGate' => false,
    'sandbox' => false,
],
```
and you are good to go (legacy config still works)
now you can access the zarinpal lib like this:
```php
use Zarinpal\Laravel\Facade\Zarinpal;

$results = Zarinpal::request(
    "example.com/testVerify.php",          //required
    1000,                                  //required
    'testing',                             //required
    'me@example.com',                      //optional
    '09000000000',                         //optional
    json_encode([                          //optional
        "Wages" => [
            "zp.1.1" => [
                "Amount" => 120,
                "Description" => "part 1"
            ],
            "zp.2.5" => [
                "Amount" => 60,
                "Description" => "part 2"
            ]
        ]
    ])
);
// save $results['Authority'] for verifying step
Zarinpal::redirect(); // redirect user to zarinpal

// after that verify transaction by that $results['Authority']
Zarinpal::verify('OK',1000,$results['Authority']);
```


# zarinpal-composeer-library
transaction request library for zarinpal

##laravel ready
this package is going to work with all kinds of projects, but for laravel i add provider to make it as easy as possible.
just add :
```php
'providers' => array(
    ...
    'Zarinpal\Laravel\ZarinpalServiceProvider'
    ...
)
```
to providers list in "config/app.php".

##usage

###request
```php
use Zarinpal\Drivers\Soap;
use Zarinpal\Zarinpal;

$test = new Zarinpal('XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX',new soap());
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
use Zarinpal\Drivers\Soap;
use Zarinpal\Zarinpal;

$test = new Zarinpal('XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX',new soap());
$answer['Authority'] = file_get_contents('Authority');
echo json_encode($test->verify('OK',1000,$answer['Authority']));
//'Status'(index) going to be 'success', 'error' or 'canceled'
```
##change driver
driver can be changed between soap and NuSoap with using:
```php
use Zarinpal\Drivers\Soap;
$test = new Zarinpal('XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX',new soap());
```
or:
```php
use Zarinpal\Drivers\NuSoap;
$test = new Zarinpal('XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX',new NuSoap());
```
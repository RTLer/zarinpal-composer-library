# zarinpal-composeer-library
transaction request library for zarinpal

##laravel ready
this package is going to work with all kinds of projects, but for laravel i add provider to make it as easy as possible.
just add :
```
Zarinpal\Laravel\ZarinpalServiceProvider
```
to providers list in "config/app.php".

##usage
request
```php
use Zarinpal\Drivers\Soap;
use Zarinpal\Zarinpal;

$test = new Zarinpal('XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX',new soap());
echo json_encode($answer = $test->request("example.com/testVerify.php",1000,'testing'));
if(isset($answer['Authority']))
    $test->redirect();
//it will redirect to zarinpal to do the transaction or fail and just echo the errors.
//$answer['Authority'] must save somewhere to do the verification  
```
```php
use Zarinpal\Drivers\Soap;
use Zarinpal\Zarinpal;

$test = new Zarinpal('XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX',new soap());
echo json_encode($test->verify('OK',1000,'###########'));
//'Status'(index) going to be 'success', 'error' or 'canceled'
```
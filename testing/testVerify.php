<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Zarinpal\Drivers\Soap;
use Zarinpal\Zarinpal;

$test = new Zarinpal('XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX',new soap());
$answer['Authority'] = file_get_contents('Authority');
echo json_encode($test->verify('OK',1000,$answer['Authority']));

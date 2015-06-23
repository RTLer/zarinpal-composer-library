<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Zarinpal\Drivers\Soap;
use Zarinpal\Zarinpal;



$test = new Zarinpal('XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX',new soap());
echo json_encode($test->request("example.com/testVerify.php",1000,'testing'));

<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Zarinpal\Drivers\Soap;
use Zarinpal\Zarinpal;



$test = new Zarinpal('XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX',new soap());
echo json_encode($answer = $test->request("example.com/testVerify.php",1000,'testing'));
if(isset($answer['Authority'])) {
    file_put_contents('Authority',$answer['Authority']);
    $test->redirect();
}

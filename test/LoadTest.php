<?php
use alipay\Request\Pay;

require_once(dirname(__DIR__) . "/vendor/autoload.php");

$pay = new Pay();


if ($pay instanceof Pay) {
    var_dump(true);
   $pay -> request();
}

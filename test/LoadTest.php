<?php
use alipay\pay\Request;

require_once(dirname(__DIR__) . "/vendor/autoload.php");

$pay = new Request();


if ($pay instanceof Request) {
    var_dump(true);
    return true;
}

<?php

namespace tests;

use alipay\Request\Pay;
use alipay\Core\Account;
use PHPUnit\Framework\TestCase;

include_once(__DIR__ . "/../vendor/autoload.php");

class PayTest extends TestCase {
    public $appid = 'Appid';
    public $privateKey = 'MIICXAIBAAKBgQChw3cODIBOWspmPSIx997XbUqraJF09tsTaICRPXn0X9ysF0q5qRj06DT2eZSHgDRZ4hrc0wIjWW5nitQepIfhlzBHsK7Q54YzkToxEfdwVs2fnDV+DVyMvKH+jKzvzvYweATpc2tra/3WQQHzFW118m3Clm7+MKql86ZCm9n+5QIDAQABAoGBAIgpnURRF4tc9vO6T9dZutUBJRJFcfLPe7bJhzc6VTZznq+o53iCMwSrlTFHQ9QPAYILRetNHoac18cGX+jvO4Q5QoQmm4ud9DLOOguqxz94RVLnDxP4qpP8IQrWqTBQNYi98fYUTwtjkkJBjiFBa6MMb8zDQjKhlj/E5EYJwnKdAkEAr383/+f68J5lu84b2hgQ8MyVH1cM6kmEbwplnaTk0VCMOSwEePR2GgO7hR+jJM6t1kSIRWyknihOp9KeAQbcmwJBAOv3hJnni+g20QBSINzF2ksupfE2zc1a1qLHrKYBpfU7+r1nL2Tn9XhzCjWjZnQkAdWZ35UrvEi0iXcfoP6Fin8CQEfarcIAaFU2dW7kn7C3I9CD4xaW3LncafXQ6vQVvH4bHZ6W8CnZ5bUXDCMgZfq/CJvvKWBLcEc1N6cs6/Z9qbsCQQCqJVggAa4ISz55FL9NcfztqT1OqU3MfWu3BHOhYB7irdUnLXgnMnr11z3NX31I0Y3hD4sAbQDfDA7zLoNQA8rRAj9D5ckXxv+YjH4wK1eNP+HBsA9AJ2aN8W8OgxRWq1jhDVkYiMPGikGBAVy/hNKuGTyiuMnv6S/Ck89kwtaNfh8=';
    public $publicKey = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQChw3cODIBOWspmPSIx997XbUqraJF09tsTaICRPXn0X9ysF0q5qRj06DT2eZSHgDRZ4hrc0wIjWW5nitQepIfhlzBHsK7Q54YzkToxEfdwVs2fnDV+DVyM';

    public function testPay() {
        $account = new Account($this -> appid, $this -> privateKey, $this -> publicKey, 'RSA2');

        if ($account instanceof Account) {
        } else {
            return false;
        }

        $pay = new Pay($account);

        if ($pay instanceof Pay) {
            $response = $pay -> request();
        }
        $this -> assertArrayHasKey("code", $response->getBody());
        return true;
    }
}

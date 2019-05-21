# 支付宝SDK
> http://github.com/itzcy/alipay-sdk.git

## 使用Demo
```php
<?php
use alipay\Core\Account;
use alipay\Request\Pay;

$appid = "You Appid";
$privateKey = "MIICXAIBAAKBgQChw3cODIBOWspmPSIx997XbUqraJF09tsTaICRPXn0X9ysF0q5qRj06DT2eZSHgDRZ4hrc0wIjWW5nitQepIfhlzBHsK7Q54YzkToxEfdwVs2fnDV+DVyMvKH+jKzvzvYweATpc2tra/3WQQHzFW118m3Clm7+MKql86ZCm9n+5QIDAQABAoGBAIgpnURRF4tc9vO6T9dZutUBJRJFcfLPe7bJhzc6VTZznq+o53iCMwSrlTFHQ9QPAYILRetNHoac18cGX+jvO4Q5QoQmm4ud9DLOOguqxz94RVLnDxP4qpP8IQrWqTBQNYi98fYUTwtjkkJBjiFBa6MMb8zDQjKhlj/E5EYJwnKdAkEAr383/+f68J5lu84b2hgQ8MyVH1cM6kmEbwplnaTk0VCMOSwEePR2GgO7hR+jJM6t1kSIRWyknihOp9KeAQbcmwJBAOv3hJnni+g20QBSINzF2ksupfE2zc1a1qLHrKYBpfU7+r1nL2Tn9XhzCjWjZnQkAdWZ35UrvEi0iXcfoP6Fin8CQEfarcIAaFU2dW7kn7C3I9CD4xaW3LncafXQ6vQVvH4bHZ6W8CnZ5bUXDCMgZfq/CJvvKWBLcEc1N6cs6/Z9qbsCQQCqJVggAa4ISz55FL9NcfztqT1OqU3MfWu3BHOhYB7irdUnLXgnMnr11z3NX31I0Y3hD4sAbQDfDA7zLoNQA8rRAj9D5ckXxv+YjH4wK1eNP+HBsA9AJ2aN8W8OgxRWq1jhDVkYiMPGikGBAVy/hNKuGTyiuMnv6S/Ck89kwtaNfh8=";

$account = new Account($appid, $privateKey, $publicKey);
$account -> setNotityUrl("https://domain.com/callback/alipay.do");

$extendParams = ["sys_service_provider_id" => "2088511833207846"];
$pay = new Pay($account);
$response = $pay -> setParam("out_trade_no", "20150320010101001")
        -> setParam("scene", "bar_code")
        -> setParam("auth_code", "28763443825664394")
        -> setParam("subject", "商品名称")
        -> setParam("total_amount", "88.88")
        -> setParam("trans_currency", "CNY")
        -> setParam("settle_currency", "CNY")
        -> setParam("store_id", "TLJ0100002")
        -> setParam("terminal_id", "TLJ0100002")
        -> setParam("extend_params", json_encode($extendParams, JSON_UNESCAPED_UNICODE))
        -> request();
// $request -> getSourceData(); // 原始返券请求
$request -> getBody(); // 支付宝响应数据 array 格式
```

## 配置注意事项

配置私钥公钥到配置文件中不能有任何换行符;
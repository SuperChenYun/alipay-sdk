<?php

namespace alipay\Core;

class Account 
{
    protected $appid; // 应用id
    protected $privateKey; // 私钥
    protected $publicKey; // 公钥
    protected $signType; // 签名类型
    
    /**
     * Init Object
     * 
     * @param string $appid
     * @param string $privateKey
     * @param string $publicKey
     * @param string $signType
     */
    public function __construct($appid, $privateKey, $publicKey = '', $signType = "RSA2")
    {
        $this -> appid = $appid;
        $this -> privateKey = $privateKey;
        $this -> publicKey = $publicKey;
        $this -> signType = $signType;
        
    }

    /**
     * 设置签名类型
     *
     * @param string $signType
     * @return void
     */
    public function setSignType($signType = "RSA2")
    {
        $signTypeList = [
            "RSA2",
            "RSA"
        ];
        if (!in_array($signType, $signTypeList)) {
            throw new Exception("不支持的签名类型:" . $signType);
        }
        $this -> signType = $signType;
    }

    /**
     * 获取签名类型
     *
     * @return String
     */
    public function getSignType()
    {
        return $this -> signType;
    }

    /**
     * 返回当前账户的应用id
     *
     * @return string
     */
    public function getAppid()
    {
        return $this -> appid;
    }

    /**
     * 返回当前账户的私钥字符串
     *
     * @return string
     */
    public function getPrivateKey()
    {
        return $this -> privateKey;
    }

    /**
     * 返回当前账户的公钥字符串
     *
     * @return string
     */
    public function getPublicKey()
    {
        return $this -> publicKey;
    }
}
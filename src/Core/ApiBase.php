<?php
/**
 * 统一收单接口父类
 * 
 * @author itzcy <itzcy@itzcy.com>
 * @version 0.0.1
 * @link https://github.com/itzcy/alipay-sdk
 */

namespace alipay\Core;

use alipay\Response\Response;

class ApiBase
{

	public $rsaPrivateKeyFilePath;//私钥文件路径
    public $rsaPrivateKey;	//私钥值
    

	protected $gatewayUrl = "https://openapi.alipay.com/gateway.do"; //网关

    protected $appId = ""; // 应用id
    protected $method = ""; // 接口名称
    protected $format = "JSON"; // 数据格式
    protected $returnUrl = ""; // HTTP/HTTPS 开头字符串
    protected $charset = "UTF-8"; // 编码格式 
    protected $signType = "RSA2"; // 签名算法类型
    protected $signField = "sign"; // 签名字段
    protected $timestamp = "1970-01-01 08:00:00"; // 时间 请求发送的时间
    protected $apiVersion = "1.0"; // 接口版本
    protected $notifyUrl = ""; // 回调通知地址
    protected $appAuthToken = ""; // 授权令牌
    protected $bizContent = ""; // 请求参数的集合

    protected $bizContentArr = []; // 请求参数

    
    public function __construct()
    {
        $this->appId = "";
        $this -> timestamp = date("Y-m-d H:i:s");
    }

    /**
     * 检测要请求的参数 并且过滤空参数
     */
    public function checkParams(&$sysParams) 
    {

    }

    public function getBizContent()
    {
    }

    public function request()
    {
        //组装系统参数
        $sysParams["app_id"] = $this->appId;
		$sysParams["method"] = $this->method;
		$sysParams["format"] = $this->format;
		$sysParams["charset"] = $this->charset;
        $sysParams["sign_type"] = $this->signType;
		$sysParams["timestamp"] = $this->timestamp;        
		$sysParams["version"] = $this->apiVersion;
        $sysParams["app_auth_token"] = $this->appAuthToken;

        $sysParams["notify_url"] = $this->notifyUrl;
        $sysParams["return_url"] = $this->returnUrl;

        $sysParams['biz_content'] = $this -> getBizContent();
        
        $this -> checkParams($sysParams);
        $sysParams['sign'] = $this -> sign($this->getSignContent($sysParams), $this->signType);

        // post
        $resp = $this -> curl($this -> gatewayUrl, $sysParams);
        var_dump($resp);
        

    }

    /** 
     * 生成签名串
	 *  在使用本方法前，必须初始化AopClient且传入公私钥参数。
	 *  公钥是否是读取字符串还是读取文件，是根据初始化传入的值判断的。
	 **/
	public function getSignContent($params) {
		ksort($params);

		$stringToBeSigned = "";
		$i = 0;
		foreach ($params as $k => $v) {
			if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {

				// 转换成目标字符集
				$v = $this->characet($v, $this -> charset);

				if ($i == 0) {
					$stringToBeSigned .= "$k" . "=" . "$v";
				} else {
					$stringToBeSigned .= "&" . "$k" . "=" . "$v";
				}
				$i++;
			}
		}

		unset ($k, $v);
		return $stringToBeSigned;
	}
    
    /**
     * 计算签名
     *
     * @param array $data
     * @param string $signType
     * @return void
     */
    protected function sign($data, $signType = "RSA") {
		if($this->checkEmpty($this->rsaPrivateKeyFilePath)){
			$priKey=$this->rsaPrivateKey;
			$res = "-----BEGIN RSA PRIVATE KEY-----\n" .
				wordwrap($priKey, 64, "\n", true) .
				"\n-----END RSA PRIVATE KEY-----";
		}else {
			$priKey = file_get_contents($this->rsaPrivateKeyFilePath);
			$res = openssl_get_privatekey($priKey);
		}

		($res) or die('您使用的私钥格式错误，请检查RSA私钥配置'); 

		if ("RSA2" == $signType) {
			openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
		} else {
			openssl_sign($data, $sign, $res);
		}

		if(!$this->checkEmpty($this->rsaPrivateKeyFilePath)){
			openssl_free_key($res);
		}
		$sign = base64_encode($sign);
		return $sign;
    }
    
    /**
     * 请求
     *
     * @param [type] $url
     * @param [type] $postFields
     * @return void
     */
    protected function curl($url, $postFields = null) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FAILONERROR, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$postBodyString = "";
		$encodeArray = Array();
		$postMultipart = false;


		if (is_array($postFields) && 0 < count($postFields)) {

			foreach ($postFields as $k => $v) {
				if ("@" != substr($v, 0, 1)) //判断是不是文件上传
				{
					$postBodyString .= "$k=" . urlencode($this->characet($v, $this->postCharset)) . "&";
					$encodeArray[$k] = $this->characet($v, $this->postCharset);
				} else //文件上传用multipart/form-data，否则用www-form-urlencoded
				{
					$postMultipart = true;
					$encodeArray[$k] = new \CURLFile(substr($v, 1));
				}

			}
			unset ($k, $v);
			curl_setopt($ch, CURLOPT_POST, true);
			if ($postMultipart) {
				curl_setopt($ch, CURLOPT_POSTFIELDS, $encodeArray);
			} else {
				curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString, 0, -1));
			}
		}

		if ($postMultipart) {

			$headers = array('content-type: multipart/form-data;charset=' . $this->postCharset . ';boundary=' . $this->getMillisecond());
		} else {

			$headers = array('content-type: application/x-www-form-urlencoded;charset=' . $this->postCharset);
		}
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$reponse = curl_exec($ch);

		if (curl_errno($ch)) {

			throw new Exception(curl_error($ch), 0);
		} else {
			$httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if (200 !== $httpStatusCode) {
				throw new Exception($reponse, $httpStatusCode);
			}
		}

		curl_close($ch);
		return $reponse;
    }
    

    /**
	 * 转换字符集编码
	 * @param $data
	 * @param $targetCharset
	 * @return string
	 */
	function characet($data, $targetCharset) {
		
		if (!empty($data)) {
			$type = $this->charset;
			if (strcasecmp($type, $targetCharset) != 0) {
				$data = mb_convert_encoding($data, $targetCharset, $type);
			}
		}


		return $data;
    }
    
    /**
	 * 校验$value是否非空
	 *  if not set ,return true;
	 *    if is null , return true;
	 **/
	protected function checkEmpty($value) {
		if (!isset($value))
			return true;
		if ($value === null)
			return true;
		if (trim($value) === "")
			return true;

		return false;
	}
}
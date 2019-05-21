<?php
/**
 * 统一收单接口父类
 * 
 * @author itzcy <itzcy@itzcy.com>
 * @version 0.0.1
 * @link https://github.com/itzcy/alipay-sdk
 */

namespace alipay\Core;

use alipay\Core\Response;

class ApiBase
{

	public $rsaPrivateKeyFilePath;//私钥文件路径
    public $rsaPrivateKey;	//私钥值
    

	protected $gatewayUrl = "https://openapi.alipay.com/gateway.do"; //网关

    protected $appId = ""; // 应用id
    protected $method = ""; // 接口名称 【子类覆盖】
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

    protected $bizContentArr = []; // 请求参数集合

	
	/**
	 * Init Object
	 *
	 * @param Account $account
	 */
    public function __construct(Account $account)
    {
		$this -> appId = $account -> getAppid();
		$this -> rsaPrivateKey  = $account -> getPrivateKey();
		$this -> signType  = $account -> getSignType();
		$this -> timestamp = date("Y-m-d H:i:s");
		$this -> appAuthToken = $this -> getAppAuthToken();
	}
	
	/**
	 * 获取AppAuthToken
	 *
	 * @return string
	 */
	protected function getAppAuthToken()
	{
		return '';
	}

	/**
	 * 设置回调通知地址
	 *
	 * @param string $url
	 * @return void
	 */
	public function setNotityUrl($url)
	{
		$this -> notifyUrl = $url;
		return $this;
	}
	
	/**
	 * 获取回调通知地址
	 *
	 * @return void
	 */
	public function getNotifyUrl()
	{
		return $this -> notifyUrl;
	}
    /**
     * 检测要请求的参数 并且过滤空参数
     */
    public function checkParams(&$sysParams) 
    {
		foreach($sysParams as $key => $val) {
			if ( $this -> checkEmpty($val) ) {
				unset($sysParams[$key]);
			}
		}
	}
	
	/**
	 * 设置单个请求参数详情
	 *
	 * @param [type] $key
	 * @param [type] $val
	 * @return void
	 */
	public function setParam($key, $val)
	{
		$this -> bizContentArr[$key] = $val;
		return $this;
	}

	/**
	 * 获取单个请求参数详情
	 *
	 * @param [type] $key
	 * @param [type] $val
	 * @return null|string
	 */
	public function getParam($key, $val = null)
	{
		if (isset($this -> bizContentArr[$key])) {
			return $this -> bizContentArr[$key];
		}
		return null;
	}

	/**
	 * 获取BizContent 支付详情
	 *
	 * @return void
	 */
    public function getBizContent()
    {
		$this -> bizContent = json_encode($this -> bizContentArr, JSON_UNESCAPED_UNICODE);
		return $this -> bizContent;
    }

	/**
	 * 请求支付宝
	 *
	 * @return void
	 */
    public function request()
    {
        // 组装系统参数
        // COMMON PARAM
        $sysParams["app_id"]         = $this->appId;
        $sysParams["method"]         = $this->method;
        $sysParams["format"]         = $this->format;
        $sysParams["charset"]        = $this->charset;
        $sysParams["sign_type"]      = $this->signType;
        $sysParams["timestamp"]      = $this->timestamp;        
        $sysParams["version"]        = $this->apiVersion;
        $sysParams["app_auth_token"] = $this->appAuthToken;
        $sysParams["notify_url"]     = $this->notifyUrl;
        $sysParams["return_url"]     = $this->returnUrl;
        // INFO PARAM
        $sysParams['biz_content']    = $this -> getBizContent();

        $this -> checkParams($sysParams);
        $sysParams['sign'] = $this -> sign($this->getSignContent($sysParams), $this->signType);

        // POST
        $resp = Request::curl($this -> gatewayUrl, $sysParams);

        $baseResp = new Response($resp, "json");
        $baseRespArr = $baseResp -> getBody();
        $key = str_replace(".", "_", $this->method);
        unset($baseResp);
        return new Response(json_encode($baseRespArr[$key . "_response"], JSON_UNESCAPED_UNICODE), "json");
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
			$priKey = "-----BEGIN RSA PRIVATE KEY-----\n" .
				wordwrap($priKey, 64, "\n", true) .
				"\n-----END RSA PRIVATE KEY-----";
		}else {
			$priKey = file_get_contents($this->rsaPrivateKeyFilePath);
		}

		$res = openssl_get_privatekey($priKey);

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
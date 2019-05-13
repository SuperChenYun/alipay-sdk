<?php
/**
 * 手机网站支付接口2.0
 * 
 * alipay.trade.wap.pay
 * 
 * @author itzcy <itzcy@itzcy.com>
 * @version 0.0.1
 * @link https://github.com/itzcy/alipay-sdk
 */

namespace alipay\Request;

use alipay\Core\ApiBase;

class WayPay extends ApiBase
{
    protected $method = "alipay.trade.wap.pay"; // 接口名称
}
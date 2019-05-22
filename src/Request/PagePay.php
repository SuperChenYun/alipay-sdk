<?php
/**
 * 统一收单下单并支付页面接口
 * 
 * alipay.trade.page.pay
 * 
 * @author itzcy <itzcy@itzcy.com>
 * @version 0.0.1
 * @link https://github.com/itzcy/alipay-sdk
 */

namespace alipay\Request;

use alipay\Core\ApiBase;

class PagePay extends ApiBase
{
    protected $method = "alipay.trade.page.pay"; // 接口名称
}

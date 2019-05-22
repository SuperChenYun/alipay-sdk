<?php
/**
 * 支付宝订单退款
 * 
 * alipay.trade.refund
 * 
 * @author itzcy <itzcy@itzcy.com>
 * @version 0.0.1
 * @link https://github.com/itzcy/alipay-sdk
 */

namespace alipay\Request;

use alipay\Core\ApiBase;

class Refund extends ApiBase
{
    protected $method = "alipay.trade.refund"; // 接口名称
}

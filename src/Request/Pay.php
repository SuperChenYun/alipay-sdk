<?php
/**
 * 面对面支付
 * 
 * alipay.trade.pay
 * 
 * @author itzcy <itzcy@itzcy.com>
 * @version 0.0.1
 * @link https://github.com/itzcy/alipay-sdk
 */

namespace alipay\Request;

use alipay\Core\ApiBase;
use alipay\Core\Response;

class Pay extends ApiBase
{
    protected $method = "alipay.trade.pay"; // 接口名称
}
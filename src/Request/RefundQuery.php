<?php
/**
 * 统一收单交易退款查询
 * 
 * alipay.trade.fastpay.refund.query
 * 
 * @author itzcy <itzcy@itzcy.com>
 * @version 0.0.1
 * @link https://github.com/itzcy/alipay-sdk
 */

namespace alipay\Request;

use alipay\Core\ApiBase;

class RefundQuery extends ApiBase
{
    protected $method = "alipay.trade.fastpay.refund.query"; // 接口名称
}
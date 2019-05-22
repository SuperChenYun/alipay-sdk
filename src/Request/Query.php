<?php
/**
 * 统一收单线下交易查询
 * 
 * alipay.trade.query
 * 
 * @author itzcy <itzcy@itzcy.com>
 * @version 0.0.1
 * @link https://github.com/itzcy/alipay-sdk
 */

namespace alipay\Request;

use alipay\Core\ApiBase;

class Query extends ApiBase
{
    protected $method = "alipay.trade.query"; // 接口名称
}
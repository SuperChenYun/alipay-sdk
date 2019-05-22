<?php
/**
 * 统一收单交易创建接口
 * 
 * alipay.trade.create
 * 
 * @author itzcy <itzcy@itzcy.com>
 * @version 0.0.1
 * @link https://github.com/itzcy/alipay-sdk
 */

namespace alipay\Request;

use alipay\Core\ApiBase;

class Create extends ApiBase
{
    protected $method = "alipay.trade.create"; // 接口名称
}
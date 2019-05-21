<?php
/**
 * 统一收单交易撤销接口
 * 
 * alipay.trade.cancel
 * 
 * @author itzcy <itzcy@itzcy.com>
 * @version 0.0.1
 * @link https://github.com/itzcy/alipay-sdk
 */

namespace alipay\Request;

use alipay\Core\ApiBase;

class Cancel extends ApiBase
{
    protected $method = "alipay.trade.cancel"; // 接口名称
}

<?php
/**
 * 统一收单交易关闭接口
 * 
 * alipay.trade.close
 * 
 * @author itzcy <itzcy@itzcy.com>
 * @version 0.0.1
 * @link https://github.com/itzcy/alipay-sdk
 */

namespace alipay\Request;

use alipay\Core\ApiBase;

class Close extends ApiBase
{
    protected $method = "alipay.trade.close"; // 接口名称
}
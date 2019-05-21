<?php
/**
 * @author itzcy <itzcy@itzcy.com>
 * @version 0.0.1
 */

 namespace alipay\Core;

/**
* 返回资源类
 */
class Response 
{
    /**
     * 支持的返回数据类型
     *
     * @var array
     */
    protected $dataTypeList = [
        "json",
        "xml",
        "string"
    ];

    /**
     * 原始数据
     *
     * @var [type]
     */
    protected $sourceData;

    /**
     * 返回数据类型
     *
     * @var string
     */
    protected $dataType = "string";

    /**
     * 转换结果数据
     *
     * @var array|string
     */
    protected $body; 

    /**
     * Init Object
     *
     * @param string $sourceData
     * @param string $dataType
     */
    public function __construct($sourceData, $dataType= "string")
    {
        if (!in_array($dataType, $this -> dataTypeList)) {
            throw Exception("不支持的接口数据类型：" . $dataType);
        }
        $this -> sourceData = $sourceData;
        $this -> dataType = $dataType;
        $this -> data = $this -> coverData();
    }

    protected function coverData()
    {
        switch($this -> dataType) {
            case "xml":
                $this -> body = $this -> xmlToArray($this -> sourceData);
                break;
            case "json":
                $this -> body = $this -> jsonToArray($this -> sourceData);
                break;
            case "string":
                $this -> body = $this -> sourceData;
                break;
            default:
                throw new Exception("不支持的数据类型");
        }
    }

    /**
     * xml 字符串 转数组
     *
     * @return void
     */
    protected function xmlToArray($xml)
    {
        $objectXml = simplexml_load_string($xml);//将文件转换成 对象
        $xmlJson= json_encode($objectXml);//将对象转换个JSON
        return json_decode($xmlJson,true);//将json转换成数组
    }

    /**
     * json 字符串转换为数组
     *
     * @return void
     */
    protected function jsonToArray($json)
    {
        return json_decode($json, true);
    }

    /**
     * 获取原始数据
     *
     * @return string
     */
    public function getSourceData()
    {
        return $this -> sourceData;
    }

    /**
     * 获取转换后的数据
     *
     * @return array|string
     */
    public function getBody()
    {
        return $this -> body;
    }
}
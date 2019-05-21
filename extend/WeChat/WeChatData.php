<?php
/**
 * Created by PhpStorm.
 * User: Yeah
 * Date: 2018/8/18
 * Time: 15:21
 */
namespace WeChat;
class WeChatData{

    public static function arrayToXml($array)
    {
        if (!is_array($array) || count($array) <= 0)
            return null;
        $xml = "<xml>\r\n";
        $xml .= self::arrayToXmlSub($array);
        $xml .= "</xml>";
        return $xml;
    }

    private static function arrayToXmlSub($array)
    {
        if (!is_array($array) || count($array) <= 0)
            return null;
        $xml = "";
        foreach ($array as $key => $val) {
            if (is_array($val)) {
                if (is_numeric($key))
                    $xml .= self::arrayToXmlSub($val);
                else
                    $xml .= "<" . $key . ">" . self::arrayToXmlSub($val) . "</" . $key . ">\r\n";
            } elseif (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">\r\n";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">\r\n";
            }
        }
        return $xml;
    }

    /**
     * @param $xml
     * @return bool|mixed|null
     */
    public static function xmlToArray($xml)
    {
        try {
            if (!$xml) {
                return null;
            }
            //将XML转为array
            //禁止引用外部xml实体

            //libxml_disable_entity_loader(true);
            @ $res = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);

            return $res;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function arrayToUrlParam($array, $url_encode = true)
    {
        $url_param = "";
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $list_url_param = "";
                foreach ($value as $list_key => $list_value) {
                    if (!is_array($list_value))
                        $url_param .= $key . "[" . $list_key . "]=" . ($url_encode ? urlencode($list_value) : $list_value) . "&";
                }
                $url_param .= trim($list_url_param, "&") . "&";
            } else {
                $url_param .= $key . "=" . ($url_encode ? urlencode($value) : $value) . "&";
            }
        }
        return trim($url_param, "&");
    }

}
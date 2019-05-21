<?php
/**
 * Created by PhpStorm.
 * User: Yeah
 * Date: 2018/8/18
 * Time: 18:20
 */
namespace WeChat;
class Pay extends Base {

    /**
     * @param $args
     * @return bool|mixed|null
     * 统一下单
     * 'body' => '商品描述',
     *
     * 'detail' => '商品详情，选填',
     *
     * 'attach' => '附加数据，选填',
     *
     * 'out_trade_no' => '商户订单号，最大长度32',
     *
     * 'total_fee' => '订单总金额，单位为分',
     *
     * 'notify_url' => '异步接收微信支付结果通知的回调地址，通知url必须为外网可访问的url，不能携带参数',
     *
     * 'trade_type' => '交易类型，可选值：JSAPI，NATIVE，APP',
     *
     * 'product_id' => '商品ID，trade_type=NATIVE时，此参数必传',
     *
     * 'openid' => '用户标识，trade_type=JSAPI时，此参数必传',
     *
     */
    public function unifiedOrder($args)
    {
        $args['appid'] = $this->WeChat->appId;
        $args['mch_id'] = $this->WeChat->MchId;
        $args['nonce_str'] = md5(uniqid());
        $args['sign_type'] = 'MD5';
        $args['spbill_create_ip'] = '127.0.0.1';
        $args['sign'] = $this->makeSign($args);
        $xml = WeChatData::arrayToXml($args);

        $api = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        $result=$this->WeChat->Curl($api, $xml);

        if (!$result)
            return false;
        return WeChatData::xmlToArray($result);
    }

    /**
     * @param $order_no
     * @return bool
     * 查询订单
     *
     * 'order_no'=>订单号
     *
     */
    public function orderQuery($order_no)
    {
        $data = [
            'appid' => $this->WeChat->appId,
            'mch_id' => $this->WeChat->MchId,
            'out_trade_no' => $order_no,
            'nonce_str' => md5(uniqid()),
        ];
        $data['sign'] = $this->makeSign($data);
        $xml = WeChatData::arrayToXml($data);
        $api = "https://api.mch.weixin.qq.com/pay/orderquery";
        $result=$this->WeChat->Curl($api, $xml);
        if (!$result)
            return false;
        return WeChatData::xmlToArray($result);
    }

    /**
     * @param $args
     * @return bool|mixed|null
     *
     * 退款申请
     *
     * 'out_trade_no' => '商户订单号，最大长度32',
     *
     * 'out_refund_no' => '商户退款单号，最大长度32',
     *
     * 'total_fee' => '订单总金额，单位为分',
     *
     * 'refund_fee' => '退款总金额，单位为分',
     */
    public function refund($args)
    {
        $args['appid'] = $this->WeChat->appId;
        $args['mch_id'] = $this->WeChat->MchId;
        $args['nonce_str'] = md5(uniqid());
        $args['op_user_id'] = $this->WeChat->MchId;
        $args['sign'] = $this->makeSign($args);
        $xml = WeChatData::arrayToXml($args);
        $api = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
        $result=$this->WeChat->Curl($api, $xml,true);
        if (!$result)
            return false;
        return WeChatData::xmlToArray($result);
    }
    /**
     *
     * @param $args
     * @return bool
     * 企业付款
     *
     */
    public function transfers($args)
    {
        $args['mch_appid'] = $this->WeChat->appId;
        $args['mchid'] = $this->WeChat->MchId;
        $args['nonce_str'] = md5(uniqid());
        $args['check_name'] = 'NO_CHECK';
        $args['spbill_create_ip'] = '127.0.0.1';
        $args['sign'] = $this->makeSign($args);
        $xml = WeChatData::arrayToXml($args);
        $api = "https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";
        $result=$this->WeChat->Curl($api, $xml,true);
        if (!$result)
            return false;
        return WeChatData::xmlToArray($result);
    }
    /**
     * @param $args
     * @return string
     * MD5签名
     */
    public function makeSign($args)
    {

        if (isset($args['sign']))
            unset($args['sign']);

        ksort($args);
        foreach ($args as $i => $arg) {
            if ($args === null || $arg === '')
                unset($args[$i]);
        }
        $string = WeChatData::arrayToUrlParam($args, false);
        $string = $string . "&key={$this->WeChat->apiKey}";
        $string = md5($string);
        $result = strtoupper($string);
        return $result;
    }

}
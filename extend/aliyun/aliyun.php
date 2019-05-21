<?php
/**
 * Created by PhpStorm.
 * User: Yeah
 * Date: 2018/8/25
 * Time: 15:29
 */
namespace aliyun;
class aliyun{
    protected $accessKeyId;
    protected $accessKeySecret;
    protected $SignName;
    protected $TemplateCode;
    protected $variable;
    public $ErrorMsg;

    public function __construct($argc)
    {
        $this->accessKeyId=isset($argc['access_key_id'])?$argc['access_key_id']:null;
        $this->accessKeySecret=isset($argc['access_key_secret'])?$argc['access_key_secret']:null;
        $this->SignName=isset($argc['sign'])?$argc['sign']:null;
      	$this->TemplateCode=isset($argc['tpl_id'])?$argc['tpl_id']:null;
      	$this->variable=isset($argc['variable'])?$argc['variable']:'code';
    }
    public function code($phone) {
        $params = array ();

        // fixme 必填: 短信接收号码
        $params["PhoneNumbers"] = $phone;

        // fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $params["SignName"] = $this->SignName;

        // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template

        $params["TemplateCode"] = $this->TemplateCode;

        // fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
        $msg=mt_rand(1000,9999);
        $params['TemplateParam'] = Array (
            $this->variable => $msg,
        );

        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }

        try{
            $helper = new SignatureHelper();
            $content = $helper->request(
                $this->accessKeyId,
                $this->accessKeySecret,
                "dysmsapi.aliyuncs.com",
                array_merge($params, array(
                    "RegionId" => "cn-hangzhou",
                    "Action" => "SendSms",
                    "Version" => "2017-05-25",
                ))
            );
            if (empty($content->Code)){
                return $this->ErrorMsg='请求错误';
            }
            if($content->Code!='OK'){
                $this->ErrorMsg=$content->Message;
                return false;
            };

            return $msg;
        }catch (\Exception $e){
            $this->ErrorMsg='未知错误';
            return false;
        }

    }
}
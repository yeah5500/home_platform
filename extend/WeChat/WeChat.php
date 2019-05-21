<?php
/**
 * Created by PhpStorm.
 * User: Yeah
 * Date: 2018/8/18
 * Time: 13:40
 */
namespace WeChat;
use think\Cache;
class WeChat extends Base {
    public $errMsg = 0;
    public $errCode;
    public $appId;
    public $secret;
    public $cert_pem_file;
    public $key_pem_file;
    public $MchId;
    public $apiKey;
    /**
     * @var Pay
     */
    public $pay;

    /**
     * WeChat constructor.
     * @param $args
     */
    public function __construct($args)
    {
        $this->MchId=isset($args['mch_id'])?$args['mch_id']:null;
        $this->appId=isset($args['appid'])?$args['appid']:null;
        $this->secret=isset($args['appsecret'])?$args['appsecret']:null;
        $this->apiKey=isset($args['mch_key'])?$args['mch_key']:null;
        $this->key_pem_file=isset($args['ssl_key'])?$args['ssl_key']:null;
        $this->cert_pem_file=isset($args['ssl_cer'])?$args['ssl_cer']:null;
        parent::__construct($this);
        $this->pay=new Pay($this);
    }
    /**
     * @param bool $refresh
     * @param int $expires
     * @return bool|mixed
     * 获取accessToken
     */
    public function getAccessToken($refresh = false, $expires = 3600){
        $cacheKey = md5("{$this->appId}@access_token");
        $accessToken = Cache::get($cacheKey);
        $accessTokenOk = $this->checkAccessToken($accessToken);
        if (!$accessToken || $refresh || !$accessTokenOk) {
            $api = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appId}&secret={$this->secret}";
            $res = json_decode($this->Curl($api),true);

            if (empty($res['access_token'])) {
                $this->errCode = isset($res['errcode']) ? $res['errcode'] : null;
                $this->errMsg = isset($res['errmsg']) ? $res['errmsg'] : null;
                return false;
            }
            $accessToken = $res['access_token'];
            Cache::set($cacheKey, $accessToken, $expires);
            return $accessToken;
        } else {
            return $accessToken;
        }
    }

    /**
     * @param $accessToken
     * @return bool
     * 检测accessToken
     */
    private function checkAccessToken($accessToken)
    {
        if (!$accessToken)
            return false;
        $api = "https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token={$accessToken}";
        $res = json_decode($this->Curl($api),true);
        if (!empty($res['errcode']) && $res['errcode'] != 1)
            return false;
        return true;
    }

    /**
     * @param $code
     * @return bool|mixed
     * 公众号获取openid
     */
    public function getPlatformOpenId($code){
        $api='https://api.weixin.qq.com/sns/oauth2/access_token?';
        $request['appid']=$this->appId;
        $request['secret']=$this->secret;
        $request['code']=$code;
        $request['grant_type']='authorization_code';

        $res=json_decode($this->Curl($api,http_build_query($request)),true);
        if(empty($res['openid'])){
            $this->errCode = isset($res['errcode']) ? $res['errcode'] : null;
            $this->errMsg = isset($res['errmsg']) ? $res['errmsg'] : null;
            return false;
        }else{
            return $res;
        }
    }

    /**
     * @param $access_token
     * @param $openid
     * @return bool|mixed
     * 公众号获取用户信息
     */
    public function getPlatformUserInfo($access_token,$openid){
        $api='https://api.weixin.qq.com/sns/userinfo?';
        $request['access_token']=$access_token;
        $request['openid']=$openid;
        $res=json_decode($this->Curl($api,http_build_query($request)),true);
        if(empty($res['openid'])){
            $this->errCode = isset($res['errcode']) ? $res['errcode'] : null;
            $this->errMsg = isset($res['errmsg']) ? $res['errmsg'] : null;
            return false;
        }else{
            return $res;
        }
    }
    /***
     * @param $code
     * @return bool
     * 小程序获取用户OpenId,session_key
     */
    public function getOpenId($code){
        $api='https://api.weixin.qq.com/sns/jscode2session?';
        $request['appid']=$this->appId;
        $request['secret']=$this->secret;
        $request['js_code']=$code;
        $request['grant_type']='authorization_code';
        $res=json_decode($this->Curl($api,http_build_query($request)),true);
        if(empty($res['openid'])){
            $this->errCode = isset($res['errcode']) ? $res['errcode'] : null;
            $this->errMsg = isset($res['errmsg']) ? $res['errmsg'] : null;
            return false;
        }else{
            return $res;
        }

    }

    /**
     * @param $session_key
     * @param $encryptedData
     * @param $iv
     * @return mixed
     * 获取用户信息
     */
    public function getUserInfo($session_key,$encryptedData,$iv){
        $WXBizDataCrypt = new WXBizDataCrypt($this->appId, $session_key);
        $errCode = $WXBizDataCrypt->decryptData($encryptedData, $iv, $data );

        if($errCode){
            $this->errCode=$errCode;
            return false;
        }else{
            return json_decode($data,true);
        }
    }

    /**
     * @param array $config
     * @return bool|mixed|string
     * 获取分享二维码
     */
    public function getQrCode($config=[]){
        $accessToken=$this->getAccessToken();
        if(empty($accessToken))return false;
        $url="https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token={$accessToken}";
        $request=json_encode([
            'scene'=>isset($config['scene'])?$config['scene']:null,
            'width'=>isset($config['width'])?$config['scene']:450,
            'page'=> isset($config['page'])?$config['page']:'sd_liferuning/pages/constmer/index/index',
        ]);
        $result=$this->Curl($url,$request);
        $res=json_decode($result,true);
        if (!empty($res['errcode'])){
            $this->errCode = isset($res['errcode']) ? $res['errcode'] : null;
            $this->errMsg = isset($res['errmsg']) ? $res['errmsg'] : null;
            return false;
        }
        return $result;
    }
    /***
     * @param $url
     * @param string $data
     * @param bool $Cert
     * @param int $expires
     * @return mixed|string
     * 请求
     */
    public function Curl($url , $data='', $Cert = false, $expires = 30)
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_TIMEOUT, $expires);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);//严格校验
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        if ($Cert == true) {
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLCERT, $this->cert_pem_file);
            curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLKEY, $this->key_pem_file);
        }
        //post提交方式
        if($data){
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        //运行curl
        $data = curl_exec($ch);
        curl_close($ch);
        //返回结果
        if ($data) {
            return $data;
        }else{
            return false;
        }

    }

}
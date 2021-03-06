<?php
/**
 * Created by PhpStorm.
 * User: Yeah
 * Date: 2018/10/22
 * Time: 15:13
 */
namespace app\common;
use think\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use WeChat\WeChat;

class Base extends Controller{
    /**
     * @var We7
     */
    protected $We7;
    protected $acid;
    /**
     * @var WeChat
     */
    protected $weChat;
    protected $request;
    protected $weChatApp;

    /**
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 初始化
     */
    public function _initialize()
    {

        global $_W;
        if(!isset($_W['account']['acid'])||!isset($_W['account']['uniacid'])){
            exit("<script>history.back(-1)</script>");
        }
        $this->We7=new We7($_W);
        $this->acid=$_W['account']['acid'];
        $weChatApp=model('WechatApp')
            ->where(['acid'=>$this->acid])
            ->find();
        if(empty($weChatApp)){
            $data=[
                'acid'=>$this->acid,
                'appid'=>$this->We7->account['key'],
                'appsecret'=>$this->We7->account['secret']
            ];
            $res=model('WechatApp')->save($data);
            if(empty($res))error_back('初始化失败,请联系开发者:(');
            $weChatApp=model('WechatApp')->where(['acid'=>$this->acid])->find();
        }
        $this->weChatApp=$weChatApp;
        if(!is_dir(RUNTIME_PATH.'/pem')){
            mkdir(RUNTIME_PATH.'/pem',0755);
            file_put_contents(RUNTIME_PATH . '/pem/index.html', '');
        }
        $cert_pem_file=null;
        if($this->weChatApp->ssl_cer){
            $cert_pem_file=RUNTIME_PATH.'/pem/'.md5($this->weChatApp->ssl_cer);
            if(!file_exists($cert_pem_file)){
                file_put_contents($cert_pem_file,$this->weChatApp->ssl_cer);
            }
        }
        $key_pem_file = null;
        if($this->weChatApp->ssl_key){
            $key_pem_file=RUNTIME_PATH.'/pem/'.md5($this->weChatApp->ssl_key);
            if(!file_exists($key_pem_file)){
                file_put_contents($key_pem_file,$this->weChatApp->ssl_key);
            }
        }
        $this->weChat=new WeChat([
            'appid'          => $this->weChatApp->appid,
            'appsecret'      => $this->weChatApp->appsecret,
            'mch_id'         => $this->weChatApp->mch_id,
            'mch_key'        => $this->weChatApp->mch_key,
            'ssl_key'        => $key_pem_file,
            'ssl_cer'        => $cert_pem_file,
        ]);

        $this->request=new Client(['verify'=>false]);
        parent::_initialize(); // TODO: Change the autogenerated stub
    }
    public function upload(){
        $image=request()->file('file');
        $dir=ROOT_PATH.'public/uploads/image/';
        if(!is_dir($dir)){
            mkdir($dir,0755);
        }
        $res=$image->move($dir);
        if(empty($res))return json(['code'=>1,'msg'=>$res->getError()]);
        $url="{$this->We7->siteroot}addons/sd_135K/core/public/uploads/image/{$res->getSaveName()}";
        $url=str_replace("\\",'/',$url);
        return ['code'=>0,'data'=>$url];
    }
}
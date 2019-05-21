<?php
/**
 * Created by PhpStorm.
 * User: Yeah
 * Date: 2018/10/23
 * Time: 12:42
 */
namespace app\web\controller;
use app\common\Home;

class WechatApp extends Home{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }
    public function index(){
        if(request()->isPost()){
            $data=input('post.');
            $data['ssl_key']=!$data['ssl_key']?$this->weChatApp->ssl_key:$data['ssl_key'];
            $data['ssl_cer']=!$data['ssl_cer']?$this->weChatApp->ssl_cer:$data['ssl_cer'];
            $res=model('WechatApp')->save($data,['acid'=>$this->acid]);
            if($res)return json(['code'=>0,'msg'=>'设置成功']);
            return json(['code'=>1,'msg'=>'设置失败']);
        }else{
            return view('',['data'=>$this->weChatApp]);
        }
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Yeah
 * Date: 2018/10/25
 * Time: 19:48
 */
namespace app\common;
class Api extends Base{
    protected $user;
    /**
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $access_token=input('access_token');
        $user=model('User')
            ->where(['access_token'=>$access_token,'acid'=>$this->acid])
            ->find();
        if(!$user)exit(json_encode(['code'=>-1,'msg'=>'身份效验失败!']));
        $this->user=$user;
    }

}
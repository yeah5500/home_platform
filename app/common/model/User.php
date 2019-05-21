<?php
/**
 * Created by PhpStorm.
 * User: Yeah
 * Date: 2018/10/26
 * Time: 22:22
 */
namespace app\common\model;
use think\Model;

class User extends Model{
    protected $autoWriteTimestamp=true;

    /**
     * @param $acid
     * @param $user
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function login($acid,$user){
        $access_token = make_token();
        $res = $this->where(['wx_openid' => $user['openid'], 'acid' => $acid])
            ->find();
        $data['union_id'] = isset($user['unionid']) ? $user['unionid'] : '';
        if ($res) {
            $data['union_id'] = isset($user['unionid']) ? $user['unionid'] : '';
            $this->save($data, ['id' => $res->id]);
        } else {
            $data['wx_openid'] = $user['openid'];
            $data['access_token'] = $access_token;
            $data['acid']=$acid;
            $data['nickname'] = "用户".date('Ymd').substr(md5(uniqid()),0,5);
            $this->save($data);
        }
        $res=$this->where(['wx_openid'=>$user['openid'],'acid'=>$acid])
            ->field('id,acid,create_time,update_time',true)
            ->find();
        return $res;

    }
}

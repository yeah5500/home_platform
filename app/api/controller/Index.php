<?php
/**
 * Created by PhpStorm.
 * User: Yeah
 * Date: 2018/10/22
 * Time: 14:41
 */
namespace app\api\controller;
use app\common\Base;
class Index extends Base {
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    /**
     * 首页数据
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
   public function index(){
       $data['article']=model('Article')
           ->where(['is_delete'=>0,'acid'=>$this->acid,'genre'=>1])
           ->order('sort asc')
           ->order('id desc')
           ->field('title,id')
           ->select();
       $data['banner']=model('Banner')
           ->where(['is_delete'=>0,'acid'=>$this->acid])
           ->order('sort asc')
           ->order('id desc')
           ->field('is_delete,acid',true)
           ->select();
       $data['classify']=model('Classify')
           ->where(['is_delete'=>0,'acid'=>$this->acid,'parent_id'=>0])
           ->order('sort asc')
           ->order('id desc')
           ->field('title,logo,id')
           ->select();
       return json(['code'=>0,'msg'=>'success','data'=>$data]);
   }

    /**
     * 分类详情
     * @param $parent_id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
   public function seed($parent_id){
       $data=model('classify')
           ->where(['parent_id'=>$parent_id,'acid'=>$this->acid,'is_delete'=>0,'is_status'=>1])
           ->field('is_delete,acid,content',true)
           ->order('sort asc')
           ->order('id desc')
           ->select();
       return json(['code'=>0,'data'=>$data]);
   }

    /**
     * 预约信息
     * @param $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
   public function class_details($id){
       $data=model('classify')
           ->where(['id'=>$id,'is_delete'=>0,'is_status'=>1])
           ->cache(true)
           ->find();
       if(empty($data))return json(['code'=>1,'msg'=>'内容不存在']);
       return json(['code'=>0,'msg'=>'success','data'=>$data]);
   }
   public function login($code){
       $res=$this->weChat->getOpenId($code);
       if($res&&isset($res['openid'])){
           $data=model('User')->login($this->acid,$res);
           if(empty($data))return json(['code'=>-1]);
           return json(['code'=>0,'data'=>$data]);
       }else return json(['code'=>1,'msg'=>$this->weChat->errMsg]);
   }
}
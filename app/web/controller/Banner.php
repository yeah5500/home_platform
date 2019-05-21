<?php
/**
 * Created by PhpStorm.
 * User: Yeah
 * Date: 2018/10/23
 * Time: 14:47
 */
namespace app\web\controller;
use app\common\Home;
class Banner extends Home{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    /**
     * @return \think\response\View
     * @throws \think\exception\DbException
     */
    public function index(){

        $data=model('Banner')
            ->where(['acid'=>$this->acid,'is_delete'=>0])
            ->order('id desc')
            ->paginate('',false,['query' =>$_GET]);
        return view('',['data'=>$data]);
    }
    public function add(){
        if(request()->isPost()){
            $data=[
                'acid'=>$this->acid,
                'image'=>input('image_url',null),
                'sort'=>input('sort',1)
            ];
            if(empty($data['image']))return json(['code'=>1,'msg'=>'请上传图片']);
            $res=model('Banner')->save($data);
            if(empty($res))return json(['code'=>1,'msg'=>'添加失败']);
            return json(['code'=>0,'msg'=>'添加成功','url'=>we7_url('banner/index')]);
        }else{
            return view();
        }
    }
    public function edit($id, $data = [], $rule = '')
    {
        if(request()->isPost()){
            $data=[
                'acid'=>$this->acid,
                'image'=>input('image_url',null),
                'sort'=>input('sort',1)
            ];
            if(empty($data['image']))return json(['code'=>1,'msg'=>'请上传图片']);
        }
        return parent::edit($id, $data, $rule = ''); // TODO: Change the autogenerated stub
    }
    public function delete($id)
    {
        return parent::delete($id); // TODO: Change the autogenerated stub
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Yeah
 * Date: 2018/10/29
 * Time: 9:35
 */
namespace app\api\controller;
use app\common\Api;

class Address extends Api{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }
    public function add(){
        $data=[
            'username'=>input('username'),
            'address'=>input('address'),
            'mobile'=>input('mobile'),
            'sex'=>input('sex'),
            'uid'=>$this->user->id,
            'acid'=>$this->acid,
            'detail_info'=>input('detail_info')
        ];
        $res=model('Address')
            ->validate([
                ['username','require','姓名不能为空'],
                ['address','require','地址不能为空'],
                ['mobile','require|regex:^[1][0-9]{10}$', '电话不能为空|请输入正确的手机号'],
                ['sex','require','性别不能为空'],
                ['detail_info','require','补充信息不能为空']
            ])
            ->save($data);
        if($res)return json(['code'=>0,'msg'=>'添加成功']);
        $err=model('Address')->getError();
        return json(['code'=>1,'msg'=>$err?$err:'添加失败']);
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index(){
        $data=model('Address')
            ->where(['uid'=>$this->user->id,'is_delete'=>0])
            ->order('is_default desc')
            ->order('update_time desc')
            ->select();
        return json(['code'=>0,'data'=>$data]);
    }

    /**
     * @param $id
     * @return \think\response\Json
     */
    public function delete($id){
        $res=model('Address')
            ->save(
                ['is_delete'=>1],
                ['id'=>$id,'uid'=>$this->user->id]
            );
        if($res)return json(['code'=>0,'msg'=>'删除成功']);
        return json(['code'=>1,'msg'=>'删除失败']);
    }
    public function set_default($id){
        model('Address')->save(['is_default'=>0],['uid'=>$this->user->id]);
        $res=model('Address')
            ->save(
                ['is_default'=>1],
                ['id'=>$id,'uid'=>$this->user->id]
            );
        if($res)return json(['code'=>0,'msg'=>'设置成功']);
        return json(['code'=>1,'msg'=>'设置失败']);
    }

    /**
     * @param null $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function details($id=null){
        $where['uid']=$this->user->id;
        if($id){
            $where['id']=$id;
        }else{
            $where['is_default']=1;
        }
        $data=model('Address')
            ->where($where)
            ->where(['is_delete'=>0])
            ->find();
        if(!$data)return json(['code'=>2,'msg'=>'地址不存在']);
        return json(['code'=>0,'msg'=>'查询成功','data'=>$data]);
    }
    public function edit($id){
        $data=[
            'username'=>input('username'),
            'address'=>input('address'),
            'mobile'=>input('mobile'),
            'sex'=>input('sex'),
            'uid'=>$this->user->id,
            'acid'=>$this->acid,
            'detail_info'=>input('detail_info')
        ];
        $res=model('Address')
            ->validate([
                ['username','require','姓名不能为空'],
                ['address','require','地址不能为空'],
                ['mobile','require|regex:^[1][0-9]{10}$', '电话不能为空|请输入正确的手机号'],
                ['sex','require','性别不能为空'],
                ['detail_info','require','补充信息不能为空']
            ])
            ->save($data,['id'=>$id]);
        if($res)return json(['code'=>0,'msg'=>'修改成功']);
        $err=model('Address')->getError();
        return json(['code'=>1,'msg'=>$err?$err:'修改失败']);
    }
}
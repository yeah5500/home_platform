<?php
/**
 * Created by PhpStorm.
 * User: Yeah
 * Date: 2018/11/13
 * Time: 13:50
 */
namespace app\common\model;
use think\Model;


class Order extends Model{
    protected $autoWriteTimestamp=true;
    protected $updateTime=false;
    protected $type=[
        'pay_time'=>'timestamp:Y-m-d H:i',
        'apply_delete_time'=>'timestamp:Y-m-d H:i',
        'booking_time'=>'timestamp:Y-m-d H:i',
        'serve_time'=>'timestamp:Y-m-d H:i'
    ];
}
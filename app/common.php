<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function we7_url($params,$group=[]){
    $url = './index.php?';
    if(isset($_GET['c'])){
        $url .= "c={$_GET['c']}&";
    }
    if(isset($_GET['a'])){
        $url .= "a={$_GET['a']}&";
    }
    if(isset($_GET['do'])){
        $url .= "do={$_GET['do']}&";
    }
    if(isset($_GET['m'])){
        $url .= "m={$_GET['m']}&";
    }
    if(isset($_GET['version_id'])){
        $url .="version_id={$_GET['version_id']}&";
    }
    if (!empty($params)) {
        $params=['s'=>$params];
        $queryString = http_build_query($params, '', '&');
        $url .= $queryString;
    }

    if(count($group)){
        $queryString = http_build_query($group, '', '&');
        $url .= '&'.$queryString;
    }
    return $url;
}
function go_url($url){
    if(!is_null($url)){
        header("location:{$url}");
    }
}
function strip($str,$count=0){
    $str=strip_tags($str);
    $str=str_replace(['&nbsp;','<br/>'],'',$str);
    if($count){
        return $str;
    }else{
        return substr($str,0,$count);
    }
}

function error_back($msg){
    exit("<script>alert('{$msg}');history.back(-1)</script>");
}

function make_token(){
     $str = md5(uniqid(md5(microtime(true)),true));
     $str = sha1($str);  //加密
     return $str;
}
function weekTime($minute=30,$day=3){
    $date = [];
    $stamp=60*$minute;//秒
    $sum=(86400/$stamp)-1;//一天分割成多少秒
    $count=0;
    for ($i=0; $i<=$day-1; $i++){
        $strToTime=strtotime( '+' . $i .' days', strtotime(date('Y-m-d',time()+1800)));
        $w=date('w',$strToTime);
        $weekArr=['星期天','星期一','星期二','星期三','星期四','星期五','星期六'];//星期数组
        $name=$weekArr[$w];//获取今天星期几

        $date[$count]['name'] = $name;
        if($strToTime==strtotime(date('Y-m-d'))){
            $date[$count]['name'] ="{$name}(今天)";
            $date[$count]['time'] = [];
            $m=0;
            for ($j=0; $j<=$sum; $j++){
                $time1=strtotime(date('Y-m-d'));
                $time2=$time1+($stamp*$j);
                if($time2>time()){
                    $date[$count]['time'][$m]['name']=date('H:i',$time2);
                    $date[$count]['time'][$m]['stamp']=$time2;
                    $m++;
                }
            }

        }else{
            for ($j=0; $j<=$sum; $j++){
                $time1=strtotime(date('Y-m-d',$strToTime));
                $time2=$time1+($stamp*$j);
                $date[$count]['name']=$name."(".date('m-d',$time2).")";
                if($i==1) $date[$count]['name'] ="{$name}(明天)";
                if($i==2) $date[$count]['name'] ="{$name}(后天)";
                $date[$count]['time'][$j]['name']=date('H:i',$time2);
                $date[$count]['time'][$j]['stamp']=$time2;
            }
        }

        $count++;
    }
    return $date;

}
/**
 * 生成唯一的订单号 201808181435592323127
 * 2011-年日期
 * 08-月份
 * 09-日期
 * 11-小时
 * 12-分
 * 59-秒
 * 2323-微秒
 * 127-随机值
 * @return string
 */
function trade_no() {
    list($usec) = explode(" ", microtime());
    $usec = substr(str_replace('0.', '', $usec), 0 ,4);
    $str  = rand(1000,9999);
    return date("YmdHis").$usec.$str;
}


<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/10
 * Time: 14:23
 */

namespace app\card\service;


use app\card\model\SmallprogramFrom;
use app\card\model\SmallprogramUser;

class ActivePushMsg
{
    //执行推送方法
    public function push($type){

        //拿到Openid和用户名
        $SmallprogramUser=new SmallprogramUser();
        $SmallprogramFrom = new SmallprogramFrom();
        $CardReminding = new CardReminding();
         static $arr = [];
        $OpenidName = $SmallprogramUser->getOpenidName();

        //遍历openid推送
        foreach ($OpenidName as $k=>$v){
           $formId = $SmallprogramFrom->getFormId($v['userid']);
           $result = $CardReminding->PushMessage($v['openid'],$v['username'],$formId,$type);
           $arr[]=$result;
        }
        return $arr;
    }








}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 11:32
 */

namespace app\card\controller\v1;


use app\card\model\SmallprogramFrom;
use app\card\service\CardReminding;
use app\card\service\Token as TokenService;


class SendMessage
{
    //执行发送消息
    public function Send(){
        $data = input('post.');
        $uid=TokenService::getCurrentTokenVar('uid');
        $name=TokenService::getCurrentTokenVar('user');
        if (!$uid){
            return false;
        }
        $user = [
            'uid'=>$uid,
            'username'=>$name
        ];
        $cardReminding = new CardReminding();
        $smallprogramFrom = new SmallprogramFrom();
        $fromId = $smallprogramFrom->getFormId($uid);
        $result = $cardReminding->sendDeliveryMessage($user,$fromId,$data['type'],$data['address']);
        return $result;
    }

    //接收formId 存入数据库
    public function receive(){
        $uid=TokenService::getCurrentTokenVar('uid');
        $data = input('post.');
        $arr = [];
        foreach ($data as $k=>$v){
            $arr[] = [
                'form_id'=>$v['form_id'],
                'expice'=>$v['expice'],
                'userid'=>$uid
            ];
        }
        $smallprogramFrom = new SmallprogramFrom();
        $result = $smallprogramFrom->saveAll($arr);
        if (!$result){
            return false;
        }
        return true;
    }
}
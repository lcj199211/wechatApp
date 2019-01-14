<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/10
 * Time: 10:24
 */

namespace app\push\controller;

use app\card\model\SmallprogramFrom;
use app\card\service\ActivePushMsg;
use Workerman\Lib\Timer;

class PushMessage
{
    public function add_time(){
        Timer::add(60, function ()use (&$timer_id){
            $this->send();
        }, array(), true);

        // Timer::add(1, function ()use (&$timer_id){
        //     SmallprogramFrom::eliminateFormId();
        //     echo true;
        // }, array(), false);
    }

    public function send(){
       // $demo = new SmallprogramFrom();
//        $result = $ActivePushMsg->push(1);
//        echo json_encode($result);
        $time = date('H:i',time());
        switch ($time){
            case '08:20':
                $ActivePushMsg = new ActivePushMsg();
                $result = $ActivePushMsg->push(1);
                echo json_encode($result);
            break;
            case '12:03'://12点记得改回18点
                $ActivePushMsg = new ActivePushMsg();
                $result = $ActivePushMsg->push(2);
                echo json_encode($result);
            break;
            default:
                return;
        }
    }
}
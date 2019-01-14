<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 11:01
 */

namespace app\card\service;


use app\card\model\SmallprogramUser;

class CardReminding extends WxMessage
{
    const DELIVERY_MSG_ID = 'LHMXf57I6UNXhVyzB6BQ1rWDQR02rRjxcJjFkXZg6ns';// 小程序模板消息ID号
    const DELIVERY_MSG_OK_ID = 'J5xCBeDWbOw5ZoTBEQ9EbK7llPCKvhRlVCYiOlaXqow';
    //发送
    //$type= 1为上班打卡提醒;2为下班打卡提醒;3为上班打卡成功提醒;4为下班打卡成功提醒
    public function sendDeliveryMessage($data,$fromId,$type,$address=''){

        if ($type == 1 || $type == 2){
            $this->templateId = self::DELIVERY_MSG_ID;
        }else{
            $this->templateId = self::DELIVERY_MSG_OK_ID;
        }

        $this->page = 'pages/home/home';
        $this->data =$this->prepareMessageData($type,$address,$data['username']);
        $this->emphasisKeyWord='';

        $openId = $this->getUserOpenID($data['uid']);
        $result = parent::sendMessage($openId,$fromId);

        return $result;
    }

    //主动推送
    //$type= 1为上班打卡提醒;2为下班打卡提醒
    public function PushMessage($openId,$username,$fromId,$type){
        $this->templateId = self::DELIVERY_MSG_ID;
        $this->page = 'pages/home/home';
        $this->data =$this->prepareMessageData($type,'',$username);
        $this->emphasisKeyWord='';

        $result = parent::sendMessage($openId,$fromId);
        return $result;
    }

    private function prepareMessageData($type,$address,$username)
    {
        $dt = new \DateTime();
        switch ($type){
            case 1:
                $data = [
                    'keyword1' => [
                        'value' => $username.'您好！别忘记打卡了哟!',
                        'color' => '#0190a0'
                    ],
                    'keyword2' => [
                        'value' => '上班',
                        'color' => '#27408B'
                    ],
                    'keyword3' => [
                        'value' => '08:30'
                    ],
                    'keyword4' => [
                        'value' => '小程序打卡'
                    ],
                    'keyword5' => [
                        'value' => '请前往打卡地点打卡~~'
                    ]
                ];
                break;
            case 2:
                $data = [
                    'keyword1' => [
                        'value' => $username.'您好！别忘记打卡了哟!',
                        'color' => '#0190a0'
                    ],
                    'keyword2' => [
                        'value' => '下班',
                        'color' => '#27408B'
                    ],
                    'keyword3' => [
                        'value' => '12:00'
                    ],
                    'keyword4' => [
                        'value' => '小程序打卡'
                    ],
                    'keyword5' => [
                        'value' => '请前往打卡地点打卡~~'
                    ]
                ];
                break;
            case 3:
                $data = [
                    'keyword1' => [
                        'value' => '上班',
                        'color' => '#0190a0'
                    ],
                    'keyword2' => [
                        'value' => $dt->format("Y-m-d H:i")
                    ],
                    'keyword3' => [
                        'value' => $address
                    ],
                    'keyword4' => [
                        'value' => $username.'恭喜您打卡成功，请再接再厉！'
                    ]
                ];
                break;
            default:
                $data = [
                    'keyword1' => [
                        'value' => '下班',
                        'color' => '#0190a0'
                    ],
                    'keyword2' => [
                        'value' => $dt->format("Y-m-d H:i")
                    ],
                    'keyword3' => [
                        'value' => $address
                    ],
                    'keyword4' => [
                        'value' => $username.'恭喜您打卡成功，请再接再厉！'
                    ]
                ];   
        }
        return $data;
    }

    private function getUserOpenID($uid)
    {
        $user = SmallprogramUser::where('userid',$uid)->find();

        return $user->openid;
    }
}
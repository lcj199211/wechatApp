<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 10:24
 */

namespace app\card\service;


use app\lib\exception\SendMessageException;

class WxMessage
{
    private $sendUrl;
    protected $templateId;
    protected $page;
    protected $data;
    protected $emphasisKeyWord;

    function __construct()
    {
        $accessToken = new AccessToken();
        $token = $accessToken->get();
        $this->sendUrl = sprintf(config('wx.send_message_url'), $token);
    }

    protected function sendMessage($openId,$formId){
        $data = [
            'touser'=>$openId,
            'template_id' => $this->templateId,
            'page'=>$this->page,
            'form_id'=>$formId,
            'data'=>$this->data,
            'emphasis_keyword'=>$this->emphasisKeyWord
        ];

        $result = curl_post_raw($this->sendUrl,$data);

        $result = json_decode($result,true);
        if ($result['errcode'] == 0){
            return $result;
        }else{
            return $result;
//            throw new SendMessageException([
//                'errorCode'=> $result['errmsg']
//            ]);
        }
    }
}
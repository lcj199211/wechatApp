<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/30
 * Time: 16:56
 */

namespace app\card\service;


use app\card\model\SmallprogramUser;
use app\lib\exception\LoginException;
use app\lib\exception\WxOpenIdException;

class UserSmallProgram
{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    function __construct($code){
        $this->code = $code;
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl  = sprintf(config('wx.login_url'), $this->wxAppID,$this->wxAppSecret,$this->code);
    }

    //发起请求
    public function get($userid){
        $result = curl_get($this->wxLoginUrl);
        $wxResult = json_decode($result,true);
        if (empty($wxResult)){
            throw new WxOpenIdException();
        }else{
            $loginFail = array_key_exists('errcode',$wxResult);
            if ($loginFail){
                throw new WxOpenIdException();
            }else{
                $result = $this->inspectOpenid($wxResult,$userid);
                return $result;
            }
        }
    }

    //保存openid以及获取到的用户id
    public function inspectOpenid($wxResult,$userid){
        $data = [
            'openid'=>$wxResult['openid'],
            'userid'=>$userid
        ];

        $openidResult = SmallprogramUser::getOpenid($data['openid']);

        if (!$openidResult){
            $result = SmallprogramUser::create($data);
            if (!$result){
                throw new LoginException([
                    'msg' => '网络繁忙，登录失败',
                    'errorCode' => 20001
                ]);
            }
            return true;
        }

        return true;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/6
 * Time: 10:53
 */

namespace app\card\controller\v1;
use app\card\service\Token as TokenService;
use app\lib\exception\TokenException;


class UcUser
{
    /*
     * 用户登录接口
     */
    public function getUser(){
        $user=TokenService::getCurrentTokenVar('user');
        if (!$user){
            return [
                'tokenVerify'=>false
            ];
        }
        return [
            'user'=>$user
        ];
    }
}
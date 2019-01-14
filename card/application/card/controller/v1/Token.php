<?php
namespace app\card\controller\v1;
use app\card\service\AppToken as AppTokenService;
use app\card\service\Token as TokenService;
use app\lib\exception\LoginException;
use app\lib\exception\TokenException;

class token
{
    //登录获取token接口
    public function getToken($username,$password,$code)
    {
       $app = new AppTokenService();
       $token = $app->get($username,$password,$code);
       if (!$token){
           throw new LoginException();
       }else{
           return [
               'token' => $token
           ];
       }
    }

    //验证token是否合法
    public function verifyToken($token=''){
        if (!$token){
            throw new TokenException();
        }
        $isVerify = TokenService::validationToken($token);
        if (!$isVerify){
            throw new TokenException();
        }
        return json([
            'isVerify'=>true
        ]);
    }
}

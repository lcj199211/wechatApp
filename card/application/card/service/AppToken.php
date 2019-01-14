<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/4
 * Time: 10:15
 */
namespace app\card\service;

use app\card\model\UcUser as UserModel;

class AppToken extends Token
{
    //验证用户名以及返回token
    public function get($username,$password,$code){

        $app = UserModel::check($username,$password);
        if (!$app){
            return false;
        }else{
            $smallProgramService = new UserSmallProgram($code);
            $wxResult = $smallProgramService->get($app->userid);
            if (!$wxResult){
                return false;
            }
            $uid=$app->userid;
            $user=$app->username;
            $groupingID = $app->admin_id;
            $value=[
                'uid'=>$uid,
                'user'=>$user,
                'grouping_id'=>$groupingID
            ];
            $token=$this->saveToCache($value);
            return $token;
        }

    }
//
//    //所属用户组id检测
//    private function groupingCheck($groupingID){
//
//    }

    //存入token令牌
    private function saveToCache($value){
        $token = self::generateToken();
        $expire_in = config('setting.token_expire_in');
        $result = cache($token,json_encode($value),$expire_in);
        if (!$result){
            abort('10005','服务器缓存异常');
        }
        return $token;
    }
}
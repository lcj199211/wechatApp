<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/4
 * Time: 9:37
 */
namespace app\card\model;


class UcUser extends BaseModel
{

    //查询用户是否存在
    public static function check($username,$password){
        $user = self::where('username',$username)->find();
        if ($user){
            if ($user['password']==$password){
                return $user;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    //查询补卡申请人
    public static function getApplyUser($id){
        $data = self::field('username')->find($id);
        return $data;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/4
 * Time: 10:48
 */

namespace app\card\service;


use think\Cache;
use think\Request;

class Token
{
    //生成token令牌
    public static function generateToken(){
        //32个字符组成一组随机字符串
        $randChars = getRandChar(32);
        //用三组字符串，进行MD5加密
        $timestamp = $_SERVER['REQUEST_TIME'];
        //salt 盐
        $salt = config('setting.token_salt');
        return md5($randChars.$timestamp.$salt);
    }

    //验证token
    public static function validationToken($token){
        $verify = Cache::get($token);
        if ($verify){
            return true;
        }else{
            return false;
        }
    }

    //通用token拿用户id以及相关数据
    public static function getCurrentTokenVar($key){
        $token = Request::instance()->header('token');
        $vars = Cache::get($token);
        if (!$vars){
            return false;
        }else{
            if (!is_array($vars)){
                $vars = json_decode($vars,true);
            }
            if (array_key_exists($key,$vars)){
                return $vars[$key];
            }else{
                return false;
            }
        }
    }
}
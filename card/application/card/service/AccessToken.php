<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 9:56
 */

namespace app\card\service;


use app\lib\exception\AccessTokenException;

class AccessToken
{
    private $tokenUrl;
    const TOKEN_CACHED_KEY = 'access';
    const TOKEN_EXPIRE_IN = 7000;

    //构造access_token接口

    function __construct()
    {
        $url = config('wx.access_token_url');
        $url = sprintf($url,config('wx.app_id'),config('wx.app_secret'));
        $this->tokenUrl = $url;
    }
    // 获取access_token
    // 但微信access_token接口 2000次/天
    public function get(){
        $token = $this->getFromCache();
        if (!$token){
            return $this->getFromWxServer();
        }
        return $token;
    }

    //从缓存获取access_token
    private function getFromCache()
    {
        $token = cache(self::TOKEN_CACHED_KEY);
        if(!$token){
            return null;
        }
        return $token;
    }

    //请求新的access_token
    private function getFromWxServer()
    {
        $token = curl_get($this->tokenUrl);
        $token = json_decode($token,true);

        if (!$token){
            throw new AccessTokenException();
        }

        if(!empty($token['errcode'])){
            throw new AccessTokenException([
                'msg'=>$token['errmsg']
            ]);
        }

        $this->saveToCache($token['access_token']);

        return $token['access_token'];
    }

    public function saveToCache($token)
    {
        cache(self::TOKEN_CACHED_KEY,$token,self::TOKEN_EXPIRE_IN);
    }
}
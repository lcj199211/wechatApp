<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/7
 * Time: 16:51
 */

namespace app\card\model;


class SmallprogramUser extends BaseModel
{
    public static function getOpenid($openid){
        $result = self::where('openid',$openid)->field('openid')->find();
        return $result;
    }

    //关联user表查询所有openid以及用户名

    public function getOpenidName(){
        $result = $this->alias('a')->join('uc_user b','a.userid=b.userid')->field('openid,username,a.userid')->select();

        return $result;
    }

}
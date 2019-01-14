<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/18
 * Time: 9:59
 */

namespace app\card\model;


class OwnFieldPersonnel extends BaseModel
{
    public static function getStatus($uid){
        $condition['field_personnel_userid'] = $uid;
        $condition['field_personnel_status'] = 2;
        $result = self::where($condition)->field('field_personnel_id')->find();
        return $result;
    }

    public function dataResult($id){
        $result = self::where('field_personnel_id','=',$id)->find();
        return $result;
    }
}
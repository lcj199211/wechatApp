<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/13
 * Time: 14:36
 */

namespace app\card\model;


class HrAttendApply extends BaseModel
{

    //根据ID查消息
    public static function getMsgID($uid,$date){

        $msg = self::where('hr_attend_apply_auditId','=',$uid)->
        where('hr_attend_apply_data','like',$date.'%')->order('hr_attend_apply_id desc')->select();

        return $msg;
    }

    //获取当前审批消息

    public static function getApplyMsg($applyID){
        $result = self::where('hr_attend_apply_id','=',$applyID)->find();
        return $result;
    }



}
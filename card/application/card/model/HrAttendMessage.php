<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/13
 * Time: 14:36
 */

namespace app\card\model;


class HrAttendMessage extends BaseModel
{

    public function items(){
        return $this->hasOne('HrAttendApply','hr_attend_apply_id','hr_attend_message_applyId')
            ->field('hr_attend_apply_id,hr_attend_typeCard,hr_attend_status');
    }


    //获取当前通知消息
    public static function getMessage($uid,$date){
        $data = self::with('items')->where('hr_attend_message_recipient','=',$uid)->
        where('hr_attend_message_date','like',$date.'%')->order('hr_attend_message_id desc')->select();

        return $data;
}
}
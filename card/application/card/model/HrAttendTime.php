<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/14
 * Time: 15:16
 */

namespace app\card\model;


class HrAttendTime extends BaseModel
{
    //返回时间配置
    public function getTimeConfigure($value){
        $result = self::where('hr_attend_time_id','=',$value)->find();
        return $result;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: gd
 * Date: 2018/8/12
 * Time: 16:12
 */

namespace app\card\service;


use app\card\model\HrAttendPlace as HrAttendPlaceModel;
use app\card\model\ProjectPlace;
use app\card\model\UcUser as UcUserModel;

class AppAddress
{
    public static function verificationAddress($pois,$uid){
        $arr = [];

        foreach ($pois as $k=>$v){
            $arr[] = [
                'title'=>$v['title'],
                'address'=>$v['address']
            ];
        };

        //判断是否是项目经理
        $center_id = UcUserModel::where('userid',$uid)->value('center_id');
        if ($center_id == 36){
            $result = ProjectPlace::getAddress($arr);
        }else{
            $result = HrAttendPlaceModel::getAddress($arr);
        }
        if (!$result){
            return false;
        }
        return true;
    }
}
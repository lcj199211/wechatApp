<?php
/**
 * Created by PhpStorm.
 * User: gd
 * Date: 2018/8/12
 * Time: 16:06
 */

namespace app\card\model;


class HrAttendPlace extends BaseModel
{
    protected $validate=['hr_attend_place_name'];

    public static function getAddress($pois){
        $place = self::field('hr_attend_place_name')->select();
        $posResult = [];
        foreach ($place as $k=>$v){
            foreach ($pois as $k2=>$v2){
                $pos1 = strpos($v2['title'],$v['hr_attend_place_name']);
                $pos2 = strpos($v2['address'],$v['hr_attend_place_name']);
                if ($pos1 !== false || $pos2 !== false){
                    $posResult[]=$v['hr_attend_place_name'];
                    break;
                }
            }
        }
//        foreach ($arr as $k=>$v){
//
//            $pos = strpos($arr,$v);
//
//            if ($pos !== false){
//                $posResult[]=$v;
//                break;
//            }
//
//        }
        return $posResult;

    }
}
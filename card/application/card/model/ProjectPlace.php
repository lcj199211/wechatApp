<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/15
 * Time: 9:28
 */

namespace app\card\model;


class ProjectPlace extends BaseModel
{
    protected $validate=['place_name'];

    public static function getAddress($pois){
        $place = self::field('place_name')->select();
        $posResult = [];
        foreach ($place as $k=>$v){
            foreach ($pois as $k2=>$v2){
                $pos1 = strpos($v2['title'],$v['place_name']);
                $pos2 = strpos($v2['address'],$v['place_name']);
                if ($pos1 !== false || $pos2 !== false){
                    $posResult[]=$v['place_name'];
                    break;
                }
            }
        }

        return $posResult;

    }
}
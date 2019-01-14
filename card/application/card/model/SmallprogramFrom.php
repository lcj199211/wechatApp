<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 17:41
 */

namespace app\card\model;

class SmallprogramFrom extends BaseModel
{
    //获取formId,获取一条有效的

//    public function getFormId(){
//        $time = time()+60;
//        $result = array();
//        $this->chunk(10,function ($formIds) use (&$result,&$time){
//            foreach ($formIds as $k=>$v){
//                //判断是否小于当前时间，小于就删除 否则 赋值删除然后退出
//                if ($v['expice'] < $time){
//                    self::destroy($v['id']);
//                }else{
//                    $result = $v['form_id'];
//                    self::destroy($v['id']);
//                    return;
//                }
//            }
//        });
//
//        return $result;
//    }

    public function getFormId($uid){
        $time = time()+60;
        $result = $this->where('expice','>',$time)->
        where('userid','=',$uid)->field('id,form_id')->find();
        self::destroy($result['id']);
        return $result['form_id'];
    }


    //清除过期formId
    public static function eliminateFormId(){
        $timestamp = $_SERVER['REQUEST_TIME'];
        $result = self::where('expice','<',$timestamp)->delete();
        return $result;
    }
}
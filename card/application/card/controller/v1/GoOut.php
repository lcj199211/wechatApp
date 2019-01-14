<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/18
 * Time: 9:24
 */

namespace app\card\controller\v1;

use app\card\service\Token as TokenService;
use app\card\service\AppFieldPersonnel as AppFieldPersonnelService;
class GoOut
{
    //员工外勤
    public function getGoOut(){
        $uid=TokenService::getCurrentTokenVar('uid');
        if (!$uid){
            return false;
        }
        $AppFieldPersonnel= new AppFieldPersonnelService();
        $result = $AppFieldPersonnel->getOutgoing($uid);
        return $result;
    }

    //添加考勤记录
    public function addCardData(){
        $timestamp = $_SERVER['REQUEST_TIME'];
        $uid=TokenService::getCurrentTokenVar('uid');
        if (!$uid){
            return [
                'tokenVerify'=>false
            ];
        }
        $adminId=TokenService::getCurrentTokenVar('grouping_id');
        $data = input('post.');
        $AppFieldPersonnel= new AppFieldPersonnelService();
        $result = $AppFieldPersonnel->addAttendanceData($data['id'],$timestamp,$uid,$adminId);
        return $result;
    }
}
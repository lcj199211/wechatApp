<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/7
 * Time: 11:22
 */

namespace app\card\controller\v1;
use app\card\model\HrAttend as HrAttendModel;
use app\card\model\HrAttend;
use app\card\model\HrAttendApply;
use app\card\model\HrAttendMessage;
use app\card\service\Token as TokenService;
use app\card\service\AppCard as AppCardService;
use app\card\service\AppSupplement as AppSupplementService;
use think\Request;


class Card
{
    //上班打卡接口
    public function getPunchTheClock($address){
        $timestamp = $_SERVER['REQUEST_TIME'];
        $ip = Request::instance()->ip();
        $user=TokenService::getCurrentTokenVar('uid');
        if(!$address){
            return [
                'address'=>false
            ];
        }
        if (!$user) {
            return json([
                'tokenVerify'=>$user
            ]);
        }
        //调用打卡主方法
        $AppCard = AppCardService::getCard($user,$timestamp,$address,$ip);
        return $AppCard;
    }

    //下班打卡接口
    public function getOffWork($address){
        $timestamp = $_SERVER['REQUEST_TIME'];
        $ip = Request::instance()->ip();
        $user=TokenService::getCurrentTokenVar('uid');
        if(!$address){
            return [
                'address'=>false
            ];
        }
        if (!$user) {
            return json([
                'tokenVerify'=>$user
            ]);
        }
        //调用打卡主方法
        $AppCard = AppCardService::getOffCard($user,$timestamp,$address,$ip);
        return $AppCard;
    }

    //请求当天打卡记录
    public function getCardRecord(){
        $timestamp = $_SERVER['REQUEST_TIME'];
        $date = date('Y-m-d',$timestamp);
        $user=TokenService::getCurrentTokenVar('uid');
        if (!$user) {
            return false;
        }
        //当日打卡记录
        $getCard = new HrAttendModel();
        $result = $getCard->getCard($user,$date);
        return $result;
    }

    //申请补卡接口
    public function getSupplementCard($checkedValue,$dateTime,$contentText){
        $uid=TokenService::getCurrentTokenVar('uid');
        $groupingId=TokenService::getCurrentTokenVar('grouping_id');
        if (!$uid) {
            return json([
                'tokenVerify'=>false
            ]);
        }
        $AppSupplement = new AppSupplementService();

        $result = $AppSupplement->SupplementCard($uid,$groupingId,$checkedValue,$dateTime,$contentText);

        return $result;
    }

    //获取审批消息接口
    public function getNews(){
        $timestamp = $_SERVER['REQUEST_TIME'];
        $date = date('Y-m',$timestamp);
        $uid=TokenService::getCurrentTokenVar('uid');
        if (!$uid) {
            return [
                'tokenVerify'=>false
            ];
        }
        $result = HrAttendApply::getMsgID($uid,$date);
        if (empty($result)){
            return false;
        }
        return $result;

    }

    //获取通知消息接口
    public function getNotice(){
        $timestamp = $_SERVER['REQUEST_TIME'];
        $date = date('Y-m',$timestamp);
        $uid=TokenService::getCurrentTokenVar('uid');
        if (!$uid) {
            return [
                'tokenVerify'=>false
            ];
        }
        $result = HrAttendMessage::getMessage($uid,$date);

        if (empty($result)){
            return false;
        }
        return $result;

    }

    //获取统计数据
    public function getStatisticalData(){
        $timestamp = $_SERVER['REQUEST_TIME'];
        $uid=TokenService::getCurrentTokenVar('uid');
        if (!$uid) {
            return [
                'tokenVerify'=>false
            ];
        }
        $result = HrAttend::getStatistical($uid,$timestamp);

        return $result;
    }

    //获取当前提交月份打卡天数
    public function getDaysData(){
        $data = input('get.');
        $timestamp = $_SERVER['REQUEST_TIME'];
        $uid=TokenService::getCurrentTokenVar('uid');
        if (!$uid) {
            return [
                'tokenVerify'=>false
            ];
        }
        $HrAttendM = new HrAttend();
        $result = $HrAttendM->getDaysRes($uid,$data,$timestamp);
        if (!$result){
            return false;
        }
        return $result;
    }

    //获取指定日期数据
    public function getAppointTimeData(){
        $data = input('get.');
        $uid=TokenService::getCurrentTokenVar('uid');
        if (!$uid) {
            return [
                'tokenVerify'=>false
            ];
        }
        $HrAttendM = new HrAttend();
        $result = $HrAttendM->where(
            [
                'hr_attend_date'=>$data['times'],
                'hr_attend_userId'=>$uid
            ]
        )->find();
        if (!$result){
            return false;
        }
        return $result;

    }
}
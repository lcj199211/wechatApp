<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/7
 * Time: 11:41
 */

namespace app\card\service;


use app\card\model\HrAttend as HrAttendModel;
use app\card\model\HrAttendTime;

class AppCard
{
    //上班打卡主方法
    public static function getCard($uid,$timestamp,$address,$ip){
            $time = self::timeConfig($uid);//获取时间配置
            $WorkTime = self::WorkTime($timestamp,$time);
            $worker = new SupplementCardData();
            $team = $worker->checkProjectTeam($uid);
            $attend = [
                'hr_attend_userId'      =>  $uid,
                'hr_attend_date'        =>  $WorkTime['date'],
                'hr_attend_startWork'   =>  $WorkTime['time'],
                'hr_attend_workingState'=>  $WorkTime['msg'],
                'hr_attend_workAddress'     =>  $address,
                'hr_attend_workIp'          =>  $ip,
                'hr_attend_projectId'=>$team['supplierMod_worker_projectId'],
                'hr_attend_workTeamId'=>$team['supplierMod_worker_workTeamId'],
                'hr_attend_WTLength' => '0.5'
            ];
            $HrAttend = new HrAttendModel();
            $HrAttend->save($attend);
            $record = $HrAttend->currentCard($uid,$WorkTime['date'],$WorkTime['time']);
            if (!$record){
                return false;
            }else{
                return  true;
            }

    }

    //上班打卡时间逻辑判断
    private static function WorkTime($timestamp,$time){
        $GoToWorkTimeH  = $time['hr_attend_time_workhour'];//演示上班
        $GoToWorkTimeI  = $time['hr_attend_time_workminute'];//演示上班
        $time = date('H:i:s',$timestamp);
        $date = date('Y-m-d',$timestamp);
        $timeH = intval(date('H',$timestamp));
        $timeI = intval(date('i',$timestamp));

        $WorkTime =[
            'date' => $date,
            'time' => $time,
            'msg'  => ''
        ];
        if ($timeH<=$GoToWorkTimeH){
            if($timeH==$GoToWorkTimeH&&$timeI<=$GoToWorkTimeI){
                $WorkTime['msg'] = '打卡成功';
            }elseif ($timeH<$GoToWorkTimeH){
                $WorkTime['msg'] = '打卡成功';
            }else{
                $WorkTime['msg'] = '迟到打卡';
            }
        }else{
            $WorkTime['msg'] = '迟到打卡';
        }

        return  $WorkTime;
    }


    //下班打卡接口

    public static function getOffCard($uid,$timestamp,$address,$ip){
        $time = self::timeConfig($uid);//获取时间配置
        $WorkTime = self::restWorkTime($timestamp,$time);
        $worker = new SupplementCardData();
        $team = $worker->checkProjectTeam($uid);
        $overtime = self::overtimeVerification($timestamp,$time);//判断是否加班
        $attend = [
            'hr_attend_userId'      =>  $uid,
            'hr_attend_date'        =>  $WorkTime['date'],
            'hr_attend_knockOff'   =>  $WorkTime['time'],
            'hr_attend_restState'=>  $WorkTime['msg'],
            'hr_attend_offWorkAddress'     =>  $address,
            'hr_attend_underIp'          =>  $ip,
            'hr_attend_control' => 1,
            'hr_attend_projectId'=>$team['supplierMod_worker_projectId'],
            'hr_attend_workTeamId'=>$team['supplierMod_worker_workTeamId'],
            'hr_attend_WTLength' => '1',
            'hr_attend_overtime' =>$overtime['hr_attend_overtime'],
            'hr_attend_overtimeDate'=>$overtime['hr_attend_overtimeDate']
        ];
        $HrAttend = new HrAttendModel();
        $ToUpdate = $HrAttend->currentOffCard($attend);
        if (!$ToUpdate){
            return false;
        }else{
            return  true;
        }
    }

    //下班打卡时间逻辑判断
    private static function restWorkTime($timestamp,$time){
        $cardTimeH  = $time['hr_attend_time_hour'];//演示下班
        $cardTimeI  = $time['hr_attend_time_minute'];//演示下班
        $time = date('H:i:s',$timestamp);
        $date = date('Y-m-d',$timestamp);
        $timeH = intval(date('H',$timestamp));
        $timeI = intval(date('i',$timestamp));
        $WorkTime =[
            'date' => $date,
            'time' => $time,
            'msg'  => ''
        ];
        if (date('w',$timestamp) == 6){
            if ($timeH>=12){
                $WorkTime['msg'] = '打卡成功';
            }else{
                $WorkTime['msg'] = '早退';
            }
        }else{
            if ($timeH>=$cardTimeH){
                if($timeH==$cardTimeH&&$timeI>=$cardTimeI){
                    $WorkTime['msg'] = '打卡成功';
                }elseif ($timeH>$cardTimeH){
                    $WorkTime['msg'] = '打卡成功';
                }else{
                    $WorkTime['msg'] = '早退';
                }
            }else{
                $WorkTime['msg'] = '早退';
            }
        }

        return  $WorkTime;
    }

    //判断是否加班(如果是则统计加班时长)
    public static function overtimeVerification($timestamp,$time){
        $date = date('Ymd',$timestamp);
        $result = strtotime($date .$time['hr_attend_time_hour'].':'.$time['hr_attend_time_minute']) + 3600;
        if ($timestamp>$result){
            $second = $timestamp-$result;
            $overtime = intval($second/60);
            return [
              'hr_attend_overtime' => '1',
              'hr_attend_overtimeDate'=>$overtime
            ];
        }
        return [
            'hr_attend_overtime' => '0',
            'hr_attend_overtimeDate'=>'0'
        ];
    }

    //获取时间配置
    public static function timeConfig($userId){
        $Supplement = new SupplementCardData();
        $UserGroupResult = $Supplement->judgementUserGroup($userId);
        //为true时是办公室人员
        if ($UserGroupResult){
            $value = 1;
        }else{
            $value = 2;
        }
        $AttendTime = new HrAttendTime();
        $timeConfigure = $AttendTime->getTimeConfigure($value);
        return $timeConfigure;
    }

}
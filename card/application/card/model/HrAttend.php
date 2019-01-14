<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/7
 * Time: 11:36
 */

namespace app\card\model;


use app\card\service\SupplementCardData;

class HrAttend extends BaseModel
{
    //查询当前上班打卡时间和记录是否已经成功存入
    public function currentCard($uid,$date,$time){
        $data = [
            'hr_attend_userId'=> $uid,
            'hr_attend_date'=> $date,
            'hr_attend_startWork'=>$time
        ];

        $record= self::where($data)->find();

        return $record;
    }
    //查询当前下班打卡时间和记录是否已经成功存入
    public function currentOffCard($data){
        $getData = [
            'hr_attend_userId'=> $data['hr_attend_userId'],
            'hr_attend_date'=> $data['hr_attend_date']
        ];
        $record= self::where($getData)->value('hr_attend_id');
        $ToUpdate = self::where('hr_attend_id','=',$record)->update($data);
        return $ToUpdate;
    }

    //查询当天打卡记录
    public function getCard($uid,$date){
        $data = [
            'hr_attend_userId'=> $uid,
            'hr_attend_date'=> $date,
        ];
        $record= self::where($data)->find();
        if (!$record){
            return false;
        }
        return $record;
    }

    //统计数据
    public static function getStatistical($uid,$timestamp){

        $date = date('Y-m',$timestamp);
        $statisticsDays = self::where('hr_attend_userId','=',$uid)->
        where('hr_attend_date','like',$date.'%')->count();

        $beLateDays = self::where('hr_attend_userId','=',$uid)->
        where('hr_attend_workingState','=','迟到打卡')->
        where('hr_attend_date','like',$date.'%')->count();

        $MoveForwardCardDays = self::where('hr_attend_userId','=',$uid)->
        where('hr_attend_restState','=','早退')->
        where('hr_attend_date','like',$date.'%')->count();

        return [
            'statistics'=>$statisticsDays,
            'beLate'=>$beLateDays,
            'MoveForwardCard'=>$MoveForwardCardDays
        ];
    }

    //根据提交月份统计数据
    public function getDaysRes($uid,$data){
        if ($data['currentMonth']<10){
            $data['currentMonth']='0'.$data['currentMonth'];
        }
        $time = $data['currentYear'].'-'.$data['currentMonth'];
        //获取打卡数据
        $dayData = self::where('hr_attend_userId',$uid)
            ->where('hr_attend_date','like',$time.'%')->field('hr_attend_date,hr_attend_workingState,hr_attend_restState')->select();
        $result = $this->testingDaysData($dayData);
        return $result;
    }

    public function testingDaysData($data){
        $arr = [];
        foreach ($data as $k=>$v){
            //异常数据
            if ($v['hr_attend_workingState'] == '迟到打卡' || $v['hr_attend_restState'] == '早退' || $v['hr_attend_restState'] == null){
                    $days = substr($v['hr_attend_date'],-2);
                    $arr['abnormalData'][] = ltrim($days,'\0');
            }else{
                //否则就是正常
                $days = substr($v['hr_attend_date'],-2);
                $arr['normalData'][] = ltrim($days,'\0');
            }
        }


        return $arr;
    }
}
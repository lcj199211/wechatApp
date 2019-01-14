<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/18
 * Time: 10:25
 */

namespace app\card\service;


use app\card\model\HrAttend;
use app\card\model\OwnFieldPersonnel;
use think\Db;
use think\Log;

class AppFieldPersonnel
{
    //检测员工是否外出
    public function getOutgoing($uid){
        //查是否有外出
        $personnelResult = OwnFieldPersonnel::getStatus($uid);
        if (!$personnelResult){
            return false;
        }
        return $personnelResult;
    }

    //考勤
    public function addAttendanceData($fieldPersonnelId,$timestamp,$uid,$adminId){
        $currentDate = date('Y-m-d H:i:s',$timestamp);
        $OwnFieldPersonnelM = new OwnFieldPersonnel();
        $personnelResult = $OwnFieldPersonnelM->dataResult($fieldPersonnelId);
        if (!$personnelResult){
            return false;
        }

        $addCardResult = $this->JudgeTheNumberOfDays($timestamp,$personnelResult['start_time'],$uid,$adminId);

        if ($addCardResult){
            $result = $personnelResult->save([
                'field_personnel_status'=>4,
                'end_time'=>$currentDate
            ]);
            if (!$result){
                return false;
            }
        }else{
           $personnelResult->save([
                'field_personnel_status'=>4,
                'end_time'=>$currentDate
           ]);
        }

        return true;

    }

    //判断天数和添加相关数据
    protected function JudgeTheNumberOfDays($timestamp,$personnelDate,$uid,$adminId){
        $oddNumbers = strtotime($personnelDate);
        $currentDate = date('Y-m-d',$timestamp);
        $oddDate = date('Y-m-d',$oddNumbers);
        if ($currentDate == $oddDate){
            return false;
        }
        $currentSecond = strtotime($currentDate);
        $oddSecond = strtotime($oddDate);
        $dateTime = [];
        for ($start = $oddSecond; $start < $currentSecond; $start += 24 * 3600) {
            $time = date("Y-m-d", $start);
            $dateTime[]=$time;
        }
        $result = $this->addCard($uid,$dateTime,$adminId);

        return $result;
    }

    //添加打卡数据
    protected function addCard($userId,$dateTime,$adminId){
        $adminId?$value = 1:$value = 2;
        $SupplementCardData = new SupplementCardData();
        $timeConfig = $SupplementCardData->timeConfigure($value);
        $checkProject = $SupplementCardData->checkProjectTeam($userId);
        //判断外出当天是否有记录
        $judgeResult = $this->judgeUpdate($userId,$dateTime[0]);
        //如果有数据就直接删除
        if ($judgeResult){
            HrAttend::destroy($judgeResult['hr_attend_id']);
        }
        Db::startTrans();// 启动事务
        try{
            $dataRes = [];
            $data=[
                'hr_attend_userId'=>$userId,
                'hr_attend_date'=>null,
                'hr_attend_startWork'=>$timeConfig['workTime'],
                'hr_attend_knockOff'=>$timeConfig['workOffTime'],
                'hr_attend_remarks'=>'外出考勤自动补卡',
                'hr_attend_workAddress'=> '广州本土中心',
                'hr_attend_offWorkAddress'=> '广州本土中心',
                'hr_attend_workingState'=> '外出考勤',
                'hr_attend_restState'=> '外出考勤',
                'hr_attend_control'=>1,
                'hr_attend_WTLength'=>1,
                'hr_attend_projectId'=>$checkProject['supplierMod_worker_projectId'],
                'hr_attend_workTeamId'=>$checkProject['supplierMod_worker_workTeamId']
            ];
            foreach ($dateTime as $k=>$v){
                $dataRes[]=$data;
            }
            foreach ($dateTime as $k=>$v){
                $dataRes[$k]['hr_attend_date'] = $v;
            }
            $hrAttendM = model('HrAttend');
            $hrAttendM->saveAll($dataRes);
            Db::commit();//提交事务
            return true;
        }catch (\Exception $ex){
            Db::rollback();//回滚事务
            Log::error($ex);
            return false;
        }
    }

    //判断外出当天是否是全天外出
    public function judgeUpdate($userId,$time){
        $getData = [
            'hr_attend_userId'=> $userId,
            'hr_attend_date'=> $time
        ];
        $result = HrAttend::where($getData)->find();

        return $result;
    }
}
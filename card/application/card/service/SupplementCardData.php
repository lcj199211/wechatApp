<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/14
 * Time: 10:26
 */

namespace app\card\service;


use app\card\model\HrAttend;
use app\card\model\HrAttendTime;
use app\card\model\SuppliermodWorkerTable;
use app\card\model\UcUser;

class SupplementCardData
{
    //判断打卡类型生成数据
    public function judgeData($data){
        //判断用户是哪个用户组的
        $userId = $data['hr_attend_apply_attendId'];
        $UserGroupResult = $this->judgementUserGroup($userId);
            //为true时是办公室人员
        if ($UserGroupResult){
            $value = 1;
        }else{
            $value = 2;
        }
        $team = $this->checkProjectTeam($userId);
        $time = $this->timeConfigure($value);
        $result = $this->editOffice($data,$time,$team);
        return $result;

    }
    //判断用户组（存在就是办公室人员，否则就是工人）
    public function judgementUserGroup($userId){
        $result = UcUser::where('userid','=',$userId)->value('admin_id');
        if ($result){
            return true;
        }
        return false;
    }

    //补卡数据更新
    public function editOffice($data,$time,$team){

        //获取补卡类型
        $typeCard = $data['hr_attend_typeCard'];
        //组装打卡数据查询
        $conditionaArr = [
            'hr_attend_userId' => $data['hr_attend_apply_attendId'],
            'hr_attend_date' => $data['hr_attend_apply_data']
        ];
        $attendData = HrAttend::get($conditionaArr);
        if ($typeCard == '全天'){
            $arrDay = [
                'hr_attend_userId'=>$data['hr_attend_apply_attendId'],
                'hr_attend_date'=>$data['hr_attend_apply_data'],
                'hr_attend_startWork' => $time['workTime'],
                'hr_attend_knockOff' => $time['workOffTime'],
                'hr_attend_applyId' => $data['hr_attend_apply_id'],
                'hr_attend_workingState'=> '已补卡',
                'hr_attend_restState' => '已补卡',
                'hr_attend_WTLength' => '1',
                'hr_attend_control' => '1',
                'hr_attend_workAddress'=>'广州本土中心',
                'hr_attend_offWorkAddress'=>'广州本土中心',
                'hr_attend_projectId'=>$team['supplierMod_worker_projectId'],
                'hr_attend_workTeamId'=>$team['supplierMod_worker_workTeamId']
            ];
        }
        if ($typeCard == '上班'){
            $arrDay = [
                'hr_attend_userId'=>$data['hr_attend_apply_attendId'],
                'hr_attend_date'=>$data['hr_attend_apply_data'],
                'hr_attend_startWork' => $time['workTime'],
                'hr_attend_applyId' => $data['hr_attend_apply_id'],
                'hr_attend_workingState'=> '已补卡',
                'hr_attend_WTLength' => '0.5',
                'hr_attend_workAddress'=>'广州本土中心'
            ];
        }
        if ($typeCard == '下班'){
            //为防止直接申请下班补卡，判断如果上班时间不存在将返回不执行
            if (!$attendData){
                return [
                    'error'=>'审批失败，该补卡内容异常，请驳回补卡人重新申请'
                ];
            }
            $arrDay = [
                'hr_attend_userId'=>$data['hr_attend_apply_attendId'],
                'hr_attend_date'=>$data['hr_attend_apply_data'],
                'hr_attend_knockOff' => $time['workOffTime'],
                'hr_attend_applyId' => $data['hr_attend_apply_id'],
                'hr_attend_restState' => '已补卡',
                'hr_attend_WTLength' => '1',
                'hr_attend_control' => '1',
                'hr_attend_offWorkAddress'=>'广州本土中心'
            ];
        }
        if ($attendData){
             $result = $attendData->save($arrDay);
            if (!$result){
                return false;
            }
            return true;
        }

        $attendM = model('HrAttend');
        $result= $attendM->save($arrDay);

        if (!$result){
            return false;
        }
        return true;
    }
    //获取时间配置
    public function timeConfigure($value){
        $AttendTime = new HrAttendTime();
        $timeConfigure = $AttendTime->getTimeConfigure($value);
        if ($timeConfigure['hr_attend_time_minute'] == 0){
            $timeConfigure['hr_attend_time_minute'] = '00';
        }
        if ($timeConfigure['hr_attend_time_workminute'] == 0){
            $timeConfigure['hr_attend_time_workminute'] = '00';
        }
        $offTime = $timeConfigure['hr_attend_time_hour'].':'.$timeConfigure['hr_attend_time_minute'].':'.'00';
        $workTime= $timeConfigure['hr_attend_time_workhour'].':'.$timeConfigure['hr_attend_time_workminute'].':'.'00';
        return [
            'workOffTime' => $offTime,
            'workTime'=> $workTime
        ];
    }

    //查用户是否有班组以及项目，有就存没就不存
    public function checkProjectTeam($uid){
        $result = SuppliermodWorkerTable::where('supplierMod_worker_userId',$uid)->field('supplierMod_worker_projectId,supplierMod_worker_workTeamId')->find();
        if (!$result){
            return [
               'supplierMod_worker_projectId'=>null,
                'supplierMod_worker_workTeamId'=>null
            ];
        }
        return $result;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12
 * Time: 10:30
 */

namespace app\card\controller\v1;


use app\card\model\HrAttend;
use app\card\model\HrAttendApply;
use app\card\model\UcUser;
use app\card\service\SupplementCardData;

class Examination
{
    //获取审批数据
    public function getApplyData($applyID){
        $applys = HrAttendApply::getApplyMsg($applyID);

        $applyUser = UcUser::getApplyUser($applys['hr_attend_apply_attendId']);
        $arr = [
            'apply'=>$applys,
            'user'=>$applyUser
        ];
        return $arr;
    }

    //审批

    public function ExaminationAndApproval(){
        $data = input('post.');
        if ($data['status']==2){
            $result = $this->ExaminationStatus($data);
            return $result;
        }
        $modifyData = HrAttendApply::getApplyMsg($data['applyId']);
        $supplementCardData = new SupplementCardData();
        $result = $supplementCardData->judgeData($modifyData);
        if ($result === true){
            $result = $this->ExaminationStatus($data);
            return $result;
        }
        return $result;
    }
    //更改审批状态
    public function ExaminationStatus($data){
        $result=HrAttendApply::where('hr_attend_apply_id','=',$data['applyId'])->update(['hr_attend_status'=>$data['status']]);
        if ($result == 0){
            return false;
        }
        return true;
    }
}
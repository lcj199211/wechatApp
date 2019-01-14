<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/13
 * Time: 14:37
 */

namespace app\card\service;


use app\card\model\HrAttendApply as HrAttendApplyModel;

use  app\card\model\HrAttendMessage;
use app\card\model\HrGrouping;
use app\card\model\SuppliermodWorkerTable;
use app\card\model\UcUser;


class AppSupplement
{
    /*
     * 补卡申请
     */
    public function SupplementCard($uid,$groupingId,$checkedValue,$dateTime,$contentText){
        //查用户组管理员id
        //如果不是办公室的就是项目组的
        if($groupingId){
            $adminID = $groupingId;
        }else{
            $adminID = $this->getProjectAdminId($uid);
        }
        $data = [
            'hr_attend_apply_attendId'=>$uid,
            'hr_attend_typeCard'=>$checkedValue,
            'hr_attend_apply_data'=>$dateTime,
            'hr_attend_apply_reason'=>$contentText,
            'hr_attend_apply_auditId'=>$adminID
        ];
        $addApply = new HrAttendApplyModel();
        $addApply->save($data);//插入
        $applyID =$addApply->hr_attend_apply_id;//获取自增id(申请单id)
        if ($applyID){
            $result = $this->addMessage($applyID,$uid,$dateTime);//执行消息添加
            return $result;
        }else{
            return false;
        }
    }
    //查项目组管理员ID
    protected function getProjectAdminId($uid){
        $projectManager = SuppliermodWorkerTable::getProject($uid);
        $adminID = UcUser::where('username','='
            ,$projectManager['items']['construct_project_leader'])->field('userid')->find();
        return $adminID->userid;
    }

    //执行消息添加
    protected function addMessage($applyID,$uid,$dateTime){
        $data = [
            'hr_attend_message_recipient' => $uid,
            'hr_attend_message_applyId' => $applyID,
            'hr_attend_message_date' => $dateTime
        ];
        $msgM = new HrAttendMessage();
        $result =$msgM->save($data);
        if (!$result){
            return false;
        }
        return true;
    }

}
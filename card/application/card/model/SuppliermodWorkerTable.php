<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/11
 * Time: 11:19
 */

namespace app\card\model;


class SuppliermodWorkerTable extends BaseModel
{
    //关联模型
    public function items(){

        return $this->hasOne('ConstructProjectTable','construct_project_id','supplierMod_worker_projectId')
            ->field('construct_project_id,construct_project_leader');
    }

    public static function getProject($uid){
        $result = self::with('items')->where('supplierMod_worker_userId','=',$uid)->find();
        return $result;
    }
}
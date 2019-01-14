<?php
/**
 * Created by PhpStorm.
 * User: gd
 * Date: 2018/8/12
 * Time: 15:58
 */

namespace app\card\controller\v1;

use app\card\model\SuppliermodWorkerTable;
use app\card\service\AppAddress as AppAddressService;
use app\card\service\Token as TokenService;

class Address
{
    /*
     * 地址检测
     */
    public function getAddress($pois){
        $uid = TokenService::getCurrentTokenVar('uid');
        if (!$uid){
            return [
                'tokenVerify'=>false
            ];
        }
        if (!$pois){
            return [
                'address'=>false
            ];
        }
        //判断是不是老板,是就直接跳过验证
        if ($uid == 54){
            return true;
        }

        $result = AppAddressService::verificationAddress($pois,$uid);

        return $result;

    }


    public function getPad(){
        $timestamp = $_SERVER['REQUEST_TIME'];
        return cache('access');
        //return  date('w',$timestamp);
    }
}
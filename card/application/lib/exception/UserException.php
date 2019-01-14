<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/1
 * Time: 11:52
 */

namespace app\lib\exception;


class UserException extends BaseException
{
    public $code = 401;
    public $msg  = '尝试获取到的用户名不存在';
    public $errorCode = 20001;
}
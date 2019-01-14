<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 10:17
 */

namespace app\lib\exception;


class AccessTokenException extends BaseException
{
    public $code = 400;
    public $msg  = '微信内部错误，获取access_token异常';
    public $errorCode = 40001;
}
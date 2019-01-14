<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/1
 * Time: 11:06
 */

namespace app\lib\exception;


class LoginException extends BaseException
{
    public $code = 400;
    public $msg  = '用户名或密码错误';
    public $errorCode = 20000;
}
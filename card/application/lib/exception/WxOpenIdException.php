<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/1
 * Time: 11:06
 */

namespace app\lib\exception;


class WxOpenIdException extends BaseException
{
    public $code = 400;
    public $msg  = '微信内部错误，请稍后重试';
    public $errorCode = 30001;
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8
 * Time: 10:57
 */

namespace app\lib\exception;


class SendMessageException extends BaseException
{
    public $code = 400;
    public $msg  = '模板消息发送失败';
    public $errorCode = 30001;
}
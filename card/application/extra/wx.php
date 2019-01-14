<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/30
 * Time: 17:05
 */

return [
    'app_id'     => 'wxa30684c40c756e56',
    'app_secret' => 'e683a4ab0b66e2f55eb079c1a70ebf5a',
    'login_url'  => 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code',
    'access_token_url' => "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s",
    'send_message_url' => "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=%s"
];
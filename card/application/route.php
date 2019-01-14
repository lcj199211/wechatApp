<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;
Route::post('card/:version/token', 'card/:version.token/getToken');
Route::post('card/:version/token/verify', 'card/:version.token/verifyToken');

Route::post('card/:version/User', 'card/:version.UcUser/getUser');

Route::post('card/:version/getClock', 'card/:version.card/getPunchTheClock');
Route::post('card/:version/OffWork', 'card/:version.card/getOffWork');
Route::get('card/:version/getCard', 'card/:version.card/getCardRecord');
Route::post('card/:version/getAddress', 'card/:version.address/getAddress');
Route::post('card/:version/supplement', 'card/:version.card/getSupplementCard');
Route::get('card/:version/statistics', 'card/:version.Card/getStatisticalData');

Route::get('card/:version/getMsg', 'card/:version.card/getNews');
Route::get('card/:version/getNotice', 'card/:version.card/getNotice');
Route::get('card/:version/getApply/:applyID', 'card/:version.examination/getApplyData');

Route::post('card/:version/approval', 'card/:version.examination/ExaminationAndApproval');

Route::post('card/:version/goOuts', 'card/:version.GoOut/getGoOut');
Route::post('card/:version/outgoing', 'card/:version.GoOut/addCardData');

Route::post('card/:version/sendmsg', 'card/:version.SendMessage/Send');
Route::post('card/:version/receive', 'card/:version.SendMessage/receive');

Route::get('card/:version/getext', 'card/:version.address/getPad');
Route::get('card/:version/getCardDays', 'card/:version.card/getDaysData');

Route::get('card/:version/getAppointTime', 'card/:version.card/getAppointTimeData');
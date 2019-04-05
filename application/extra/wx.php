<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/27
 * Time: 13:00
 */

return [
	'app_id' => 'wxf093d9b588da269c',
	'app_secret' => '3acfe74ff04efc64af877c97fa1606aa',
	'login_url' => 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code',

	//微信获取access_token的url地址
	'access_token_url' => "https://api.weixin.qq.com/cgi-bin/token?"."grant_type=client_credential&appid=%s&secret=%s",
];


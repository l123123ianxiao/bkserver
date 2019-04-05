<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/27
 * Time: 14:49
 */

namespace app\lib\exception;


class WeChatException extends BaseException
{
	public $code = 400;
	public $msg = '微信服务器接口调用失败';
	public $errorCode = 999;
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/27
 * Time: 16:22
 */

namespace app\lib\exception;


class TokenException extends BaseException
{
	public $code = 401;
	public $msg = 'Token已过期或者为无效Token';
	public $errorCode = 10001;

}
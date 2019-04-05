<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/29
 * Time: 6:03
 */

namespace app\lib\exception;


class ForbiddenException extends BaseException
{
	public $code = 403;
	public $msg = '权限不够';
	public $errorCode = 10001;
}
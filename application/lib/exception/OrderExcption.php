<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/29
 * Time: 19:37
 */

namespace app\lib\exception;


class OrderExcption extends BaseException
{
	public $code = 404;
	public $msg = '订单不存在，请检查ID';
	public $errorCode = 80000;
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/25
 * Time: 21:24
 */

namespace app\lib\exception;


class CategoryException extends BaseException
{
	public $code = 404;
	public $msg = '指定的类目不存在，请检查参数';
	public $errorCode =50000;
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/27
 * Time: 12:36
 */

namespace app\api\validate;


class TokenGet extends BaseValidate
{
	protected $rule = [
		'code' => 'require|isNotEmpty'
	];

	protected $message = [
		'code' => '无法使用空参数获取token'
	];
}
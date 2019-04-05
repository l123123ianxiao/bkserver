<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/21
 * Time: 1:38
 */

namespace app\api\validate;


class AppTokenGet extends BaseValidate
{
	protected $rule = [
		'ac' => 'require|isNotEmpty',
		'se' => 'require|isNotEmpty'
	];
}
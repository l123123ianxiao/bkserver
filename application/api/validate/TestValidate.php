<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/1
 * Time: 19:54
 */

namespace app\api\validate;


use think\Validate;

class TestValidate extends Validate
{
	protected $rule = [
		'name' => 'require|max:10',
		'email' => 'email'
	];
}
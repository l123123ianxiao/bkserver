<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/24
 * Time: 18:00
 */

namespace app\api\validate;


class PagingParameter extends BaseValidate
{
	protected $rule = [
		'page' => 'isPositiveInteger',
		'size' => 'isPositiveInteger',
	];

	protected $message = [
		'page' => '分页参数必须是正整数',
		'size' => '分页参数必须是正整数',
	];
}
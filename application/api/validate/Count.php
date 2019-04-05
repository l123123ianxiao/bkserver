<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/25
 * Time: 19:59
 */

namespace app\api\validate;



class Count extends BaseValidate
{
	protected $rule = [
		'count' => 'isPositiveInteger|between:1,15'
	];
}
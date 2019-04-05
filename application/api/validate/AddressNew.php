<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/28
 * Time: 11:44
 */

namespace app\api\validate;


class AddressNew extends BaseValidate
{
 protected $rule = [
 	 'name' => 'require|isNotEmpty',
	// 'mobile' => 'require|isMobile',
	 'province' => 'require|isNotEmpty',
	 'city' => 'require|isNotEmpty',
	 'country' => 'require|isNotEmpty',
	 'detail' => 'require|isNotEmpty',
 ];
}
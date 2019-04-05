<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/28
 * Time: 16:27
 */

namespace app\api\model;


class UserAddress extends BaseModel
{
	protected $hidden = [
		'id','delete_time','user_id'
	];
}
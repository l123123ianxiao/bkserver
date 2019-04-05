<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/27
 * Time: 12:46
 */

namespace app\api\model;


class User extends BaseModel
{

	public function address(){
		return $this->hasOne('UserAddress','user_id','id');
	}


	public static function getByOpenId($openid){
		$user = self::where('openid','=',$openid)->find();
		return $user;
	}



}
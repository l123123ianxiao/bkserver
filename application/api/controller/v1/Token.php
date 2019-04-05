<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/27
 * Time: 12:34
 */

namespace app\api\controller\v1;
use app\api\service\AppToken;
use app\api\validate\AppTokenGet;
use app\lib\exception\ParameterException;
use app\api\service\Token as TokenService;


use app\api\service\UserToken;
use app\api\validate\TokenGet;

class Token
{
	public function getToken($code=''){
		(new TokenGet())->goCheck();
		$ut = new UserToken($code);
		$token = $ut->get();
		return [
			'token' => $token
		];
	}
	/*
	*第三方应用获取令牌
	 * @url /app_token?
	 * @POST ac=:ac se=:secret
	*/
	public function getAppToken($ac='',$se=''){
		(new AppTokenGet())->gocheck();
		$app = new AppToken();
		$token = $app->get($ac,$se);
		return[
			'token' => $token
		];
	}



	public function verifyToken($token=''){
		if(!$token){
			throw new ParameterException([
				'token不允许为空'
			]);
			$valid = TokenService::verifyToken($token);
			return [
				'isValid' => $valid
			];
		}
	}
}
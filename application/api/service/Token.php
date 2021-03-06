<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/27
 * Time: 15:55
 */

namespace app\api\service;


use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

class Token
{
	//生成令牌
	public static function generateToken(){
		//32字符组成随机字符串
		$randChars = getRandChar(32);
		//防伪造:用三组字符串进行md5加密
		$timestamp = $_SERVER['REQUEST_TIME'];
		//salt
		$salt = config('secure.token_salt');
		return md5($randChars.$timestamp.$salt);
	}

	public static function getCurrentTokenVar($key){
		$token = Request::instance()->header('token');
		$vars = Cache::get($token);
		if(!$vars){
			throw new TokenException();
		}else{
			if(!is_array($vars)){
				$vars =json_decode($vars,true);
			}
			if(array_key_exists($key,$vars)){
				return $vars[$key];
			}else{
				throw new Exception('尝试获取的Token变量并不存在');
			}
		}
	}

	public static function getCurrentUid(){
		//token
		$uid = self::getCurrentTokenVar('uid');
		return $uid;

	}

	//用户与cms管理员都可以访问的权限
	public static function needPrimaryScope(){
		$scope = self::getCurrentTokenVar('scope');
		if($scope){
			if($scope == ScopeEnum::User){
				return true;
			}else{
				throw new ForbiddenException();
			}
		}else{
			throw new TokenException();
		}

	}

	//只有用户可以访问的权限
	public static function needExclusiveScope(){
		$scope = self::getCurrentTokenVar('scope');
		if($scope){
			if($scope == ScopeEnum::User){
				return true;
			}else{
				throw new ForbiddenException();
			}
		}else{
			throw new TokenException();
		}
	}

	//被检测uid是否和令牌一致
	public static function isValidOperate($checkedUID){
		if(!$checkedUID){
			throw new Exception('验证身份信息时，必须传入一个被检测的UID');
		}
		$currentOperateUID = self::getCurrentUid();
		if($checkedUID == $currentOperateUID){
			return true;
		}
		return false;
	}

	public static function verifyToken($token){
		$exist = Cache::get($token);
		if($exist){
			return true;
		}else{
			return false;
		}
	}
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/27
 * Time: 12:47
 */

namespace app\api\service;


use app\api\model\User;
use app\lib\enum\ScopeEnum;
use app\lib\exception\TokenException;
use app\lib\exception\WeChatException;
use think\Exception;
use app\api\model\User as UserModel;

class UserToken extends Token
{
	protected $code;
	protected $wxAppID;
	protected $wxAppSecret;
	protected $wxLoginUrl;

	function __construct($code)
	{
		$this->code =$code;
		$this->wxAppID = config('wx.app_id');
		$this->wxAppSecret = config('wx.app_secret');
		$this->wxLoginUrl = sprintf(config('wx.login_url'),$this->wxAppID,$this->wxAppSecret,$this->code);
	}

	public function get(){
		$result = curl_get($this->wxLoginUrl);
		//加入true参数转换成数组（原为对象）
		$wxResult = json_decode($result,true);
		if(empty($wxResult)){
			throw new Exception('获取session_key及openID时异常，微信内部错误');
		}else{
			$loginFail = array_key_exists('errorcode',$wxResult);
			if($loginFail){
				$this->processLoginError($wxResult);
			}else{
				return $this->grantToken($wxResult);

			}

		}

	}

	private function processLoginError($wxResult){
		throw new WeChatException([
			'msg' => $wxResult['errmsg'],
			'errorcode' => $wxResult['errcode']

		]);
	}

	//生成令牌
	private function grantToken($wxResult){
		//拿到openid
		//检验数据库openid是否已经存在
		//存在不处理，不存在新增一条user记录
		//生成令牌，准备缓存数据，写入缓存
		//把令牌返回到客户端
		//key:令牌
		//value:wxResult,uid,scope

		$openid = $wxResult['openid'];
		$user = UserModel::getByOpenId($openid);
		if($user){
			$uid = $user->id;
		}else{
			$uid = $this->newUser($openid);
		}
		$cachedValue = $this->prepareCacheValue($wxResult,$uid);
		$token = $this->saveToCache($cachedValue);
		return $token;
	}

	//写入缓存
	private function saveToCache($cachedValue){
		$key = self::generateToken();
		$value = json_encode($cachedValue);
		$expire_in = config('setting.token_expire_in');

		$request = cache($key,$value,$expire_in);
		if(!$request){
			throw new TokenException([
				'msg' => '服务器缓存异常',
				'errerCode' => 10005
			]);
		}
		return $key;
	}

	//准备缓存数据
	private function prepareCacheValue($wxResult,$uid){
		$cachedValue = $wxResult;
		$cachedValue['uid'] = $uid;
		//scope=16 代表app用户的权限数值
		$cachedValue['scope'] = ScopeEnum::User;
		return $cachedValue;
	}

	private function newUser($openid){
		$user = UserModel::create([
			'openid' => $openid
		]);
		return $user->id;
	}

}
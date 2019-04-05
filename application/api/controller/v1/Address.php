<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/28
 * Time: 11:41
 */

namespace app\api\controller\v1;

use app\api\model\User as UserModel;
use app\api\model\UserAddress;
use app\api\service\Token as TokenService;
use app\api\validate\AddressNew;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;

class Address extends BaseController
{
	protected $beforeActionList = [
		'checkPrimaryScope' => ['only' => 'createOrUpdateAddress,getUserAddress']
	];

	public function getUserAddress(){
		$uid = TokenService::getCurrentUid();
		$userAddress = UserAddress::where('user_id',$uid)->find();
		if(!$userAddress){
			throw new UserException([
				'msg' => '用户地址不存在',
				'errorCode' => 60001
			]);
		}
		return $userAddress;
	}


	public function createOrUpdateAddress(){
	//	print_r($_POST['userName']);exit;
	$validate = new AddressNew();
	$validate->goCheck();

		//根据token获取用户uid
		//根据uid查找用户数据,判断用户是否存在,如果不存在抛出异常
		//获取用户从客户端提交来的地址信息
		//根据用户地址信息是否存在 从而判断是添加地址还是更新地址

		$uid = TokenService::getCurrentUid();
		$user = UserModel::get($uid);
		if(!$user){
			throw new UserException();
		}
		$dataArray = $validate->getDataByRule(input('post.'));
		// 根据规则取字段是很有必要的，防止恶意更新非客户端字段
		$userAddress = $user->address;
		if(!$userAddress){
			//新增

			$user->address()->save($dataArray);
		}else{
		//	print_r($dataArray);exit;
			// 存在则更新
			// fromArrayToModel($user->address, $data);
			// 新增的save方法和更新的save方法并不一样
			// 新增的save来自于关联关系
			// 更新的save来自于模型
			$user->address->save($dataArray);
		}
		return json(new SuccessMessage(),201);
	}
}
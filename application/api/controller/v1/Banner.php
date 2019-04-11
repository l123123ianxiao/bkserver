<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/1
 * Time: 19:10
 */

namespace app\api\controller\v1;


use app\api\model\BannerItem;
use app\api\validate\IDMustBePostiveInt;
use app\api\model\Banner as BannerModel;
use app\lib\exception\BannerMissException;
use app\api\service\BannerService;
use think\Exception;
use app\lib\exception\SuccessMessage;

class Banner
{
	/*
	 获取指定id的banner信息

	@url /banner/:id
	@http GET
	@id banner的id号
	*/
	 public function getBanner($id){

		 (new IDMustBePostiveInt())->goCheck();

		$banner = BannerModel::getBannerByID($id);

		if(!$banner){
			throw new BannerMissException();
		}
		return $banner;

	 }

	 public  function getAllBanner(){
	 	$banner = BannerItem::getAll();
	 	return $banner;
	 }

	public function deleteOne($id)
	{
		(new IDMustBePostiveInt())->goCheck();
		$result = BannerService::removeOne($id);
		if ($result == 1) {
			return json(new SuccessMessage([
				'code' => 200,
				'msg' => 'ok，请检查参数',
			]), 200);

		} else {
			return json(new SuccessMessage([
				'code' => 400,
				'msg' => '请求失败，请检查参数',
				'errorCode' => 80000
			]));
		}
	}

	public function addOne()
	{
		$data = input('post.');
		$result = BannerService::addBanner($data);
		if ($result->id) {
			return json(new SuccessMessage([
				'code' => 200,
				'msg' => '请求成功，请检查参数',
				'errorCode' => 0
			]));
		} else {
			throw BannerException();
		}
	}


	public function editOne()
	{
		$data = input('post.');
		//print_r($data);exit;
		$id = $data['id'];
		unset($data['id']);
		$result = BannerService::editThemeOne($id, $data);
		return $result;
	}
}
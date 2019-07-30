<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/30
 * Time: 12:36
 */

namespace app\api\model;



class Order extends BaseModel
{
	protected $hidden = [
		'user_id','delete_time','update_time'
	];
	protected  $autoWriteTimestamp = true;

	public function getSnapItemsAttr($value){
		if(empty($value)){
			return null;
		}
		return json_decode($value);
	}

	public function getSnapAddressAttr($value){
		if(empty($value)){
			return null;
		}
		return json_decode($value);
	}

	public static function getSummaryByUser($uid,$page=1,$size=15){
		$pagingData = self::where('user_id','=',$uid)
			->order('create_time desc')
			->paginate($size,true,['page' => $page]);
		return $pagingData;
	}

	public static function getSummaryByPage($page = 1,$size = 20){
		$pagingData = self::order('create_time desc')->paginate($size,true,['page'=> $page]);
		return $pagingData;
	}

	public function getProNameByOrder($orderID){
		$proName = self::where('id','=',$orderID)->value('snap_name');
		return $proName;
	}

}
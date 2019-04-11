<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/20
 * Time: 10:21
 */

namespace app\api\model;


use think\Db;

class BannerItem extends BaseModel
{
	protected $hidden = ['banner_id','delete_time','update_time'];
	public function img(){
		return $this->belongsTo('Image','img_id','id');
	}

	public static function getAll(){
		$banner = self::with('img')->select();
		return $banner;
	}


	public static function addOne($data){
		$result = self::create($data);
		return $result;
	}

	public static function updateOne($where,$data)
	{
		$result =  self::update($data,$where);
		return $result;
	}


	public static  function deleteone($id){
		return Db::table('banner_item')->where('id',$id)->delete();

	}
}
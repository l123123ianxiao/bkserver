<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/7
 * Time: 17:51
 */

namespace app\api\model;

use think\Model;

class Banner extends BaseModel
{
	protected $hidden = ['delete_time','update_time'];
	//banner关联banneritems 一对多
	public function items(){
		return $this->hasMany('BannerItem','banner_id','id');
	}
	public static function getBannerByID($id){
		//todo:根据bannaer ID号 获取banner信息
//	    $result = Db::query('select * from banner_item where banner_id =?',[$id]);
//		return $result;
//		$result = Db::table('banner_item')->where('banner_id','=',$id)->select();
//		return $result;

		$banner = self::with(['items','items.img'])->find($id);
		return $banner;
	}



}
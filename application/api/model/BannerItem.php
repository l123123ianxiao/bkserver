<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/20
 * Time: 10:21
 */

namespace app\api\model;

use think\Model;

class BannerItem extends BaseModel
{
	protected $hidden = ['id','img_id','banner_id','delete_time','update_time'];
	public function img(){
		return $this->belongsTo('Image','img_id','id');
	}
}
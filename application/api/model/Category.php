<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/25
 * Time: 21:04
 */

namespace app\api\model;


class Category extends BaseModel
{
	protected $hidden = ['delete_time','update_time','create_time'];
	public function img(){
		return $this->belongsTo('Image','topic_img_id','id');
	}
}
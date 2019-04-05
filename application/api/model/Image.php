<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/20
 * Time: 22:20
 */

namespace app\api\model;


class Image extends BaseModel
{
	protected $hidden = ['id','from','delete_time','update_time'];
//								  Url    img数组
	public function getUrlAttr($value,$data){
		return $this->prefixImgUrl($value,$data);
	}
}
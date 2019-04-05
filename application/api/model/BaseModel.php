<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/21
 * Time: 11:01
 */

namespace app\api\model;


use think\Model;

class BaseModel extends Model
{

	protected function prefixImgUrl($value,$data){
		$finalUrl = $value;
		if($data['from'] == 1){
			$finalUrl = config('setting.img_prefix').$value;
		}
		return $finalUrl;
	}

}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/21
 * Time: 1:48
 */

namespace app\api\model;


class ThirdApp extends BaseModel
{
	public static function check($ac,$se){
		$app = self::where('app_id','=',$ac)->where('app_secret','=',$se)->find();
		return $app;
	}
}
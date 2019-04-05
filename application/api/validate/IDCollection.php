<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/25
 * Time: 10:12
 */

namespace app\api\validate;


class IDCollection extends BaseValidate
{
	protected $rule =[
		'ids' => 'require|checkIDs'
	];

	protected $message = [
		'ids' => 'ids参数必须是以逗号分隔的多个正整数'
	];

	protected function checkIDs($value){
		 $values = explode(',',$value);
		 if(empty($values)){
		 	return false;
		 }
		 foreach($values as $id){
		 	if(!$this->isPositiveInteger($id)){
		 		return false;
			}
		 }
		 return true;
	}
}
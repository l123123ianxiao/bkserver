<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/1
 * Time: 19:10
 */

namespace app\api\controller\v2;


class Banner
{
	/*
	 获取指定id的banner信息

	@url /banner/:id
	@http GET
	@id banner的id号
	*/
	 public function getBanner($id){


		return 'this is version 2';

	 }
}
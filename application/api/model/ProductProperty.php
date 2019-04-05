<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/27
 * Time: 17:36
 */

namespace app\api\model;


class ProductProperty extends BaseModel
{
	protected $hidden = ['product_id','delete_time','update_time','product_id'];

}
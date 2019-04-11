<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/27
 * Time: 12:23
 */

namespace app\api\service;

use app\api\model\Category as CategoryModel;

class CategoryService
{
	public static function addCategory($data)
	{
		$add['name'] = $data['name'];
		$add['topic_img_id'] = $data['topic_img_id'];
		$add['description'] = $data['description'];
		$add['update_time'] = time();
        return CategoryModel::addOne($add);
	}


	public static function editCategoryOne($id, $data)
	{

		$where = array('id'=>$id);
		if(empty($data['imglist'])){
			$add['name'] = $data['name'];
			$add['description'] = $data['description'];
			$add['update_time'] = time();
			return CategoryModel::updateOne($where, $add);
		}else{
			$add['topic_img_id'] = $data['imglist'];
			$add['name'] = $data['name'];
			$add['description'] = $data['description'];
			$add['update_time'] = time();
			return CategoryModel::updateOne($where, $add);
		}



	}


    public static  function  removeOne($id){
        return CategoryModel::deleteone($id);
    }


    public static  function  getone($id){
        return CategoryModel::getCategoryDetail($id);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/25
 * Time: 21:04
 */

namespace app\api\controller\v1;
use app\api\model\Category as CategoryModel;
use app\lib\exception\CategoryException;

class Category
{
	public function getAllCategories(){
//		$categories = CategoryModel::with('img')->select();

		$categories = CategoryModel::all([],'img');
		if($categories->isEmpty()){
			throw new CategoryException();
		}
		return $categories;
	}
}
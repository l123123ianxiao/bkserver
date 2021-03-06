<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/23
 * Time: 15:26
 */

namespace app\api\model;

use think\Db;



class Product extends BaseModel
{
  protected $hidden = [
  	'delete_time','main_img_id','pivot','from','update_time'
  ];

	public function getMainImgUrlAttr($value,$data){
		return $this->prefixImgUrl($value,$data);
	}

	public static function getMostRecent($count){
		$products = self::limit($count)->order('create_time desc')->select();
		return $products;
	}

	public static function getProductsByCategoryID($categoryID){
		$products = self::where('category_id','=',$categoryID)->select();
		return $products;
	}

	public static function getSummaryByPage($page = 1,$size = 20){
		$pagingData = self::order('create_time desc')->paginate($size,true,['page'=> $page]);
		return $pagingData;
	}

	public function imgs(){
		return $this->hasMany('ProductImage','product_id','id');
	}

	public function properties(){
		return $this->hasMany('ProductProperty','product_id','id');
	}

	//闭包函数构建查询器
	public static function getProductDetail($id){
		$product = self::with(['imgs'=>function($query){
					$query->with(['imgUrl'])->order('order','asc');
					}])->with(['properties'])->find($id);
		return $product;
	}

	public static function addOne($data){
		$result = self::create($data);
		return $result;
	}

	public static function updateOne($where,$data)
	{
		$result =  self::update($data,$where);
		return $result;
	}

    public static  function deleteone($id){
       return Db::table('product')->where('id',$id)->delete();
    }


}
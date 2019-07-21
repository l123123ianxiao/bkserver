<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/27
 * Time: 12:23
 */

namespace app\api\service;

use app\api\model\Product as ProductModel;
use app\api\model\ProductImage;
use app\api\model\ProductProperty;
use app\api\model\ThemeProduct;

class ProductService
{
	public static function addProduct($data)
	{
		$i = 1;
		$add['name'] = $data['name'];
		$add['category_id'] = $data['category_id'];
		$add['price'] = $data['price'];
		$add['re_price'] = $data['re_price'];
		$add['stock'] = $data['stock'];
		$add['create_time'] = time();
		if (!empty($data['imgUrl'])) {
			$add['main_img_url'] = $data['imgUrl'];
		}

		if(array_key_exists("pname".$i,$data)){
//			for($i=1;$i++,$i<){
//
//			}
//			print_r($data["pname".$i]);exit;
			print_r($data);exit;
		}





		$result = ProductModel::addOne($add);
		if ($result->id && !empty($data['imglist'])) {
			self::addProductImage($result->id, $data['imglist']);
		}

		if ($result->id && !empty($data['propertieslist'])) {
			self::addProductProperties($result->id, $data['propertieslist']);
		}
		//print_r($data['theme_id']);exit;
		if($result->id && $data['theme_id'] > 0){
			self::addProductToTheme($result->id,$data['theme_id']);
		}
		return $result;
	}

	private static function addProductToTheme($productId,$themeId){
		$data[] = array('theme_id'=>$themeId,'product_id'=> $productId);
		$productTheme = new ThemeProduct();
		$productTheme->saveAll($data);
	}

	private static function addProductImage($productId, $imagelist)
	{
		try {
			$insertArr = null;
			$imagelist = explode(',', $imagelist);
            foreach ($imagelist as $index => $imgId) {

				$insertArr[] = array('img_id' => $imgId, 'product_id' => $productId, 'order' => $index + 1);

			}
			if ($insertArr) {
				array_shift($insertArr);
				$prductImage = new ProductImage();
				$prductImage->saveAll($insertArr);
			}
		} catch (\Exception $e) {
		}
	}

	private static function addProductProperties($productId, $propertieslist)
	{
		try {
			$insertArr = null;
			foreach ($propertieslist as $index => $properties) {
				$insertArr[] = array('product_id' => $productId, 'name' => $properties['name'], 'detail' => $properties['detail']);
			}
			if ($insertArr) {
				$productProperty = new ProductProperty();
				$productProperty->saveAll($insertArr);
			}
		} catch (\Exception $e) {
		}
	}

	public static function editOne($id, $data)
	{
		$where = array('id'=>$id);
		if(!empty($data['propertieslist']) &&  $data['propertieslist']!=null){
            ProductProperty::deleteProductProperty($id);
            self::addProductProperties($id, $data['propertieslist']);

        }
		if(!empty($data['imglist']) &&  $data['imglist']!=null){
            ProductImage::deleteProductImage($id);
            self::addProductImage($id, $data['imglist']);

        }

		$add['name'] = $data['name'];
		$add['category_id'] = $data['category_id'];
		$add['price'] = $data['price'];
		$add['re_price'] = $data['re_price'];
		$add['stock'] = $data['stock'];
		$add['from'] = 1 ;
		if (!empty($data['imgUrl'])) {
			$add['main_img_url'] = $data['imgUrl'];

		}
		return ProductModel::updateOne($where, $add);
	}



	public static function removeOne($id){
	    return ProductModel::deleteone($id);
    }
}
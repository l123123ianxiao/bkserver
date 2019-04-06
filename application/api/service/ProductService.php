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
use app\lib\exception\ProductException;
use app\lib\exception\SuccessMessage;

class ProductService
{
	public static function addProduct($data)
	{
//		print_r(input('post.'));exit;
		$add['name'] = $data['name'];
		$add['category_id'] = $data['category_id'];
		$add['price'] = $data['price'];
		$add['stock'] = $data['stock'];

		if (!empty($data['imgUrl'])) {
			$add['main_img_url'] = $data['imgUrl'];
		}


		$result = ProductModel::addOne($add);
		if ($result->id && !empty($data['imglist'])) {
			self::addProductImage($result->id, $data['imglist']);
		}

		return $result;
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
				$prductImage = new ProductImage();
				$prductImage->saveAll($insertArr);
			}
		} catch (\Exception $e) {
		}
	}

	public static function editOne($id, $data)
	{
		$where = array('id'=>$id);
		return ProductModel::updateOne($where, $data);

	}

	public static function removeOne($id){
	    return ProductModel::deleteone($id);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/25
 * Time: 19:55
 */

namespace app\api\controller\v1;

use app\api\model\Image;
use app\api\model\Product as ProductModel;
use app\api\model\ProductProperty as ProPreModel;
use app\api\service\ProductService;
use app\api\util\FilesUtil;
use app\api\validate\Count;
use app\api\validate\IDMustBePostiveInt;
use app\api\validate\PagingParameter;
use app\lib\exception\ProductException;
use app\lib\exception\SuccessMessage;
use think\Db;

class Product extends Base
{
	public function getRecent($count=15){
		(new Count())->goCheck();
		$products = ProductModel::getMostRecent($count);
		if($products->isEmpty()){
			throw new ProductException();
		}

		$products = $products->hidden(['summary']);
		return $products;
	}

	public function  getAllInCategory($id){
		(new IDMustBePostiveInt())->goCheck();
		$products = ProductModel::getProductsByCategoryID($id);
		if($products->isEmpty()){
			throw new ProductException();
		}
		$products = $products->hidden(['summary']);
		return $products;
	}

	public function getOne($id){
		(new IDMustBePostiveInt())->goCheck();
		$product = ProductModel::getProductDetail($id);
		if(!$product){
			throw new ProductException();
		}
		return $product;
	}

	public function getSummary($page = 1, $size = 20){
		(new PagingParameter())->goCheck();
		$pagingOrders = ProductModel::getSummaryByPage($page,$size);
		if($pagingOrders->isEmpty()){
			return [
				'current_page' => $pagingOrders->currentPage(),
				'data' => []
			];
		}
		$data= $pagingOrders->hidden(['delete_time','from'])->toArray();
		return [
			'current_page' => $pagingOrders->currentPage(),
			'data' => $data
		];
	}

	public function deleteOne($id){
		(new IDMustBePostiveInt())->goCheck();

		$result = ProductService::removeOne($id);
		//print_r($result);exit;
		if($result == 1){
			return json(new SuccessMessage([
				'code' => 200,
				'msg'=>'ok，请检查参数',
			]), 200);

		}else{
			return json(new SuccessMessage([
				'code' => 400,
				'msg'=>'请求失败，请检查参数',
				'errorCode' => 80000
			]));
		}
	}

	public function addOne(){

		$data = input('post.');
		//print_r($data);exit;
		$result = ProductService::addProduct($data);

		if($result->id){

			return json(new SuccessMessage([
				'code' => 200,
				'msg'=>'请求成功，请检查参数',
				'errorCode' => 0
			]));
		}else{
			throw ProductException();
		}
	}

	public function addProductImgUrl(){
		if($_FILES){
			if ($_FILES['upfile']['name'][0] == "") {
				exit("请上传文件！");
			}//判断第一个文件名是否为空
			$imgUrl = FilesUtil::UploadFiles($_FILES['upfile']);
			$imgID = $this->insertImg($imgUrl);
			return array('imagelist'=>implode(',',$imgID),'imgUrl'=>$imgUrl[0]);
		}
	}

	public function insertImg($imgUrl){

		if(is_array($imgUrl)){
			$img = new Image();
			foreach ($imgUrl as $k => $v){
				$img->data(['url' => $v]);
				$img->isUpdate(false)->save();
				$result[] = $img->id;
			}
		}
		return $result;
	}

	public function editOne(){
		$data = input('post.');

		$id = $data['id'];
		unset($data['id']);
        $this->checkEmptyField(array('imglist','imgUrl'),$data);

		$result = ProductService::editOne($id,$data);
		return $result;
	}

	public function getProductPre($id){
		$productPre = ProPreModel::getProPreById($id);
		return $productPre;
	}

}
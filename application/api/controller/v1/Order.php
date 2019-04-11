<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/29
 * Time: 6:17
 */

namespace app\api\controller\v1;

use app\api\service\Order as OrderService;
use app\api\service\Token as TokenService;
use app\api\validate\IDMustBePostiveInt;
use app\api\validate\OrderPlace;
use app\api\validate\PagingParameter;
use app\lib\exception\OrderExcption;
use app\api\model\Order as OrderModel;
use app\lib\exception\SuccessMessage;

class Order extends BaseController
{
	//用户选择商品后，向API提交包含他所选商品的相关新信息
	//API在接受到信息后，需要检查订单相关商品的库存量
	//有库存 把订单数据存入数据库 = 下单成功了 返回客户端消息 告诉客户端可以支付了
	//调用支付接口 进行支付
	//还需要再次进行库存量检查
	//服务器调用微信支付接口进行支付
	//小程序根据服务器返回的结果拉起微信支付
	//微信返回支付结果（异步）
	//成功 也需要进行库存量的检测
	//成功 进行库存量的扣除

	protected $beforeActionList = [
		'checkExclusiveScope' => ['only' => 'placeOrder'],
'checkPrimaryScope' => ['only' => 'getSummaryByUser','getDetail']
	];

	public function placeOrder(){
		(new OrderPlace())->goCheck();
		$products = input('post.products/a');
		$uid = TokenService::getCurrentUid();
		$order = new OrderService();
		$status = $order->place($uid,$products);
		//print_r($status);exit;
		return $status;
	}

	public function getSummaryByUser($page=1,$size=15){

		(new PagingParameter())->goCheck();
		$uid = \app\api\service\Token::getCurrentUid();
		$pagingOrders = \app\api\model\Order::getSummaryByUser($uid,$page,$size);
		if($pagingOrders->isEmpty()){
			return [
				'data' => [],
				'current_page' => $pagingOrders->currentPage()
			];
		}
		$data = $pagingOrders->hidden(['snap_items','snap_address','prepay_id'])->toArray();
		return [
			'data' => $data,
			'current_page' => $pagingOrders->currentPage()
		];
	}

	public function getDetail($id){
		(new IDMustBePostiveInt())->goCheck();
		$orderDetail = \app\api\model\Order::get($id);
		if(!$orderDetail){
			throw new OrderExcption();
		}
		return $orderDetail->hidden(['prepay_id']);
	}

	/*/获取所有订单简要信息（分页）、cms
	 * @param int $page
	 * @param int $size
	 * @return array
	 * $throw \app\lib\excption\ParameterException
	 * */
	public function getSummary($page = 1, $size = 20){
		(new PagingParameter())->goCheck();
		$pagingOrders = OrderModel::getSummaryByPage($page,$size);
		if($pagingOrders->isEmpty()){
			return [
				'current_page' => $pagingOrders->currentPage(),
				'data' => []
			];
		}
		$data= $pagingOrders->hidden(['snap_items','snap_address'])->toArray();
		return [
			'current_page' => $pagingOrders->currentPage(),
			'data' => $data
		];
	}

	public  function delivery($id){
		(new IDMustBePostiveInt())->goCheck();
		$order =  new OrderService();
		$success = $order->delivery($id);
		if($success){
			return new SuccessMessage();
		}
	}
}
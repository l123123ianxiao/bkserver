<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/29
 * Time: 11:03
 */

namespace app\api\service;


use app\api\model\OrderProduct;
use app\api\model\Product;
use app\api\model\UserAddress;
use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderExcption;
use app\lib\exception\UserException;
use app\api\model\Order as OrderModel;
use think\Db;
use think\Exception;


class Order
{
	//订单商品列表,也就是客户端传递过来的products参数
	protected $oProducts;
	//数据库查询出来的真实产品数据（库存量）
	protected  $products;

	protected  $uid;

	public function place($uid,$oProducts){
		//$oProducts跟$products 做比对
		//products从数据库查出来
		$this->oProducts = $oProducts;
		$this->products = $this->getProductsByOrder($oProducts);
		$this->uid = $uid;
		$status = $this->getOrderStatus();
		//保持客户端返回数据一致
		if(!$status['pass']){
			$status['order_id'] = -1;
			return $status;
		}
		//创建订单
		$orderSnap = $this->snapOrder($status);
		$order = $this->createOrder($orderSnap);
		$order['pass'] = true;
		return $order;
	}

	//生成订单
	private function createOrder($snap){
		Db::startTrans();
		try{
			$orderNo = $this->makeOrderNo();
			$order = new \app\api\model\Order();
			$order->user_id = $this->uid;
			$order->order_no = $orderNo;
			$order->total_price = $snap['orderPrice'];
			$order->total_count = $snap['totalCount'];
			$order->snap_img = $snap['snapImg'];
			$order->snap_name = $snap['snapName'];
			$order->snap_address = $snap['snapAddress'];
			$order->snap_items =  json_encode($snap['pStatus']);

			$order->save();

			$orderID = $order->id;
			$create_time = $order->create_time;
			foreach($this->oProducts as &$p){
				$p['order_id'] = $orderID;
			}
			$orderProduct = new OrderProduct();
			$orderProduct->saveAll($this->oProducts);
			Db::commit();
			return [
				'order_no' => $orderNo,
				'order_id' => $orderID,
				'order_time' => $create_time
			];
		}
		catch(Exception $ex){
			Db::rollback();
			throw $ex;
		}
	}

	//生成订单号
	public static function makeOrderNo()
	{
		$yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
		$orderSn =
			$yCode[intval(date('Y')) - 2018] . strtoupper(dechex(date('m'))) . date(
				'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
				'%02d', rand(0, 99));
		return $orderSn;
	}


	//生成订单快照(保存订单当前状态)
	private function snapOrder($status){
		$snap = [
			'orderPrice' => 0,
			'totalCount' => 0,
			'pStatus' => [],
			'snapAddress' =>null,
			'snapName' => '',
			'snapImg' => ''
		];

		$snap['orderPrice'] = $status['orderPrice'];
		$snap['totalCount'] = $status['totalCount'];
		$snap['pStatus'] = $status['pStatusArray'];
		$snap['snapAddress'] = json_encode($this->getUserAddress());
		$snap['snapName'] = $this->products[0]['name'];
		$snap['snapImg'] = $this->products[0]['main_img_url'];

		if(count($this->products)>1){
			$snap['snapName'] .='等';
		}
		return $snap;
	}

	private function getUserAddress(){
		$userAddress = UserAddress::where('user_id','=',$this->uid)->find();
		if(!$userAddress){
			throw new UserException([
				'msg' => '用户收货地址不存在,下单失败',
				'errorCode' => 60001
			]);
		}
		return $userAddress->toArray();
	}

	//获取订单中的商品ID，根据商品ID查询数据库，得到订到中所有商品的详细信息并进行库存比对（订单状态）
	private function getOrderStatus(){
		$status = [
			'pass' => true,
			'orderPrice' => 0,
			'totalCount' => 0,
			//订单内所有商品的详细信息
			'pStatusArray' => []
		];

		foreach($this->oProducts as $oProduct){
			$pStatus = $this->getProductStatus(
				$oProduct['product_id'],$oProduct['count'],$this->products
			);
			if(!$pStatus['haveStock']){
				$status['pass'] = false;
			}
			$status['orderPrice'] += $pStatus['totalPrice'];
			$status['totalCount'] += $pStatus['counts'];
			array_push($status['pStatusArray'],$pStatus);
		}
		return $status;
	}

	//根据所取得的数据库中的商品信息 与订单进行比对 得到详细信息
	private function getProductStatus($oPID,$oCount,$products){

		//创建订单中要购买商品的数量 价格等详细信息
		$pStatus = [
			'id' => null,
			'haveStock' => false,
			'counts' => 0,
			'price' =>0,
			'name' => '',
			'totalPrice' => 0,
			'main_img_url' =>null
		];
		//确认序号
		$pIndex = -1;
		for($i=0;$i<count($products);$i++){
			if($oPID == $products[$i]['id']){
				$pIndex = $i;
			}
		}
		if($pIndex == -1){
			//客户端传递的product_id可能不存在
			throw new OrderExcption([
				'msg' => 'id为'.$oPID.'商品不存在，创建订单失败'
			]);
		}else{
			$product = $products[$pIndex];
			$pStatus['id'] = $product['id'];
			$pStatus['name'] = $product['name'];
			$pStatus['counts'] = $oCount;
			$pStatus['price'] = $product['price'];
			$pStatus['main_img_url'] = $product['main_img_url'];
			$pStatus['totalPrice'] = $product['price']* $oCount;
			if($product['stock'] - $oCount >= 0 ){
				$pStatus['haveStock'] = true;
			}
		}
		return $pStatus;
	}

	//根据订单信息查找真实产品信息
	private function getProductsByOrder($oProducts){
		$oPIDs = [];
		foreach($oProducts as $item){
			array_push($oPIDs,$item['product_id']);
		}
		$products = Product::all($oPIDs)->visible(['id','price','stock','name','main_img_url'])->toArray();
		return $products;
	}

	//根据订单ID查询产品id等信息,进行库存检测；
	public function checkOrderStock($orderID){

		$oProducts = OrderProduct::where('order_id','=',$orderID)->select();
		$this->oProducts = $oProducts;
		$this->products = $this->getProductsByOrder($oProducts);
		$status = $this->getOrderStatus();
		return $status;
	}

	public function delivery($orderID,$jumpPage = ''){
		$order = OrderModel::where('id','=',$orderID)->find();
		if(!$order){
			throw new OrderExcption();
		}
		if($order->status != OrderStatusEnum::PAID){
			throw new OrderExcption([
				'msg' => '??????',
				'errorCode' => 80002,
				'code' => 403

			]);
		}
		$order->status = OrderStatusEnum::DELIVERED;
		$order->save();
		$message = new DeliveryMessage();
		return $message->sendDeliveryMessage($order,$jumpPage);
	}

}
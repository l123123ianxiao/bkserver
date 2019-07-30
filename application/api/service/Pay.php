<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/3
 * Time: 17:45
 */

namespace app\api\service;

use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use app\lib\enum\OrderStatusEnum;
use app\lib\exception\TokenException;
use think\Exception;
use think\Loader;
use think\Log;
use app\lib\exception\OrderExcption;

// extend/WxPay/WxPay.Api.php
Loader::import( 'WxPay.WxPay',EXTEND_PATH,'.Api.php');

class Pay
{
	private $orderID;
	private $orderNO;
//	private $orderProName;

	function __construct($orderID)
	{
		if (!$orderID)
		{
			throw new Exception('订单号不允许为NULL');
		}
		$this->orderID = $orderID;
		//增加微信支付成功后 支付订单详情的商品名称
		$OrderModel = new OrderModel();
		$orderProName = $OrderModel->getProNameByOrder($this->orderID);
		$this->orderProName = $orderProName;

	}

	public function pay()
	{
		//订单号可能根本就不存在
		//订单号确实是存在的，但是，订单号和当前用户是不匹配的
		//订单有可能已经被支付过
		//进行库存量检测
		$this->checkOrderValid();
		$orderService = new OrderService();
		$status = $orderService->checkOrderStock($this->orderID);
		if (!$status['pass'])
		{
			return $status;
		}
//		print_r($this->makeWxPreOrder($status['orderPrice']));exit;
		return $this->makeWxPreOrder($status['orderPrice']);

	}

	private function makeWxPreOrder($totalPrice)
	{
		//openid
		$openid = Token::getCurrentTokenVar('openid');

		if (!$openid)
		{
			throw new TokenException();
		}
		$wxOrderData = new \WxPayUnifiedOrder();
		$wxOrderData->SetOut_trade_no($this->orderNO);
		$wxOrderData->SetTrade_type('JSAPI');
		$wxOrderData->SetTotal_fee($totalPrice * 100);
		$wxOrderData->SetBody($this->orderProName);
		$wxOrderData->SetOpenid($openid);
		//回调地址
		$wxOrderData->SetNotify_url(config('secure.pay_back_url'));
//		print_r($wxOrderData);exit;
		return $this->getPaySignature($wxOrderData);
	}

	private function getPaySignature($wxOrderData)
	{
//		print_r($wxOrderData);exit;
		$wxOrder = \WxPayApi::unifiedOrder($wxOrderData);
//		print_r($wxOrder);exit;
		if ($wxOrder['return_code'] != 'SUCCESS' ||
			$wxOrder['result_code'] != 'SUCCESS'
		)
		{
			Log::record($wxOrder, 'error');
			Log::record('获取预支付订单失败', 'error');
		}
		//prepay_id
		$this->recordPreOrder($wxOrder);
		$signature = $this->sign($wxOrder);

		return $signature;
	}

	private function sign($wxOrder)
	{
		$jsApiPayData = new \WxPayJsApiPay();
		$jsApiPayData->SetAppid(config('wx.app_id'));
		$jsApiPayData->SetTimeStamp((string)time());

		$rand = md5(time() . mt_rand(0, 1000));
		$jsApiPayData->SetNonceStr($rand);

		$jsApiPayData->SetPackage('prepay_id='.$wxOrder['prepay_id']);
		$jsApiPayData->SetSignType('md5');

		$sign = $jsApiPayData->MakeSign();
		$rawValues = $jsApiPayData->GetValues();
		$rawValues['paySign'] = $sign;

		unset($rawValues['appId']);

		return $rawValues;
	}

	private function recordPreOrder($wxOrder)
	{
		//print_r($wxOrder);
		OrderModel::where('id', '=', $this->orderID)
			->update(['prepay_id' => $wxOrder['prepay_id']]);
	}

	private function checkOrderValid()
	{
		$order = OrderModel::where('id', '=', $this->orderID)
			->find();
		if (!$order)
		{
			throw new OrderExcption();
		}
		if (!Token::isValidOperate($order->user_id))
		{
			throw new TokenException(
				[
					'msg' => '订单与用户不匹配',
					'errorCode' => 10003
				]);
		}
		if ($order->status != OrderStatusEnum::UNPAID)
		{
			throw new OrderException(
				[
					'msg' => '订单已支付过啦',
					'errorCode' => 80003,
					'code' => 400
				]);
		}
		$this->orderNO = $order->order_no;
		return true;
	}
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/3
 * Time: 17:27
 */

namespace app\api\controller\v1;


use app\api\service\WxNotify;
use app\api\validate\IDMustBePostiveInt;

class Pay extends BaseController
{
	protected $beforeActionList = [
		'checkExclusiveScope' => ['only' => 'getPreOrder']
	];

	public function getPreOrder($id=''){
		(new IDMustBePostiveInt())->goCheck();
		$pay = new \app\api\service\Pay($id);
//		print_r($pay->pay());exit;
		return $pay->pay();

	}

	public function receiveNotify(){
		//通知频率为15/15/30/100/1000/1800/1800/3600,单位：秒

		//1.检测库存量。（超卖）
		//2.更新这个订单状态status
		//3.减库存
		//如果成功处理，我们返回微信成功处理的信息。否则，返回失败信息
		//特点： post;xml格式;不会携带参数
		$notify = new WxNotify();
		$notify->Handle();

	}
}
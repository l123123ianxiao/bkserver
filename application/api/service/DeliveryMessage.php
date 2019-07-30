<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/21
 * Time: 2:53
 */

namespace app\api\service;


use app\api\model\User;
use app\lib\exception\OrderExcption;
use app\lib\exception\UserException;

class DeliveryMessage extends WxMessage
{
	const DELIVERY_MSG_ID = '7XV0CFPEBptIcRI1lvqyh8fOjggQ82Yx5go5Adcez7o';

	public function sendDeliveryMessage($order,$tplJumpPage=''){
		if(!$order){
			throw new OrderExcption();
		}
		$this->tplID = self::DELIVERY_MSG_ID;
		$this->formID = $order->prepay_id;
		$this->page = $tplJumpPage;
		$this->prepareMessageData($order);
		$this->emphasisKeyWord = 'keyword2.DATA';
		return parent::sendMessage($this->getUserOpenID($order->user_id));
	}


	private function prepareMessageData($order){
		$dt = new \DateTime();
		$data = [
		'keyword1' => [
			'value' => '深圳贝壳口腔',
			],
		'keyword2' => [
				'value' =>'0755-21008489',
				'color' =>'#27408B'
			],
		'keyword3' => [
				'value' =>'龙岗区布吉街道中翠路145号'
			],

		];
		$this->data = $data;
	}

	private function getUserOpenID($uid){
		$user = User::get($uid);
		if(!$user){
			throw new UserException();
		}
		return $user->openid;
	}



}
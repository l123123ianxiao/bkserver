<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/10
 * Time: 0:17
 */

namespace app\api\service;
use app\api\model\BannerItem as BannerItemModel;

class BannerService
{

	public static function addBanner($data)
	{
			//print_r($data);exit;
		$add['key_word'] = $data['key_word'];
		$add['banner_id'] = $data['banner_id'];
		if(!empty($data['imglist'])){
			$data['imglist'] = explode(',',$data['imglist']);
			if(!empty($data['imglist'][0]) ){
				$add['img_id'] = $data['imglist'][0];
			}
		}
		$add['type'] = $data['type'];
		$add['update_time'] = time();
		return BannerItemModel::addOne($add);
	}


	public static function editBannerOne($id, $data)
	{
		$where = array('id'=>$id);
		$add['name'] = $data['name'];
		if(!empty($data['imglist'])){
			$data['imglist'] = explode(',',$data['imglist']);
			//print_r($data['imglist']);exit;
			if(!empty($data['imglist'][0]) ){
				$add['topic_img_id'] = $data['imglist'][0];
			}
			if(!empty($data['imglist'][1]) ){
				$add['head_img_id'] = $data['imglist'][1];
			}
		}
		$add['description'] = $data['description'];
		$add['update_time'] = time();
		//print_r($add);exit;
		return BannerItemModel::updateOne($where, $add);

	}


	public static  function  removeOne($id){
		return BannerItemModel::deleteone($id);
	}


	public static  function  getone($id){
		return BannerItemModel::with('topicImg,headImg')->getThemeWithProducts($id);
	}
}
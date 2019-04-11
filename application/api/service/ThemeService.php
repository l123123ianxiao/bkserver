<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/27
 * Time: 12:23
 */

namespace app\api\service;

use app\api\model\Theme as ThemeModel;

class ThemeService
{
	public static function addTheme($data)
	{
		$add['name'] = $data['name'];
		if(!empty($data['imglist'])){
			$data['imglist'] = explode(',',$data['imglist']);
			if(!empty($data['imglist'][0]) ){
				$add['topic_img_id'] = $data['imglist'][0];
			}
			if(!empty($data['imglist'][1]) ){
				$add['head_img_id'] = $data['imglist'][1];
			}
		}
		$add['description'] = $data['description'];
		$add['update_time'] = time();
        return ThemeModel::addOne($add);
	}


	public static function editThemeOne($id, $data)
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
		return ThemeModel::updateOne($where, $add);

	}


    public static  function  removeOne($id){
        return ThemeModel::deleteone($id);
    }


    public static  function  getone($id){
        return ThemeModel::with('topicImg,headImg')->getThemeWithProducts($id);
    }
}
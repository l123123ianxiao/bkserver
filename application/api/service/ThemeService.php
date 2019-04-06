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
		$add['topic_img_id'] = $data['topic_img_id'];
		$add['description'] = $data['description'];
		$add['update_time'] = time();
        return ThemeModel::addOne($add);
	}


	public static function editThemeOne($id, $data)
	{
		$where = array('id'=>$id);
        $data['update_time'] =time();
		return ThemeModel::updateOne($where, $data);

	}


    public static  function  removeOne($id){
        return ThemeModel::deleteone($id);
    }


    public static  function  getone($id){
        return ThemeModel::getThemeWithProducts($id);
    }
}
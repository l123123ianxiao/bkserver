<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/23
 * Time: 15:27
 */

namespace app\api\model;
use think\db;


class Theme extends BaseModel
{
	protected $hidden = ['delete_time','update_time','topic_img_id','head_img_id'];
    public function topicImg(){
    	return $this->belongsTo('Image','topic_img_id','id');
	}

	public function headImg(){
    	return $this->belongsTo('Image','head_img_id','id');
	}

	public function products(){
    	return $this->belongsToMany('Product','theme_product','product_id','theme_id');
	}

	public static function getThemeWithProducts($id){
        return  self::with('topicImg')->find($id);
	}


    public static function addOne($data){
        $result = self::create($data);
        return $result;
    }

    public static function updateOne($where,$data)
    {
        $result =  self::update($data,$where);
        return $result;
    }


    public static  function deleteone($id){
        return Db::table('theme')->where('id',$id)->delete();

    }

}
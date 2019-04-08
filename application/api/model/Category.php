<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/25
 * Time: 21:04
 */

namespace app\api\model;
use think\Db;


class Category extends BaseModel
{
	protected $hidden = ['delete_time','update_time','create_time'];
	public function img(){
		return $this->belongsTo('Image','topic_img_id','id');
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

    public static function getCategoryDetail($id)
    {
        $theme = self::with('img')->find($id);
        return $theme;
    }

    public  static function deleteone($id){
        return Db::table('category')->where('id',$id)->delete();
    }


}
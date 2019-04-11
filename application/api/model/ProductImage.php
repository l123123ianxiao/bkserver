<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/27
 * Time: 17:33
 */

namespace app\api\model;
use think\Db;

class ProductImage extends BaseModel
{
	protected $hidden = ['img_id','delete_time','update_time','product_id'];

	public function imgUrl(){
		return $this->belongsTo('Image','img_id','id');

	}
    public static  function deleteProductImage($product_id){
        return Db::table('product_image')->where('product_id',$product_id)->delete();

    }
}
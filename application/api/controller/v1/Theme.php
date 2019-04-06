<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/23
 * Time: 15:24
 */

namespace app\api\controller\v1;


use app\api\validate\IDCollection;
use app\api\model\Theme as ThemeModel;
use app\api\validate\IDMustBePostiveInt;
use app\lib\exception\ThemeException;
use app\api\service\ThemeService;


class Theme
{
//	 *
//    @url /theme?ids=id1,id2,....
//    @return 返回一组theme

   public function getSimpleList($ids=''){
	   (new IDCollection())->goCheck();
	   $ids = explode(',',$ids);
	   $result = ThemeModel::with('topicImg,headImg')->select($ids);
	   if($result->isEmpty()){
	   	throw new ThemeException();
	   }
	   return $result;
   }


//	 *
//    @url /theme/id
//
   public function getComplexOne($id){
	   (new IDMustBePostiveInt())->goCheck();
		$theme = ThemeModel::getThemeWithProducts($id);
		if(!$theme){
			throw new ThemeException();
		}
		return $theme;
   }



    public function getOne($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $product = ThemeService::getOne($id);
        if (!$product) {
            throw new ThemeException();
        }
        return $product;
    }


    public function deleteOne($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $result = ThemeService::removeOne($id);
        if ($result == 1) {
            return json(new SuccessMessage([
                'code' => 200,
                'msg' => 'ok，请检查参数',
            ]), 200);

        } else {
            return json(new SuccessMessage([
                'code' => 400,
                'msg' => '请求失败，请检查参数',
                'errorCode' => 80000
            ]));
        }
    }

    public function addOne()
    {
        $data = input('post.');
        $result = ThemeService::addTheme($data);
        if ($result->id) {
            return json(new SuccessMessage([
                'code' => 200,
                'msg' => '请求成功，请检查参数',
                'errorCode' => 0
            ]));
        } else {
            throw ThemeException();
        }
    }


    public function editOne()
    {
        $data = input('post.');
        $id = $data['id'];
        unset($data['id']);
        $result = ThemeService::editThemeOne($id, $data);
        return $result;
    }

}
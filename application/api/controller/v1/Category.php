<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/25
 * Time: 21:04
 */

namespace app\api\controller\v1;

use app\api\model\Category as CategoryModel;
use app\api\service\CategoryService;
use app\lib\exception\CategoryException;
use app\api\validate\IDMustBePostiveInt;
use app\lib\exception\SuccessMessage;
use think\Db;


class Category
{
    public function getAllCategories()
    {
        $categories = CategoryModel::all([], 'img');
        if ($categories->isEmpty()) {
            throw new CategoryException();
        }
        return $categories;
    }


    public function getOne($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $product = CategoryService::getOne($id);
        if (!$product) {
            throw new CategoryException();
        }
        return $product;
    }


    public function deleteOne($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $result = CategoryService::removeOne($id);
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
        $result = CategoryService::addCategory($data);
        if ($result->id) {
            return json(new SuccessMessage([
                'code' => 200,
                'msg' => '请求成功，请检查参数',
                'errorCode' => 0
            ]));
        } else {
            throw ProductException();
        }
    }


    public function editOne()
    {
        $data = input('post.');
        $id = $data['id'];
        unset($data['id']);

        $result = CategoryService::editCategoryOne($id, $data);
        return $result;

    }
}
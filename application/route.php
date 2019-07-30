<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

Route::get('api/:version/banner/:id','api/:version.Banner/getBanner');
Route::get('api/:version/banner','api/:version.Banner/getAllBanner');
Route::post('api/:version/banner/edit','api/:version.Banner/editOne');
Route::post('api/:version/banner/add','api/:version.Banner/addOne');
Route::delete('api/:version/banner/:id','api/:version.Banner/deleteOne',[],['id' =>'\d+']);


Route::get('api/:version/theme','api/:version.Theme/getSimpleList');

Route::get('api/:version/theme/:id','api/:version.Theme/getComplexOne');

//路由分组  product
Route::group('api/:version/product',function(){
	Route::get('/by_category','api/:version.Product/getAllInCategory');
	Route::get('/:id','api/:version.Product/getOne',[],['id'=>'\d+']);
	Route::get('/recent','api/:version.Product/getRecent');
	Route::get('/paginate','api/:version.Product/getSummary');
	Route::delete('/:id','api/:version.Product/deleteOne',[],['id'=>'\d+']);
	Route::post('/add','api/:version.Product/addOne');
	Route::post('/addimg','api/:version.Product/addProductImgUrl');
	Route::post('/edit','api/:version.Product/editOne');
});

//路由分组  category
Route::group('api/:version/category',function(){
	Route::get('/:id','api/:version.Category/getOne',[],['id'=>'\d+']);
	Route::delete('/:id','api/:version.Category/deleteOne',[],['id'=>'\d+']);
	Route::post('/add','api/:version.Category/addOne');
	Route::post('/edit','api/:version.Category/editOne');
});

//路由分组  theme
Route::group('api/:version/theme',function(){
	Route::get('/:id','api/:version.Theme/getOne',[],['id'=>'\d+']);
	Route::delete('/:id','api/:version.Theme/deleteOne',[],['id'=>'\d+']);
	Route::post('/add','api/:version.Theme/addOne');
	Route::post('/edit','api/:version.Theme/editOne');
});


Route::get('api/:version/category/all','api/:version.Category/getAllCategories');

//Token
Route::post('api/:version/token/user','api/:version.Token/getToken');
Route::post('api/:version/token/verify','api/:version.Token/verifyToken');
Route::post('api/:version/token/app','api/:version.Token/getAppToken');

//Address
Route::post('api/:version/address','api/:version.Address/createOrUpdateAddress');
Route::get('api/:version/address','api/:version.Address/getUserAddress');

//Order
Route::post('api/:version/order','api/:version.Order/placeOrder');
Route::get('api/:version/order/by_user','api/:version.Order/getSummaryByUser');
Route::get('api/:version/order/:id','api/:version.Order/getDetail',[],['id'=>'\d+']);
Route::get('api/:version/order/paginate','api/:version.Order/getSummary');
Route::post('api/:version/order/delivery','api/:version.Order/delivery');




//Pay
Route::post('api/:version/pay/pre_order','api/:version.Pay/getPreOrder');

Route::post('api/:version/pay/notify','api/:version.Pay/receiveNotify');
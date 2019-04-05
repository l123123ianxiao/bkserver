<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/29
 * Time: 7:21
 */

namespace app\api\controller\v1;


use think\Controller;
use app\api\service\Token as TokenService;

class BaseController extends Controller
{
	protected function checkPrimaryScope(){
		TokenService::needPrimaryScope();
	}

	protected function checkExclusiveScope(){
		TokenService::needExclusiveScope();
	}
}
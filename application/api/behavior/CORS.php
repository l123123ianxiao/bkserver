<<<<<<< HEAD
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/21
 * Time: 16:09
 */

namespace app\api\behavior;


class CORS
{
	public function appInit($params){
		header('Access-Control-Allow-Origin:*');
		header("Access-Control-Allow-Headers: token,Origin,X-Requested-With,Content-Type,Accept");
		header('Access-Control-Allow-Methods:POST,GET,DELETE');
		if(request()->isOptions()){
			exit();
		}
	}

}
=======
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/21
 * Time: 16:09
 */

namespace app\api\behavior;


class CORS
{
	public function appInit($params){
		header('Access-Control-Allow-Origin:*');
		header("Access-Control-Allow-Headers: token,Origin,X-Requested-With,Content-Type,Accept");
		header('Access-Control-Allow-Methods:POST,GET,DELETE');
		if(request()->isOptions()){
			exit();
		}
	}

}
>>>>>>> 6065f49e72cce6780efcd43300f7cb05847aaa8e

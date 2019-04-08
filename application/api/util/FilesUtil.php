<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/5
 * Time: 16:17
 */

namespace app\api\util;


/***
 * 文件操作类
 * Class FilesUtil
 * @package app\api\util
 */
class FilesUtil
{

	public static $types = array("png", "jpg", "webp", "jpeg", "gif");

	public static $dir;

	public static function UploadFiles($upfile, $types = false, $dir = false)
	{

		if (!$types) {
			$types = self::$types;
		}
		if (!$dir) {
			$dir = $_SERVER['DOCUMENT_ROOT'] . "/zerg/public/images/";
		}
		var_dump($dir);die;
		if (!is_dir($dir)) {
			mkdir($dir);
		}
		for ($i = 0; $i < count($upfile['name']); $i++) {
			$name = $upfile['name'][$i];  //在循环中取得每次要上传的文件名
			//echo $name;die();
			$end = explode(".", $name);
			//print_r($end);die();
			$type = strtolower(end($end)); //在循环中取得每次要上传的文件类型
			if (!in_array($type, $types)) {
				echo "第" . ($i + 1) . "个文件类型错误<br/>";
			} else {
				$error = $upfile['error'][$i];  //在循环中取得每次要上传的文件的错误情况
				if ($error != 0) {
					echo "第" . ($i + 1) . "个文件上传错误<br/>";
				} else {
					$tmp_name = $upfile['tmp_name'][$i];//在循环中取得每次要上传的文件的临时文件
					if (!is_uploaded_file($tmp_name)) {
						echo "第" . ($i + 1) . "个文件临时文件错误<br/>";
					} else {
//							$newname = $dir.date("YmdHis") . rand(1, 10000) . "." . $type;
						$name = date("YmdHis") . rand(1, 10000) . "." . $type;
						$newname = $dir . $name;

//							$newname = '/'.date("YmdHis") . rand(1, 10000) . "." . $type;
						$img_array[$i] = substr($newname, strpos($newname, '/TP'));
						//在循环中给每个文件一个新名称
						if (!move_uploaded_file($tmp_name, $newname)) {  //对每个临时文件执行上传操作
							echo "第" . ($i + 1) . "个文件上传失败<br/>";
						}
						$imgUrl[] = '/' . $name;

					}
				}
			}
		}
		return $imgUrl;
	}
}
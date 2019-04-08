<?php
/**
 * Created by IntelliJ IDEA.
 * User: mingyongzhang
 * Date: 2019-04-08
 * Time: 20:50
 */

namespace app\api\controller\v1;


class Base
{

    public function checkEmptyField($field,&$data){
        foreach ($data as $key => $value){
            if(in_array($key,$field) && ($value == null ||$value ="")){
                unset($data[$key]);
            }
        }
    }

}
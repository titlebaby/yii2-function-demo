<?php

namespace frontend\actions;
use common\exceptions\TemporaryException;
use Yii;
use yii\web\ErrorAction;
use yii\web\Response;

/**
 *  Api 全局错误异常处理器
 */
class ErrorApiAction extends ErrorAction
{
    public function run()
    {
        // 根据异常类型设定相应的响应码
        Yii::$app-> getResponse()-> setStatusCodeByException($this-> exception);
        // json 格式返回
        Yii::$app-> getResponse()-> format = Response::FORMAT_JSON;
        // 返回的内容数据
        if ($this->exception instanceof TemporaryException) {
            return $this->exception->getMessage();
        }
        return [
            'msg' =>  $this-> exception-> getMessage(),
            'err_code' =>  $this-> exception-> getCode()
        ];
    }

}
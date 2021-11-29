<?php

namespace common\exceptions;
/**
 * app 异常基础类
 */
class HttpException extends \yii\web\HttpException
{
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct($this-> statusCode, $message, $code, $previous);
    }
}
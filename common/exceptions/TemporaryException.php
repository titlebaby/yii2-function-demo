<?php

namespace common\exceptions;


use Throwable;


class TemporaryException extends HttpException
{
    public $statusCode = 404;

}
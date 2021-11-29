<?php

namespace common\exceptions;

class HttpNotFoundException extends HttpException
{
    public $statusCode = 404;
}
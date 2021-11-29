<?php

namespace common\jobs;
use common\exceptions\TemporaryException;
use yii\base\BaseObject;
use yii\queue\RetryableJobInterface;

//class DownloadJob extends BaseObject implements \yii\queue\JobInterface
class DownloadJob extends BaseObject implements RetryableJobInterface
{
    public $url;
    public $file;

    public function execute($queue)
    {
        var_dump($this->file);
    }


    public function getTtr()
    {
        return 15 * 60;
    }

    public function canRetry($attempt, $error)
    {
        return ($attempt < 5) && ($error instanceof TemporaryException);
    }

}
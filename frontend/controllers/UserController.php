<?php
/**
 * Created by PhpStorm.
 * User: linger
 * Date: 2019-09-21
 * Time: 21:55
 */

namespace frontend\controllers;


use app\models\UserTest;
use yii\web\Controller;

class UserController extends Controller
{
    /**
     * yii实现是乐观锁 更新是检查是否存在版本启用
     * 启动乐观锁
     */
    public function actionVersion(){

        $user = new UserTest();
        $res = $user->updateRecord(1,"zhangsan");
        var_dump($res);
        die();
    }
}
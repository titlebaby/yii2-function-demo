<?php
/**
 * Created by PhpStorm.
 * User: linger
 * Date: 2019-09-21
 * Time: 21:55
 */

namespace frontend\controllers;


use app\models\UserTest;
use common\helpers\DbHelper;
use Exception;
use Yii;
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

    /**
     * 嵌套事务测试
     * @throws \yii\db\Exception
     */
    public function actionDbTrans(){
        $user = new UserTest();
        $res = DbHelper::getTableFields(UserTest::tableName());
        $db = Yii::$app->db->beginTransaction();
        try {
            $user->doTrans(1);
//            throw new  Exception("二层异常");
            $db->commit();
        }catch (\Exception $exception) {
            $db->rollBack();
            var_dump($exception->getMessage());
        }
        var_dump(1111);

    }
}
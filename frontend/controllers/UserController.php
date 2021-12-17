<?php
/**
 * Created by PhpStorm.
 * User: linger
 * Date: 2019-09-21
 * Time: 21:55
 */

namespace frontend\controllers;


use app\models\UserTest;
use common\behavior\NoCsrf;
use common\exceptions\TemporaryException;
use common\helpers\DbHelper;
use common\jobs\DownloadJob;
use Exception;
use Yii;
use yii\web\Controller;

class UserController extends Controller
{
    // 1. 该控制器下的所有Csrf验证都被屏蔽
    //public $enableCsrfValidation = false;
    // 2. 指定控制器种的某个行为的csrf验证被屏蔽
    public function behaviors()
    {
        return [
            'csrf' =>[
                'class' => NoCsrf::className(),
                'controller'=>$this,
                'actions' =>[
                    'register-scenarios'
                ]
            ]
        ];
    }

    /**
     * yii实现是乐观锁 更新是检查是否存在版本启用
     * 启动乐观锁——其实感觉可以直接根据本身的按条件更新，解决并发的
     */
    public function actionVersion(){

        $user = new UserTest();
        $res = $user->updateRecord(1,"zhangsan");
        var_dump($res);
        die();
    }

    /**
     * my.demo.test/user/db-trans
     * 嵌套事务测试（mysql的嵌套队列会一起回滚）
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

    public function actionJob(){
        //1. 测试yii支持的rabbitmq的延迟队列
//
//        Yii::$app->queue->delay(1 * 60)->push(new DownloadJob([
//            'url' => 'http://example.com/image.jpg',
//            'file' => '/tmp/image.jpg',
//        ]));

        // 2.测试全局捕获异常
        throw new TemporaryException("具体的非法描述", 4001);
        var_dump(1121);

    }

    public function actionRegisterScenarios(){

        //1. 场景作为属性来设置
        $model = new UserTest;
        $model->scenario = 'login';

        //2. 场景通过构造初始化配置来设置
        $model = new UserTest(['scenario' => 'register']);


        // 用户输入数据赋值到模型属性
        $model->attributes = \Yii::$app->request->post('register');

        // 按指定场景验证参数。
        if ($model->validate() && $model->save()) {
            // 所有输入数据都有效 all inputs are valid
            var_dump(111);
        } else {
            // 验证失败：$errors 是一个包含错误信息的数组
            $errors = $model->errors;
            var_dump($errors);
        }

    }
}
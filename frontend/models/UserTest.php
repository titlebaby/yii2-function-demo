<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_test".
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property int $age
 * @property int $version_no
 */
class UserTest extends \yii\db\ActiveRecord
{
    const SCENARIO_INSERT = 'insert';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_DEL = 'del';
    const SCENARIO_DETAIL = 'detail';
    const SCENARIO_LOGIN = 'login';
    const SCENARIO_REGISTER = 'register';

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_REGISTER] = ['name', 'address','age','password'];
        $scenarios[self::SCENARIO_UPDATE] = ['name', 'address','age','password'];
        $scenarios[self::SCENARIO_LOGIN] = ['name', 'password'];
        $scenarios[self::SCENARIO_DEL] = ['id'];
        $scenarios[self::SCENARIO_DETAIL] = ['id'];
        return $scenarios;
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_test';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // 在"register" 场景下 username, email 和 password 必须有值
            [['name', 'address','age','password'], 'required', 'on' => 'register'],

            // 在 "login" 场景下 username 和 password 必须有值
            [['user', 'password'], 'required', 'on' => 'login'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'address' => 'Address',
            'age' => 'Age',
            'version_no' => 'Version No',
        ];
    }


    public function optimisticLock()
    {
        return "version_no";
    }

    public function updateRecord($id,$name){
        try{
            $user = self::findOne($id);
            $user -> name = $name;
            sleep(20);
            $res = $user->save();
            return $res ;
        }catch (\Exception $exception){
            return $exception->getMessage();
        }


    }

    public function doTrans($id){
        $db = Yii::$app->db->beginTransaction();
        try{
            $user = self::findOne($id);
            $user->age = 88;
            $user->save();
            $db->commit();
        }catch (\Exception $exception){
            $db->rollBack();
            return $exception->getMessage();
        }

    }
}

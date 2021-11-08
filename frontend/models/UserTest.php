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
            [['name'], 'required'],
            [['address'], 'string'],
            [['age', 'version_no'], 'integer'],
            [['name'], 'string', 'max' => 32],
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

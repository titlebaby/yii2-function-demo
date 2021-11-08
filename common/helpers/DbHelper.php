<?php

namespace common\helpers;
use Yii;

class DbHelper
{
    /**
     * 获取表结构
     * @param string $tableName
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getTableStructure(string $tableName){
//		$sql = 'DESC ' . $tableName;
        $sql = 'SHOW COLUMNS FROM  ' . $tableName; // 两个SQL二选其一，返回数据结构相同
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        return $res;
    }

    /**
     * 获取表字段
     * @param string $tableName
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getTableFields(string $tableName){
        $sql = 'SHOW COLUMNS FROM  ' . $tableName;
        $res = Yii::$app->db->createCommand($sql)->queryColumn();
        return $res;
    }

}
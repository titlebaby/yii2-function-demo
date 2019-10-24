<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "news".
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property int $status
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['content'], 'string'],
            [['status'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'status' => 'Status',
        ];
    }

    const STATUS_DELETE = 0;

    //模拟yii的预定义事件。预定义事件一共有10个  ，详细内容查看 BaseActiveRecord
    // 第一步：定义自己的事件
    const EVENT_BEFORE_MARK_DELETE = 'beforeMarkDelete';
    const EVENT_AFTER_MARK_DELETE = 'afterMarkDelete';

    public function onBeforeMarkDelete () {
        // ... do sth ...
    }


    // 第三步：在初始化阶段绑定事件和Event Handler
    public function init()
    {
        parent::init();
        $this->trigger(self::EVENT_INIT);
        // 完成绑定
        $this->on(self::EVENT_BEFORE_MARK_DELETE, [$this, 'onBeforeMarkDelete']);
    }

    // 第四步：触发事件(插入、更新之前触发)
    public function beforeSave($insert) {
        // 注意，重载之后要调用父类同名函数
        if (parent::beforeSave($insert)) {
            $status = $this->getDirtyAttributes(['status']);
            // 这个判断意会即可
            if (!empty($status) && $status['status'] == self::STATUS_DELETE) {
                // 触发事件 是假删除时触发"beforeMarkDelete"事件，处理事件的handle为onBeforeMarkDelete
                $this->trigger(self::EVENT_BEFORE_MARK_DELETE);
            }
            return true;
        } else {
            return false;
        }
    }



}

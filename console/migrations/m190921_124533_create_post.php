<?php

use yii\db\Migration;

/**
 * Class m190921_124533_create_post
 */
class m190921_124533_create_post extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190921_124533_create_post cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('news', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'content' => $this->text(),
        ]);

    }

    public function down()
    {
        echo "m190921_124533_create_post cannot be reverted.\n";

        return false;
    }

}

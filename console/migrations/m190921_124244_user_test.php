<?php

use yii\db\Migration;

/**
 * Class m190921_124244_user_test
 */
class m190921_124244_user_test extends Migration
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
        echo "m190921_124244_user_test cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('user_test', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'address' => $this->text(),
            'age'=>$this->integer()->notNull()->defaultValue(18)
        ]);

    }

    public function down()
    {
        echo "m190921_124244_user_test cannot be reverted.\n";

        return false;
    }

}

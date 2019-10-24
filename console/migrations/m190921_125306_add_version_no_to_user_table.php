<?php

use yii\db\Migration;

/**
 * Class m190921_125306_add_version_no_to_user_table
 */
class m190921_125306_add_version_no_to_user_table extends Migration
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
        echo "m190921_125306_add_version_no_to_user_table cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('user_test', 'version_no', $this->integer());

    }

    public function down()
    {
        echo "m190921_125306_add_version_no_to_user_table cannot be reverted.\n";

        return false;
    }

}

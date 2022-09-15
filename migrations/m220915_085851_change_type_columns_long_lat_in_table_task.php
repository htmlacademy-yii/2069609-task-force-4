<?php

use yii\db\Migration;

/**
 * Class m220915_085851_change_type_columns_long_lat_in_table_task
 */
class m220915_085851_change_type_columns_long_lat_in_table_task extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('task', 'longitude', 'decimal(11,7)');
        $this->alterColumn('task', 'latitude', 'decimal(11,7)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('task','longitude', 'decimal' );
        $this->alterColumn('task','latitude', 'decimal' );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220915_085851_change_type_columns_long_lat_in_table_task cannot be reverted.\n";

        return false;
    }
    */
}

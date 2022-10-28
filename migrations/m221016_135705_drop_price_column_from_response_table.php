<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%response}}`.
 */
class m221016_135705_drop_price_column_from_response_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%response}}', 'price');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%response}}', 'price', $this->integer());
    }
}

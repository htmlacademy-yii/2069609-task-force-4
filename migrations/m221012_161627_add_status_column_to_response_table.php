<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%response}}`.
 */
class m221012_161627_add_status_column_to_response_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%response}}', 'status', $this->boolean());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%response}}', 'status');
    }
}

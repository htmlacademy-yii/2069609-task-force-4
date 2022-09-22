<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%response}}`.
 */
class m220922_192204_add_date_add_column_to_response_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%response}}', 'date_add', $this->timestamp()->defaultExpression('NOW()'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%response}}', 'date_add');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%response}}`.
 */
class m220930_144016_add_date_feedback_column_to_response_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%response}}', 'date_feedback', $this->timestamp()->defaultExpression('NOW()'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%response}}', 'date_feedback');
    }
}

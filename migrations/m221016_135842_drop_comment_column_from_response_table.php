<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%response}}`.
 */
class m221016_135842_drop_comment_column_from_response_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%response}}', 'comment');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%response}}', 'comment', $this->string());
    }
}

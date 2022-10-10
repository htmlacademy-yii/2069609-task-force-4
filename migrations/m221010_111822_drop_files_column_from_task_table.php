<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%task}}`.
 */
class m221010_111822_drop_files_column_from_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%task}}', 'files');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%task}}', 'files', $this->string(255));
    }
}

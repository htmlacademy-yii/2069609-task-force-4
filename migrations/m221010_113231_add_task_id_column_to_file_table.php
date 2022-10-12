<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%file}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%task}}`
 */
class m221010_113231_add_task_id_column_to_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%file}}', 'task_id', $this->integer()->notNull());

        // creates index for column `task_id`
        $this->createIndex(
            '{{%idx-file-task_id}}',
            '{{%file}}',
            'task_id'
        );

        // add foreign key for table `{{%task}}`
        $this->addForeignKey(
            '{{%fk-file-task_id}}',
            '{{%file}}',
            'task_id',
            '{{%task}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%task}}`
        $this->dropForeignKey(
            '{{%fk-file-task_id}}',
            '{{%file}}'
        );

        // drops index for column `task_id`
        $this->dropIndex(
            '{{%idx-file-task_id}}',
            '{{%file}}'
        );

        $this->dropColumn('{{%file}}', 'task_id');
    }
}

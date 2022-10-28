<?php

use yii\db\Migration;

/**
 * Class m221020_092747_altercolumn_comment_table_responce
 */
class m221020_092747_altercolumn_comment_table_responce extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('response', 'comment', $this->string()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('response', 'comment', $this->string());
    }
}

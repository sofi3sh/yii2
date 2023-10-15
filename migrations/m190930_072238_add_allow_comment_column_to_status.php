<?php

use yii\db\Migration;

/**
 * Class m190930_072238_add_allow_comment_column_to_status
 */
class m190930_072238_add_allow_comment_column_to_status extends Migration
{
    public function safeUp()
    {
        $this->addColumn('status', 'allow_comment', 'INT DEFAULT 0 AFTER `order`');
        $this->addColumn('status_log', 'comment', 'TEXT');
    }

    public function safeDown()
    {
        $this->dropColumn('status', 'allow_comment');
        $this->dropColumn('status_log', 'comment');
    }
}

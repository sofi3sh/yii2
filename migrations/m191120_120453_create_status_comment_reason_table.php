<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%status_comment_reason}}`.
 */
class m191120_120453_create_status_comment_reason_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%status_comment_reason}}', [
            'id' => $this->primaryKey(),
            'status_id' => $this->integer()->notNull(),
            'reason_key' => $this->string()->notNull(),
            'title_source_message_id' => $this->integer()->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%status_comment_reason}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%status_log}}`.
 */
class m190820_144850_create_status_log_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%status_log}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'status_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%status_log}}');
    }
}

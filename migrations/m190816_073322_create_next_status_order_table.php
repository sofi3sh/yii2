<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%next_status_order}}`.
 */
class m190816_073322_create_next_status_order_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%next_status_order}}', [
            'id' => $this->primaryKey(),
            'status_id' => $this->integer()->notNull(),
            'next_status_id' => $this->integer()->notNull(),
            'user_role_name' => $this->string(50)->notNull()
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%next_status_order}}');
    }
}

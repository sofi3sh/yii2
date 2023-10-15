<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m190507_133707_create_user_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'full_name' => $this->string(100),
            'username' => $this->string(100)->notNull()->unique(),
            'password' => $this->string(150)->notNull(),
            'email' => $this->string(35),
            'email_confirm' => 'tinyint NOT NULL DEFAULT 0',
            'phone' => $this->string(20),
            'auth_key' => $this->string(100)->notNull()->unique(),
            'is_active' => 'tinyint NOT NULL DEFAULT 1',
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}

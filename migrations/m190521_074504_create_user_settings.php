<?php

use yii\db\Migration;

/**
 * Class m190521_074504_create_user_settings
 */
class m190521_074504_create_user_settings extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%user_settings}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'language_id' => 'tinyint NOT NULL DEFAULT 1',
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%user_settings}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%status}}`.
 */
class m190815_112123_create_status_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%status}}', [
            'id' => $this->primaryKey(),
            'title_source_message_id' => $this->integer()->notNull(),
            'key' => $this->string(50)->notNull(),
            'color' => $this->string(20),
            'order' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%status}}');
    }
}

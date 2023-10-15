<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%file}}`.
 */
class m190827_080622_create_file_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%file}}', [
            'id' => $this->primaryKey(),
            'file_type_id' => $this->integer()->notNull(),
            'entity_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'size' => $this->integer(),
            'extension' => $this->string(20),
            'origin_name' => $this->string(),
            'full_origin_name' => $this->string(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    public function safeDown()
    {
        $this->dropTable('{{%file}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Class m190826_111903_create_file_type_table
 */
class m190826_111903_create_file_type_table extends Migration
{
    
    public function safeUp()
    {
        $this->createTable('{{%file_type}}', [
            'id' => $this->primaryKey(),
            'title_source_message_id' => $this->integer()->notNull(),
            'key' => $this->string(50)->notNull(),
            'entity' => $this->string(50)->notNull(),
            'allowed_extensions' => $this->string(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    public function safeDown()
    {
        $this->dropTable('{{%file_type}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Class m190902_152410_create_printed_form_formula
 */
class m190902_152410_create_printed_form_formula extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%printed_form_formula}}', [
            'id' => $this->primaryKey(),
            'title_source_message_id' => $this->integer()->notNull(),
            'key' => $this->string()->notNull(),
            'expression' => $this->text(),
            'is_system' => $this->integer()->defaultValue(0),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    public function safeDown()
    {
        $this->dropTable('{{%printed_form_formula}}');
    }
}

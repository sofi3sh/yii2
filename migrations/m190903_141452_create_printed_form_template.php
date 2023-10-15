<?php

use yii\db\Migration;

/**
 * Class m190903_141452_create_printed_form_template
 */
class m190903_141452_create_printed_form_template extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%printed_form_template}}', [
            'id' => $this->primaryKey(),
            'title_source_message_id' => $this->integer()->notNull(),
            'template' => 'LONGTEXT',
            'convert_to_csv' => $this->integer()->defaultValue(0),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    public function safeDown()
    {
        $this->dropTable('{{%printed_form_template}}');
    }
}

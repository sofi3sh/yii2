<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%printed_form_group}}`.
 */
class m190905_075956_create_printed_form_group_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%printed_form_group}}', [
            'id' => $this->primaryKey(),
            'title_source_message_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    public function safeDown()
    {
        $this->dropTable('{{%printed_form_group}}');
    }
}

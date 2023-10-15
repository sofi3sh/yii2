<?php

use yii\db\Migration;

/**
 * Class m190927_081804_i18n_for_instructions
 */
class m190927_081804_i18n_for_instructions extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%instruction_source_message}}', [
            'id' => $this->primaryKey(),
            'category' => $this->string(),
            'message' => $this->text(),
        ], $tableOptions);

        $this->createTable('{{%instruction_message}}', [
            'id' => $this->integer()->notNull(),
            'language' => $this->string(16)->notNull(),
            'translation' => $this->text(),
        ], $tableOptions);

        $this->addPrimaryKey('pk_message_id_language', '{{%instruction_message}}', ['id', 'language']);
        $this->addForeignKey(
            'fk_instruction_message_instruction_source_message', 
            '{{%instruction_message}}', 
            'id', 
            '{{%instruction_source_message}}', 
            'id', 
            'CASCADE', 
            'RESTRICT'
        );
        $this->createIndex('idx_instruction_source_message_category', '{{%instruction_source_message}}', 'category');
        $this->createIndex('idx_instruction_message_language', '{{%instruction_message}}', 'language');
    }

    public function down()
    {
        $this->dropForeignKey('fk_instruction_message_instruction_source_message', '{{%instruction_message}}');
        $this->dropTable('{{%instruction_message}}');
        $this->dropTable('{{%instruction_source_message}}');
    }
}

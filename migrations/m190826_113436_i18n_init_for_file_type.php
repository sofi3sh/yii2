<?php

use yii\db\Migration;

/**
 * Class m190826_113436_i18n_init_for_file_type
 */
class m190826_113436_i18n_init_for_file_type extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%file_type_source_message}}', [
            'id' => $this->primaryKey(),
            'category' => $this->string(),
            'message' => $this->text(),
        ], $tableOptions);

        $this->createTable('{{%file_type_message}}', [
            'id' => $this->integer()->notNull(),
            'language' => $this->string(16)->notNull(),
            'translation' => $this->text(),
        ], $tableOptions);

        $this->addPrimaryKey('pk_message_id_language', '{{%file_type_message}}', ['id', 'language']);
        $this->addForeignKey(
            'fk_file_type_message_file_type_source_message', 
            '{{%file_type_message}}', 
            'id', 
            '{{%file_type_source_message}}', 
            'id', 
            'CASCADE', 
            'RESTRICT'
        );
        $this->createIndex('idx_file_type_source_message_category', '{{%file_type_source_message}}', 'category');
        $this->createIndex('idx_file_type_message_language', '{{%file_type_message}}', 'language');
    }

    public function down()
    {
        $this->dropForeignKey('fk_file_type_message_file_type_source_message', '{{%file_type_message}}');
        $this->dropTable('{{%file_type_message}}');
        $this->dropTable('{{%file_type_source_message}}');
    }
}

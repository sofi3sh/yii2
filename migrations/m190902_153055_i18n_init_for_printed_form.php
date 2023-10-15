<?php

use yii\db\Migration;

/**
 * Class m190902_153055_i18n_init_for_printed_form
 */
class m190902_153055_i18n_init_for_printed_form extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%printed_form_source_message}}', [
            'id' => $this->primaryKey(),
            'category' => $this->string(),
            'message' => $this->text(),
        ], $tableOptions);

        $this->createTable('{{%printed_form_message}}', [
            'id' => $this->integer()->notNull(),
            'language' => $this->string(16)->notNull(),
            'translation' => $this->text(),
        ], $tableOptions);

        $this->addPrimaryKey('pk_message_id_language', '{{%printed_form_message}}', ['id', 'language']);
        $this->addForeignKey(
            'fk_printed_form_message_printed_form_source_message', 
            '{{%printed_form_message}}', 
            'id', 
            '{{%printed_form_source_message}}', 
            'id', 
            'CASCADE', 
            'RESTRICT'
        );
        $this->createIndex('idx_printed_form_source_message_category', '{{%printed_form_source_message}}', 'category');
        $this->createIndex('idx_printed_form_message_language', '{{%printed_form_message}}', 'language');
    }

    public function down()
    {
        $this->dropForeignKey('fk_printed_form_message_printed_form_source_message', '{{%printed_form_message}}');
        $this->dropTable('{{%printed_form_message}}');
        $this->dropTable('{{%printed_form_source_message}}');
    }
}

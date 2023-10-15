<?php

use yii\db\Migration;

/**
 * Class m190815_112632_i18n_init_for_status
 */
class m190815_112632_i18n_init_for_status extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%status_source_message}}', [
            'id' => $this->primaryKey(),
            'category' => $this->string(),
            'message' => $this->text(),
        ], $tableOptions);

        $this->createTable('{{%status_message}}', [
            'id' => $this->integer()->notNull(),
            'language' => $this->string(16)->notNull(),
            'translation' => $this->text(),
        ], $tableOptions);

        $this->addPrimaryKey('pk_message_id_language', '{{%status_message}}', ['id', 'language']);
        $this->addForeignKey(
            'fk_status_message_status_source_message', 
            '{{%status_message}}', 
            'id', 
            '{{%status_source_message}}', 
            'id', 
            'CASCADE', 
            'RESTRICT'
        );
        $this->createIndex('idx_status_source_message_category', '{{%status_source_message}}', 'category');
        $this->createIndex('idx_status_message_language', '{{%status_message}}', 'language');
    }

    public function down()
    {
        $this->dropForeignKey('fk_status_message_status_source_message', '{{%status_message}}');
        $this->dropTable('{{%status_message}}');
        $this->dropTable('{{%status_source_message}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Class m190605_111733_i18n_init_for_rbac
 */
class m190605_111733_i18n_init_for_rbac extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%rbac_source_message}}', [
            'id' => $this->primaryKey(),
            'category' => $this->string(),
            'message' => $this->text(),
        ], $tableOptions);

        $this->createTable('{{%rbac_message}}', [
            'id' => $this->integer()->notNull(),
            'language' => $this->string(16)->notNull(),
            'translation' => $this->text(),
        ], $tableOptions);

        $this->addPrimaryKey('pk_message_id_language', '{{%rbac_message}}', ['id', 'language']);
        $this->addForeignKey(
            'fk_rbac_message_rbac_source_message', 
            '{{%rbac_message}}', 
            'id', 
            '{{%rbac_source_message}}', 
            'id', 
            'CASCADE', 
            'RESTRICT'
        );
        $this->createIndex('idx__rbac_source_message_category', '{{%rbac_source_message}}', 'category');
        $this->createIndex('idx_rbac_message_language', '{{%rbac_message}}', 'language');
    }

    public function down()
    {
        $this->dropForeignKey('fk_rbac_message_rbac_source_message', '{{%rbac_message}}');
        $this->dropTable('{{%rbac_message}}');
        $this->dropTable('{{%rbac_source_message}}');
    }
}

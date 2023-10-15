<?php

use yii\db\Migration;

/**
 * Class m190611_101305_i18n_init_for_product
 */
class m190611_101305_i18n_init_for_product extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%product_source_message}}', [
            'id' => $this->primaryKey(),
            'category' => $this->string(),
            'message' => $this->text(),
        ], $tableOptions);

        $this->createTable('{{%product_message}}', [
            'id' => $this->integer()->notNull(),
            'language' => $this->string(16)->notNull(),
            'translation' => $this->text(),
        ], $tableOptions);

        $this->addPrimaryKey('pk_message_id_language', '{{%product_message}}', ['id', 'language']);
        $this->addForeignKey(
            'fk_product_message_product_source_message', 
            '{{%product_message}}', 
            'id', 
            '{{%product_source_message}}', 
            'id', 
            'CASCADE', 
            'RESTRICT'
        );
        $this->createIndex('idx_product_source_message_category', '{{%product_source_message}}', 'category');
        $this->createIndex('idx_product_message_language', '{{%product_message}}', 'language');
    }

    public function down()
    {
        $this->dropForeignKey('fk_product_message_product_source_message', '{{%product_message}}');
        $this->dropTable('{{%product_message}}');
        $this->dropTable('{{%product_source_message}}');
    }
}

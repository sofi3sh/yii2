<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product_option}}`.
 */
class m190611_094904_create_product_option_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%product_option}}', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer(),
            'product_id' => $this->integer(),
            'option_key' => $this->string()->notNull(),
            'option_type' => $this->string(),
            'title_source_message_id' => $this->integer()->notNull(),
            'value' => $this->string(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%product_option}}');
    }
}

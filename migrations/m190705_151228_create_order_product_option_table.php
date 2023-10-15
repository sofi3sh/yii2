<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order_product_option}}`.
 */
class m190705_151228_create_order_product_option_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%order_product_option}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'product_option_id' => $this->integer()->notNull(),
            'product_option_value' => $this->string(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%order_product_option}}');
    }
}

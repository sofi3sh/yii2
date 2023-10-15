<?php

use yii\db\Migration;

class m200309_090925_create_product_product_option extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%product_product_option}}', [
            'id' => $this->primaryKey(),
            'option_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    public function safeDown()
    {
        $this->dropTable('product_product_option');
    }
}

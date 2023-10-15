<?php

use yii\db\Migration;

class m200226_085254_create_basic_order_module_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%basic_order_module}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'amount' => $this->integer(),
            'weight' => $this->float(5),
            'material' => $this->string(),
            'laser' => $this->float(5),
            'bending' => $this->float(5),
            'welding' => $this->float(5),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    public function safeDown()
    {
        $this->dropTable('{{%basic_order_module}}');
    }
}

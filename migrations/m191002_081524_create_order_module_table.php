<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order_module}}`.
 */
class m191002_081524_create_order_module_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%order_module}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'module_number' => $this->integer(),
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
        $this->dropTable('{{%order_module}}');
    }
}

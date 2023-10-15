<?php

use yii\db\Migration;

class m200304_125618_add_module_id_to_basic_module extends Migration
{
    public function safeUp()
    {
        $this->addColumn('basic_order_module', 'module_id', 'int DEFAULT NULL AFTER id');
    }

    public function safeDown()
    {
        $this->dropColumn('basic_order_module', 'module_id');

        return false;
    }
}

<?php

use yii\db\Migration;

/**
 * Class m191025_080421_add_module_id_to_order_module
 */
class m191025_080421_add_module_id_to_order_module extends Migration
{
    
    public function safeUp()
    {
        $this->addColumn('order_module', 'module_id', 'INT AFTER order_id');
    }

    public function safeDown()
    {
        $this->dropColumn('order_module', 'module_id');
    }

}

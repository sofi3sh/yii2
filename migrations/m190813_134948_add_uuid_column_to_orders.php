<?php

use yii\db\Migration;

/**
 * Class m190813_134948_add_uuid_column_to_orders
 */
class m190813_134948_add_uuid_column_to_orders extends Migration
{    
    public function safeUp()
    {
        $this->addColumn('order', 'uuid', 'VARCHAR(50) UNIQUE AFTER id');
    }

    public function safeDown()
    {
        $this->dropColumn('order', 'uuid');
    }

}

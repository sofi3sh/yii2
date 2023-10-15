<?php

use yii\db\Migration;

/**
 * Class m190814_082301_add_measurement_unit_to_product_option
 */
class m190814_082301_add_measurement_unit_to_product_option extends Migration
{
    public function safeUp()
    {
        $this->addColumn('product_option', 'measurement_unit', 'VARCHAR(50) AFTER value');
    }

    public function safeDown()
    {
        $this->dropColumn('product_option', 'measurement_unit');
    }
}

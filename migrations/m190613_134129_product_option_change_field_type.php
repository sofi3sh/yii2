<?php

use yii\db\Migration;

/**
 * Class m190613_134129_product_option_change_field_type
 */
class m190613_134129_product_option_change_field_type extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('product_option', 'option_type', 'int(1)');
    }

    public function safeDown()
    {
        $this->alterColumn('product_option', 'option_type', 'varchar(150)');
    }
}

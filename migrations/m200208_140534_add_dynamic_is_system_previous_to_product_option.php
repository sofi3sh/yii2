<?php

use yii\db\Migration;

class m200208_140534_add_dynamic_is_system_previous_to_product_option extends Migration
{
    public function safeUp()
    {
        $this->addColumn('product_option', 'is_dynamic', 'TINYINT NOT NULL DEFAULT 0');
        $this->addColumn('product_option', 'is_system', 'TINYINT NOT NULL DEFAULT 0');
        $this->addColumn('product_option', 'previous_option_id', 'INT');
    }

    public function safeDown()
    {
        $this->dropColumn('product_option', 'is_dynamic');
        $this->dropColumn('product_option', 'is_system');
        $this->dropColumn('product_option', 'previous_option_id');

        return false;
    }
}

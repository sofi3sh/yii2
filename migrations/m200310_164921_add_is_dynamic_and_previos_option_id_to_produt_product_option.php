<?php

use yii\db\Migration;

class m200310_164921_add_is_dynamic_and_previos_option_id_to_produt_product_option extends Migration
{

    public function safeUp()
    {
        $this->addColumn('product_product_option', 'is_dynamic', 'TINYINT NOT NULL DEFAULT 0');
        $this->addColumn('product_product_option', 'is_system', 'TINYINT NOT NULL DEFAULT 0');
        $this->addColumn('product_product_option', 'previous_option_id', 'INT');
    }

    public function safeDown()
    {
        $this->dropColumn('product_product_option', 'is_dynamic');
        $this->dropColumn('product_product_option', 'is_system');
        $this->dropColumn('product_product_option', 'previous_option_id');

        return false;
    }
}

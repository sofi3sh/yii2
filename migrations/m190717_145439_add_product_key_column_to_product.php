<?php

use yii\db\Migration;

/**
 * Class m190717_145439_add_product_key_column_to_product
 */
class m190717_145439_add_product_key_column_to_product extends Migration
{
    public function safeUp()
    {
        $this->addColumn('product', 'product_key', 'VARCHAR(150) UNIQUE AFTER id');
    }

    public function safeDown()
    {
        $this->dropColumn('product', 'product_key');
    }

}

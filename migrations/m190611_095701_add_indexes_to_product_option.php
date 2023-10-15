<?php

use yii\db\Migration;

/**
 * Class m190611_095701_add_indexes_to_product_option
 */
class m190611_095701_add_indexes_to_product_option extends Migration
{
    public function safeUp()
    {
        $this->createIndex('idx_product_option_parent_id', '{{%product_option}}', 'parent_id');
        $this->createIndex('idx_product_option_product_id', '{{%product_option}}', 'product_id');
    }

    
    public function safeDown()
    {
        $this->dropIndex('idx_product_option_parent_id', '{{%product_option}}');
        $this->dropIndex('idx_product_option_product_id', '{{%product_option}}');
    }

}

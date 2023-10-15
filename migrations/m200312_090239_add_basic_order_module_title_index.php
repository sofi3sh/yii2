<?php

use yii\db\Migration;

class m200312_090239_add_basic_order_module_title_index extends Migration
{

    public function safeUp()
    {
        $this->createIndex(
            'basic_order_module_title_index',
            'basic_order_module',
            'title'
        );
    }

    public function safeDown()
    {
        $this->dropIndex(
            'basic_order_module_title_index',
            'basic_order_module'
        );
    }
}

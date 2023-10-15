<?php

use yii\db\Migration;

/**
 * Class m190821_084446_add_index_to_order_uuid
 */
class m190821_084446_add_index_to_order_uuid extends Migration
{
    public function safeUp()
    {
        $this->createIndex('idx_order_uuid', 'order', 'uuid');
    }

    public function safeDown()
    {
        $this->dropIndex('idx_order_uuid', 'order')
;    }
}

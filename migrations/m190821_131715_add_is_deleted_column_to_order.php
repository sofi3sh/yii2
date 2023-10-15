<?php

use yii\db\Migration;

/**
 * Class m190821_131715_add_is_deleted_column_to_order
 */
class m190821_131715_add_is_deleted_column_to_order extends Migration
{
    public function safeUp()
    {
        $this->addColumn('order', 'is_deleted', 'INT DEFAULT 0 AFTER status_id');
    }

    public function safeDown()
    {
        $this->dropColumn('order', 'is_deleted');
    }
}

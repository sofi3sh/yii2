<?php

use yii\db\Migration;

/**
 * Class m190819_092739_change_next_status_id_to_key
 */
class m190819_092739_change_next_status_id_to_key extends Migration
{
    public function safeUp()
    {
        $this->dropColumn('next_status_order', 'next_status_id');
        $this->dropColumn('next_status_order', 'status_id');
        $this->addColumn('next_status_order', 'status_key', 'VARCHAR(150) AFTER id');
        $this->addColumn('next_status_order', 'next_status_key', 'VARCHAR(150) AFTER status_key');
    }

    public function safeDown()
    {
        $this->dropColumn('next_status_order', 'next_status_key');
        $this->dropColumn('next_status_order', 'status_key');
        $this->addColumn('next_status_order', 'next_status_id', 'INT AFTER id');
        $this->addColumn('next_status_order', 'status_id', 'INT AFTER next_status_id');
    }
}

<?php

use yii\db\Migration;

class m191217_102803_set_default_value_to_entity_id_in_file extends Migration
{

    public function safeUp()
    {
        $this->alterColumn('file', 'entity_id', 'int(11) default 0 not null');
    }

    public function safeDown()
    {
        $this->alterColumn('file', 'entity_id', 'int(11)');
    }
}

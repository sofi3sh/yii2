<?php

use yii\db\Migration;

/**
 * Class m191002_082519_add_allow_fragments_column
 */
class m191002_082519_add_allow_fragments_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn('order', 'allow_fragments', 'INT DEFAULT 0 AFTER is_deleted');
    }

    public function safeDown()
    {
        $this->dropColumn('order', 'allow_fragments');
    }
}

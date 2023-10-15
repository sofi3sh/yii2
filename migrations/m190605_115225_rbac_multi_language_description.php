<?php

use yii\db\Migration;

/**
 * Class m190605_115225_rbac_multi_language_description
 */
class m190605_115225_rbac_multi_language_description extends Migration
{
    public function safeUp()
    {
        $this->addColumn('auth_item', 'rbac_source_message_id', 'int');
    }

    public function safeDown()
    {
        $this->addColumn('auth_item', 'description', 'text');
    }
}

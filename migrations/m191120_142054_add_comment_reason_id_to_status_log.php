<?php

use yii\db\Migration;

/**
 * Class m191120_142054_add_comment_reason_id_to_status_log
 */
class m191120_142054_add_comment_reason_id_to_status_log extends Migration
{
    public function safeUp()
    {
        $this->addColumn('status_log', 'comment_reason_id', 'INT');
    }

    public function safeDown()
    {
        $this->dropColumn('status_log', 'comment_reason_id');
    }
}

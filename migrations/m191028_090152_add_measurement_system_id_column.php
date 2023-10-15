<?php

use yii\db\Migration;

/**
 * Class m191028_090152_add_measurement_system_id_column
 */
class m191028_090152_add_measurement_system_id_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn('user_settings', 'measurement_system_id', 'tinyint NOT NULL DEFAULT 1 AFTER language_id');
    }

    public function safeDown()
    {
        $this->dropColumn('user_settings', 'measurement_system_id');
    }
}

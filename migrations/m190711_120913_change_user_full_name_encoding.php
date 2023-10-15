<?php

use yii\db\Migration;

/**
 * Class m190711_120913_change_user_full_name_encoding
 */
class m190711_120913_change_user_full_name_encoding extends Migration
{
    public function safeUp()
    {
        $this->execute("ALTER TABLE user MODIFY full_name VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci;");
    }

    public function safeDown()
    {
    }
}

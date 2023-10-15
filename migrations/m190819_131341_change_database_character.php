<?php

use yii\db\Migration;

/**
 * Class m190819_131341_change_database_character
 */
class m190819_131341_change_database_character extends Migration
{
    public function safeUp()
    {
        $this->execute('
            ALTER DATABASE metalpark CHARACTER SET utf8 COLLATE utf8_unicode_ci;
            ALTER TABLE client CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
        ');
    }

    public function safeDown()
    {
        echo "m190819_131341_change_database_character cannot be reverted.\n";

        return false;
    }
}

<?php

use yii\db\Migration;

/**
 * Class m190911_085417_change_user_email_length
 */
class m190911_085417_change_user_email_length extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('user', 'email', $this->string());
    }

    public function safeDown()
    {
        echo "m190911_085417_change_user_email_length cannot be reverted.\n";

        return false;
    }
}

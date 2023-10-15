<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%relate_client_to_user}}`.
 */
class m190815_134423_create_relate_client_to_user_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%relate_client_to_user}}', [
            'id' => $this->primaryKey(),
            'client_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%relate_client_to_user}}');
    }
}

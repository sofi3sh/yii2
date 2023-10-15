<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%file_access}}`.
 */
class m190826_135445_create_file_access_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%file_access}}', [
            'id' => $this->primaryKey(),
            'file_type_id' => $this->integer()->notNull(),
            'user_role' => $this->string(50)->notNull(),
            'status_id' => $this->integer()->notNull(),
            'action_id' => $this->integer()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    public function safeDown()
    {
        $this->dropTable('{{%file_access}}');
    }
}

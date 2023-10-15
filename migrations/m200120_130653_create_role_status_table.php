<?php

use yii\db\Migration;

class m200120_130653_create_role_status_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%role_status}}', [
            'id' => $this->primaryKey(),
            'role_name' => $this->string()->notNull(),
            'status_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    public function safeDown()
    {
        $this->dropTable('{{%role_status}}');
    }
}

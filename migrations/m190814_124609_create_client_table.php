<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%client}}`.
 */
class m190814_124609_create_client_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%client}}', [
            'id' => $this->primaryKey(),
            'referer_user_id' => $this->integer()->notNull(),
            'full_name' => $this->string(),
            'phone' => $this->string(50),
            'email' => $this->string(80),
            'address_legal' => $this->string(),
            'address_actual' => $this->string(),
            'contractor_type' => $this->integer(),
            'client_code' => $this->string(50),
            'customer_code' => $this->string(50),
            'contact_person' => $this->string(),
            'responsible_person' => $this->string(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%client}}');
    }
}

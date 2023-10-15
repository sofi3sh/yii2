<?php

use yii\db\Migration;

/**
 * Class m190905_080914_relate_printed_form_group_to_template
 */
class m190905_080914_relate_printed_form_group_to_template extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%printed_form_group_template}}', [
            'id' => $this->primaryKey(),
            'printed_form_group_id' => $this->integer()->notNull(),
            'printed_form_template_id' => $this->integer()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    public function safeDown()
    {
        $this->dropTable('{{%printed_form_group_template}}');
    }
}

<?php

namespace app\models;

use \Yii;
use yii\db\ActiveRecord;

class PrintedFormGroupTemplate extends ActiveRecord
{
    public function rules()
    {
        return [
            [['printed_form_group_id', 'printed_form_template_id'], 'safe'],
            [['printed_form_group_id', 'printed_form_template_id'], 'required'],
        ];
    }

}

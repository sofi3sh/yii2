<?php
namespace app\common\helpers\views;

use Yii;

class SettingsHelper
{
    public static function activeFormBasic($formId)
    {
        return [
            'id' => $formId,
            'fieldConfig' => [
                'template' => '{label}<div class="col-lg-10">{input}</div>',
                'options' => [
                    'tag' => false,
                ],
            ],
        ];
    }

}

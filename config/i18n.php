<?php

return [
    'translations' => [
        'rbac' => [
            'class' => 'yii\i18n\DbMessageSource',
            'sourceMessageTable' => '{{%rbac_source_message}}',
            'messageTable' => '{{%rbac_message}}',
        ],
        'product*' => [
            'class' => 'yii\i18n\DbMessageSource',
            'sourceMessageTable' => '{{%product_source_message}}',
            'messageTable' => '{{%product_message}}',
        ],
        'status*' => [
            'class' => 'yii\i18n\DbMessageSource',
            'sourceMessageTable' => '{{%status_source_message}}',
            'messageTable' => '{{%status_message}}',
        ],
        'fileType*' => [
            'class' => 'yii\i18n\DbMessageSource',
            'sourceMessageTable' => '{{%file_type_source_message}}',
            'messageTable' => '{{%file_type_message}}',
        ],
        'printedForm*' => [
            'class' => 'yii\i18n\DbMessageSource',
            'sourceMessageTable' => '{{%printed_form_source_message}}',
            'messageTable' => '{{%printed_form_message}}',
        ],
        'instruction*' => [
            'class' => 'yii\i18n\DbMessageSource',
            'sourceMessageTable' => '{{%instruction_source_message}}',
            'messageTable' => '{{%instruction_message}}',
        ],
        'app/models*' => [
            'class' => 'yii\i18n\PhpMessageSource',
            'fileMap' => [
                'app/models/loginForm' => 'models/loginForm.php',
                'app/models/user' => 'models/user.php',
                'app/models/authItem' => 'models/authItem.php',
                'app/models/product' => 'models/product.php',
                'app/models/productOption' => 'models/productOption.php',
                'app/models/order' => 'models/order.php',
                'app/models/client' => 'models/client.php',
                'app/models/status' => 'models/status.php',
                'app/models/fileType' => 'models/fileType.php',
                'app/models/file' => 'models/file.php',
                'app/models/printedFormFormula' => 'models/printedFormFormula.php',
                'app/models/printedFormTemplate' => 'models/printedFormTemplate.php',
                'app/models/printedFormGroup' => 'models/printedFormGroup.php',
                'app/models/orderModule' => 'models/orderModule.php',
                'app/models/basicOrderModule' => 'models/basicOrderModule.php'
            ],
        ],
        'app*' => [
            'class' => 'yii\i18n\PhpMessageSource',
            'fileMap' => [
                'app' => 'app.php',
            ],
        ],
        'validation' => [
            'class' => 'yii\i18n\PhpMessageSource',
            'fileMap' => [
                'app' => 'validation.php',
            ],
        ],
        'frontend*' => [
            'class' => 'yii\i18n\PhpMessageSource',
            'fileMap' => [
                'orderForm' => 'orderForm.php',
            ],
        ],
    ],
];

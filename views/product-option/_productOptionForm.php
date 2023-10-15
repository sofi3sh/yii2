<?php
    use yii\widgets\ActiveForm;
    use app\common\helpers\views\SettingsHelper;
    use yii\helpers\Html;
    use yii\helpers\ArrayHelper;
    use app\models\ProductOption;
    use app\models\Language;
    use app\common\helpers\views\TranslationHelper;
    use app\common\helpers\views\ModelAttributeHelper;
    use app\common\helpers\views\HtmlHelper;

    $baseUrl = Yii::$app->params['themeUrl'];
    $urlWithLayout = Yii::$app->params['layoutUrl'];

    $activeFormSettings = SettingsHelper::activeFormBasic('product-option-form');
    $form = ActiveForm::begin($activeFormSettings);
    $action = Yii::$app->controller->action->id;

    echo HtmlHelper::javaScript($urlWithLayout.'/assets/js/pages/productOptionForm.js');
?>

    <div class="card">
        <div class="card-header">
            <h6 class="card-title"><?= $title ?></h6>
        </div>
        <div class="card-body">
            <?php if ($model->hasErrors()): ?>
                <div class="alert alert-danger">
                    <p><?= $form->errorSummary($model); ?></p>
                </div>
            <?php endif; ?>

            <?= TranslationHelper::renderFieldWithTranslations(
                'ProductOption', 
                'title_source_message_id', 
                Yii::t('app/models/productOption', 'Name'),
                $model
            ) ?>

            <div class="row form-group">
                <?= $form->field($model, 'parent_id')->dropDownList(
                    ArrayHelper::map($productOptions, 'id', 'title'),
                    ['prompt' => Yii::t('app', 'Select')]
                    )->label($model->getAttributeLabel('parent_id'), ['class' => 'col-lg-2 col-form-label']);
                ?>
            </div>

            <div class="row form-group">
                <?= $form->field($model, 'product_id')->dropDownList(
                    ArrayHelper::map($products, 'id', 'title'),
                    ['prompt' => Yii::t('app', 'Select')]
                    )->label($model->getAttributeLabel('product_id'), ['class' => 'col-lg-2 col-form-label']);
                ?>
            </div>

            <div class="row form-group">
                <?= $form->field($model, 'option_key')
                    ->textInput([
                        'size' => 60, 
                        'maxlength' => 255, 
                        'class' => 'form-control', 
                        'disabled' => ($action === 'update'),
                        'value' => ModelAttributeHelper::getOldAttributeValueOnUpdateAction($model, 'option_key')
                    ])
                    ->label($model->getAttributeLabel('option_key'), ['class' => 'col-lg-2 col-form-label']);
                ?>
            </div>

            <div class="row form-group">
                <?= $form->field($model, 'option_type')->dropDownList(
                    ProductOption::OPTION_TYPES, ['id' => 'option-type']
                    )->label($model->getAttributeLabel('option_type'), ['class' => 'col-lg-2 col-form-label']);
                ?>
            </div>

            <div class="row form-group">
                <?= $form->field($model, 'value')
                    ->textInput(['size' => 60, 'maxlength' => 255, 'class' => 'form-control'])
                    ->label($model->getAttributeLabel('value'), ['class' => 'col-lg-2 col-form-label']);
                ?>
            </div>

            <div class="row form-group">
                <?= $form->field($model, 'measurement_unit')
                    ->textInput(['size' => 60, 'maxlength' => 255, 'class' => 'form-control'])
                    ->label($model->getAttributeLabel('measurement_unit'), ['class' => 'col-lg-2 col-form-label']);
                ?>
            </div>

            <div id="dynamic-option-section">
                <div class="row form-group">
                    <?= $form->field($model, 'is_dynamic')
                        ->checkbox([
                            'label' => '',
                            'id' => 'is_dynamic',
                            'disabled' => boolval($model->is_system)
                        ])
                        ->label($model->getAttributeLabel('is_dynamic'), ['class' => 'col-lg-2 col-form-label']);
                    ?>
                </div>

                <div class="row form-group">
                    <?= $form->field($model, 'previous_option_id')->dropDownList(
                        ArrayHelper::map($availablePreviousOptions, 'id', 'title'),
                        ['prompt' => Yii::t('app', 'Select')]
                        )
                        ->label($model->getAttributeLabel('previous_option_id'), ['class' => 'col-lg-2 col-form-label']);
                    ?>
                </div>
            </div>

            <div class="row form-group justify-content-center">
                <input type="submit" class="btn btn-primary mr-2" value="<?= Yii::t('app', 'Submit') ?>">
            </div>
        </div>
    </div>

<?php ActiveForm::end() ?>


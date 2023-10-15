
<?php
    use yii\widgets\ActiveForm;
    use app\common\helpers\views\SettingsHelper;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use app\common\helpers\views\TranslationHelper;
    use app\common\helpers\views\HtmlHelper;
   

    $baseUrl = Yii::$app->params['themeUrl'];
    $urlWithLayout = Yii::$app->params['layoutUrl'];

    $activeFormSettings = SettingsHelper::activeFormBasic('file-form');
    $form = ActiveForm::begin($activeFormSettings);

    echo HtmlHelper::javaScript($urlWithLayout.'/assets/js/pages/printedFormGroup.js');
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
            'PrintedFormGroup', 
            'title_source_message_id', 
            Yii::t('app', 'Title'),
            $model
        ) ?>

        <?php
            if (!empty($model->templates)):
                foreach ($model->templates as $key => $template):
                ?>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label"><?= Yii::t('app/models/printedFormGroup', 'Template') ?></label>
                        <div class="col-lg-10">
                            <?= Html::dropDownList(
                                'PrintedFormGroup[save_templates][]', 
                                null, 
                                ArrayHelper::map($templates,'id', 'title'), 
                                [
                                    'class' => 'form-control', 
                                    'options' => [
                                        $template->id => ['selected' => true]
                                    ]
                                ]
                            ) ?>
                            <button 
                                style="display: <?= $key > 0 ? 'inline-block' : 'none;' ?>"
                                class="btn delete-template badge badge-danger mt-1"
                            >
                                <?= Yii::t('app', 'Delete') ?>
                            </button>
                            <button 
                                style="display: <?= $key + 1 == count($model->templates) ? 'inline-block' : 'none;' ?>"
                                class="btn add-template badge badge-primary mt-1"
                            >
                                <?= Yii::t('app/models/printedFormGroup', 'One more') ?>
                            </button>
                        </div>
                    </div>
                <?php endforeach; 
            else: ?>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label"><?= Yii::t('app/models/printedFormGroup', 'Template') ?></label>
                    <div class="col-lg-10">
                        <?= Html::dropDownList(
                            'PrintedFormGroup[save_templates][]', 
                            null, 
                            ArrayHelper::map($templates,'id', 'title'), 
                            ['class' => 'form-control']
                            )
                        ?>
                        <button style="display: none" class="btn delete-template badge badge-danger mt-1">
                            <?= Yii::t('app', 'Delete') ?>
                        </button>
                        <button class="add-template btn badge badge-primary mt-1">
                            <?= Yii::t('app/models/printedFormGroup', 'One more') ?>
                        </button>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row form-group justify-content-center btns">
                <input type="submit" class="btn btn-primary mr-2" value="<?= Yii::t('app', 'Submit') ?>">
            </div>
        <?php ActiveForm::end() ?>
    </div>
</div>


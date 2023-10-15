<?php
    use yii\widgets\ActiveForm;
    use app\common\helpers\views\SettingsHelper;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;

    $baseUrl = Yii::$app->params['themeUrl'];
    $urlWithLayout = Yii::$app->params['layoutUrl'];

    $activeFormSettings = SettingsHelper::activeFormBasic('file-form');
    $form = ActiveForm::begin($activeFormSettings);
?>

<?php if ($model->hasErrors()): ?>
    <div class="alert alert-danger">
        <p><?= $form->errorSummary($model); ?></p>
    </div>
<?php endif; ?>

<div class="row form-group">
    <?= $form->field($model, 'file_type_id')->dropDownList(
        ArrayHelper::map($fileTypes, 'id', 'title'),
        ['prompt' => Yii::t('app', 'Select'), 'disabled' => !$model->isNewRecord]
        )->label($model->getAttributeLabel('file_type_id'), ['class' => 'col-lg-2 col-form-label']);
    ?>
</div>

<div class="row form-group">
    <?= $form->field($model, 'entity_id')
        ->textInput(['class' => 'form-control', 'disabled' => !$model->isNewRecord])
        ->label($model->getAttributeLabel('entity_id'), ['class' => 'col-lg-2 col-form-label']);
    ?>
</div>

<div class="row ">
    <label for="origin_name" class="col-2 col-form-label"><?= Yii::t('app/models/file', 'File') ?></label>
    <div class="col-10">
        <?php if (!$model->isNewRecord): ?>
            <?= Html::a(
                Yii::t('app/models/file', 'File') . ' (' . $model->origin_name . ')', 
                '/file/view/' . $model->id, 
                ['target' => '_blank', 'class' => 'btn text-teal-400 border-teal-400 border-2 m-1']
            ) ?>
        <?php endif; ?>                
        <?= $form->field($model, 'origin_name')
            ->fileInput(['class' => 'form-control'])
            ->label(false);
        ?>
    </div>
</div>

<div class="row form-group justify-content-center">
    <input type="submit" class="btn btn-primary mr-2" value="<?= Yii::t('app', 'Submit') ?>">
</div>

<?php ActiveForm::end() ?>


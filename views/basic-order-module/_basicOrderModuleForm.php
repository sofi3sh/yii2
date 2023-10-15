<?php
    use yii\widgets\ActiveForm;
    use app\common\helpers\views\SettingsHelper;

    $baseUrl = Yii::$app->params['themeUrl'];
    $urlWithLayout = Yii::$app->params['layoutUrl'];

    $activeFormSettings = SettingsHelper::activeFormBasic('basic-order-module');
    $form = ActiveForm::begin($activeFormSettings);
    $isModule = !$model->module_id;
?>

<?php if ($model->hasErrors()): ?>
    <div class="alert alert-danger">
        <p><?= $form->errorSummary($model); ?></p>
    </div>
<?php endif; ?>

<div class="row form-group">
    <?= $form->field($model, 'title')
        ->textInput([
            'class' => 'form-control',
        ])
        ->label($model->getAttributeLabel('title'), ['class' => 'col-lg-2 col-form-label']);
    ?>
</div>

<div class="row form-group">
    <?= $form->field($model, 'amount')
        ->textInput([
            'disabled' => $isModule,
            'class' => 'form-control',
            'type' => 'number'
        ])
        ->label($model->getAttributeLabel('amount'), ['class' => 'col-lg-2 col-form-label']);
    ?>
</div>

<div class="row form-group">
    <?= $form->field($model, 'weight')
        ->textInput([
            'disabled' => $isModule,
            'class' => 'form-control', 
            'type' => 'number', 
            'step' => 0.01, 
            'min' => 0
        ])
        ->label($model->getAttributeLabel('weight'), ['class' => 'col-lg-2 col-form-label']);
    ?>
</div>

<div class="row form-group">
    <?= $form->field($model, 'material')
        ->textInput([
            'disabled' => $isModule,
            'class' => 'form-control'
        ])
        ->label($model->getAttributeLabel('material'), ['class' => 'col-lg-2 col-form-label']);
    ?>
</div>

<div class="row form-group">
    <?= $form->field($model, 'laser')
        ->textInput([
            'disabled' => $isModule,
            'class' => 'form-control', 
            'type' => 'number', 
            'step' => 0.01, 
            'min' => 0
        ])
        ->label($model->getAttributeLabel('laser'), ['class' => 'col-lg-2 col-form-label']);
    ?>
</div>

<div class="row form-group">
    <?= $form->field($model, 'bending')
        ->textInput([
            'disabled' => $isModule,
            'class' => 'form-control', 
            'type' => 'number', 
            'step' => 0.01,
            'min' => 0
        ])
        ->label($model->getAttributeLabel('bending'), ['class' => 'col-lg-2 col-form-label']);
    ?>
</div>

<div class="row form-group">
    <?= $form->field($model, 'welding')
        ->textInput([
            'class' => 'form-control', 
            'type' => 'number', 
            'step' => 0.01, 
            'min' => 0
        ])
        ->label($model->getAttributeLabel('welding'), ['class' => 'col-lg-2 col-form-label']);
    ?>
</div>

<div class="row form-group justify-content-center">
    <input type="submit" class="btn btn-primary mr-2" value="<?= Yii::t('app', 'Submit') ?>">
</div>

<?php ActiveForm::end() ?>


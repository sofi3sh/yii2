<?php
use yii\widgets\ActiveForm;

$baseUrl = Yii::$app->params['themeUrl'];
$form = ActiveForm::begin([
    'id' => 'login-form',
    'fieldConfig' => ['template' => '{label}{input}'],
    'options' => ['style' => 'min-width:20rem'],
]);
?>

<?php if (count($formErrors)): ?>
    <div class="nNote alert alert-danger alert-styled-left alert-dismissible">
        <p><?= $form->errorSummary($model); ?></p>
    </div>
<?php endif; ?>

<div class="card mb-0">
    <div class="card-body">
        <div class="text-center mb-3">
            <i class="icon-reading icon-2x text-slate-300 border-slate-300 border-3 rounded-round p-3 mb-3 mt-1"></i>
        </div>

        <div class="form-group form-group-feedback form-group-feedback-left">
            <?= $form->field($model, 'username')->textInput(['placeholder' => $model->getAttributeLabel('username'), 'class' => 'loginEmail form-control'])->label(false); ?>
            <div class="form-control-feedback">
                <i class="icon-user text-muted"></i>
            </div>
        </div>

        <div class="form-group form-group-feedback form-group-feedback-left">
            <?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->getAttributeLabel('password'), 'class' => 'loginPassword form-control'])->label(false); ?>
            <div class="form-control-feedback">
                <i class="icon-lock2 text-muted"></i>
            </div>
        </div>
        
        <div class="form-group">
            <div class="form-check form-check-inline">
                <?= $form->field($model, 'rememberMe')->checkbox(['label' => 'Remember Me', 'class' => 'check']) ?>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block"><?= Yii::t('app', 'Login'); ?> <i class="icon-circle-right2 ml-2"></i>
            </button>
        </div>

    </div>
</div>
<?php ActiveForm::end() ?>


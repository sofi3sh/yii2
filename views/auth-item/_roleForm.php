<?php

use yii\widgets\ActiveForm;
use app\common\helpers\views\SettingsHelper;
use \app\models\RoleStatus;
use app\common\helpers\views\TranslationHelper;

$baseUrl = Yii::$app->params['themeUrl'];
$urlWithLayout = Yii::$app->params['layoutUrl'];
$action = Yii::$app->controller->action->id;

$activeFormSettings = SettingsHelper::activeFormBasic('role-form');
$form = ActiveForm::begin($activeFormSettings);
?>

<?php if ($model->hasErrors()) : ?>
    <div class="alert alert-danger">
        <p><?= $form->errorSummary($model); ?></p>
    </div>
<?php endif; ?>

<div class="row form-group">
    <?= $form->field($model, 'name')
        ->textInput([
            'size' => 60,
            'maxlength' => 255,
            'class' => 'form-control',
            'disabled' => $action === 'update-role'
        ])
        ->label($model->getAttributeLabel('name'), ['class' => 'col-lg-2 col-form-label']);
    ?>
</div>

<?= TranslationHelper::renderFieldWithTranslations(
    'AuthItem',
    'rbac_source_message_id',
    Yii::t('app/models/product', 'Name'),
    $model
) ?>

<div class="row form-group">
    <div class="col-form-label col-lg-2">
        <?= Yii::t('app/models/authItem', 'Available orders with statuses of:') ?>
    </div>
    <div class="col-lg-10">
        <?php foreach ($statuses as $status) : ?>
            <div>
                <?= $form->field(
                    $model,
                    'authItemAccess[' . $status->id . ']'
                )
                    ->checkbox([
                        'checked' => in_array($status->id, $model->visibleOrderStatusesIds),
                        'label' => Yii::t('status', $status->titleSourceMessage->message)
                    ]);
                ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="row form-group justify-content-center">
    <input type="submit" class="btn btn-primary mr-2" value="<?= Yii::t('app', 'Submit') ?>">
</div>
<?php ActiveForm::end() ?>
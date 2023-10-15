<?php
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$baseUrl = Yii::$app->params['themeUrl'];
$urlWithLayout = Yii::$app->params['layoutUrl'];
$actionName = Yii::$app->controller->action->id;

$form = ActiveForm::begin([
    'id' => 'user-form',
    'fieldConfig' => [
        'template' => '{label}<div class="col-lg-10">{input}</div>',
        'options' => [
            'tag' => false,
        ],
    ],
]);
$userRole = $model->getRole();
?>

    <div class="card">
        <div class="card-header">
            <h6 class="card-title"><?= Yii::t('app/models/user', $actionName == 'create' ? 'Create a new user' : 'Update the user') ?></h6>
        </div>
        <div class="card-body">
        <?php if ($model->hasErrors()): ?>
            <div class="alert alert-danger">
                <p><?= $form->errorSummary($model); ?></p>
            </div>
        <?php endif; ?>
            <div class="row form-group">
                <?= $form->field($model, 'full_name')
                    ->textInput(['size' => 60, 'maxlength' => 255, 'class' => 'form-control'])
                    ->label($model->getAttributeLabel('full_name'), ['class' => 'col-lg-2 col-form-label']);
                ?>
            </div>

            <div class="row form-group">
                <?= $form->field($model, 'username')
                    ->textInput(['size' => 60, 'maxlength' => 255, 'class' => 'form-control'])
                    ->label($model->getAttributeLabel('username'), ['class' => 'col-lg-2 col-form-label']);
                ?>
            </div>

             <div class="row form-group">
                <?= $form->field($model, 'role')
                    ->dropDownList(ArrayHelper::map($roles,'name', 'name'), ['value' => ($userRole ? $userRole->roleName : null)])
                    ->label($model->getAttributeLabel('role'), ['class' => 'col-lg-2 col-form-label']);
                ?>
            </div>

             <div class="row form-group">
                <?= $form->field($model, 'client_id')
                    ->dropDownList(
                        ArrayHelper::map($allClients,'id', 'full_name'), 
                        [
                            'value' => $model->client ? $model->client->id : null,
                            'prompt' => Yii::t('app', 'Select')
                        ]
                    )
                    ->label($model->getAttributeLabel('client_id'), ['class' => 'col-lg-2 col-form-label']);
                ?>
            </div>

            <div class="row form-group">
                <?= $form->field($model, 'email')
                    ->textInput(['size' => 60, 'maxlength' => 255, 'class' => 'form-control'])
                    ->label($model->getAttributeLabel('email'), ['class' => 'col-lg-2 col-form-label']);
                ?>
            </div>

            <div class="row form-group">
                <?= $form->field($model, 'phone')
                    ->textInput(['size' => 60, 'maxlength' => 255, 'class' => 'form-control'])
                    ->label($model->getAttributeLabel('phone'), ['class' => 'col-lg-2 col-form-label']);
                ?>
            </div>

            <div class="row form-group">
                <?= $form->field($model, 'password')
                    ->passwordInput(['size' => 60, 'maxlength' => 255, 'class' => 'form-control', 'value' => ''])
                    ->label($model->getAttributeLabel('password'), ['class' => 'col-lg-2 col-form-label']);
                ?>
            </div>

            <div class="row form-group">
                <?= $form->field($model, 'password_confirm')
                    ->passwordInput(['size' => 60, 'maxlength' => 255, 'class' => 'form-control', 'value' => ''])
                    ->label($model->getAttributeLabel('password_confirm'), ['class' => 'col-lg-2 col-form-label']);
                ?>
            </div>

            <div class="row form-group">
                <?= $form->field($model, 'is_active')
                    ->checkbox(['checked' => true, 'class' => 'check', 'label' => ''])
                    ->label($model->getAttributeLabel('is_active'), ['class' => 'col-lg-2 col-form-label']);
                ?>
            </div>

            <?= $form->field($model, 'client_id')->hiddenInput()->label(false); ?>
            
            <div class="row form-group justify-content-center">
                <input type="submit" class="btn btn-primary mr-2" value="<?= Yii::t('app', 'Submit') ?>">
            </div>
        </div>
    </div>

<?php ActiveForm::end() ?>


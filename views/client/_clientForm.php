<?php
    use yii\widgets\ActiveForm;
    use app\common\helpers\views\SettingsHelper;
    use yii\helpers\ArrayHelper;

    $baseUrl = Yii::$app->params['themeUrl'];
    $urlWithLayout = Yii::$app->params['layoutUrl'];

    $activeFormSettings = SettingsHelper::activeFormBasic('client-form');
    $form = ActiveForm::begin($activeFormSettings);
?>

    <div class="card">
        <div class="card-header">
            <h6 class="card-title"><?= Yii::t('app/models/client', 'Create a client') ?></h6>
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
                <?= $form->field($model, 'phone')
                    ->textInput(['size' => 60, 'maxlength' => 255, 'class' => 'form-control'])
                    ->label($model->getAttributeLabel('phone'), ['class' => 'col-lg-2 col-form-label']);
                ?>
            </div>

            <div class="row form-group">
                <?= $form->field($model, 'email')
                    ->textInput(['size' => 60, 'maxlength' => 255, 'class' => 'form-control'])
                    ->label($model->getAttributeLabel('email'), ['class' => 'col-lg-2 col-form-label']);
                ?>
            </div>

            <div class="row form-group">
                <?= $form->field($model, 'referer_user_id')->dropDownList(
                    ArrayHelper::map($users, 'id', 'full_name'),
                    ['prompt' => Yii::t('app', 'Select')]
                    )->label($model->getAttributeLabel('referer_user_id'), ['class' => 'col-lg-2 col-form-label']);
                ?>
            </div>

            <div class="row form-group">
                <?= $form->field($model, 'address_legal')
                    ->textInput(['size' => 60, 'maxlength' => 255, 'class' => 'form-control'])
                    ->label($model->getAttributeLabel('address_legal'), ['class' => 'col-lg-2 col-form-label']);
                ?>
            </div>

            <div class="row form-group">
                <?= $form->field($model, 'address_actual')
                    ->textInput(['size' => 60, 'maxlength' => 255, 'class' => 'form-control'])
                    ->label($model->getAttributeLabel('address_actual'), ['class' => 'col-lg-2 col-form-label']);
                ?>
            </div>

            <div class="row form-group">
                <?= $form->field($model, 'contractor_type')->dropDownList(
                    $model->getContractorTypes(),
                    ['prompt' => Yii::t('app', 'Select')]
                    )->label($model->getAttributeLabel('contractor_type'), ['class' => 'col-lg-2 col-form-label']);
                ?>
            </div>

            <div class="row form-group">
                <?= $form->field($model, 'client_code')
                    ->textInput(['size' => 60, 'maxlength' => 255, 'class' => 'form-control'])
                    ->label($model->getAttributeLabel('client_code'), ['class' => 'col-lg-2 col-form-label']);
                ?>
            </div>

            <div class="row form-group">
                <?= $form->field($model, 'customer_code')
                    ->textInput(['size' => 60, 'maxlength' => 255, 'class' => 'form-control'])
                    ->label($model->getAttributeLabel('customer_code'), ['class' => 'col-lg-2 col-form-label']);
                ?>
            </div>

            <div class="row form-group">
                <?= $form->field($model, 'contact_person')
                    ->textInput(['size' => 60, 'maxlength' => 255, 'class' => 'form-control'])
                    ->label($model->getAttributeLabel('contact_person'), ['class' => 'col-lg-2 col-form-label']);
                ?>
            </div>

            <div class="row form-group">
                <?= $form->field($model, 'responsible_person')
                    ->textInput(['size' => 60, 'maxlength' => 255, 'class' => 'form-control'])
                    ->label($model->getAttributeLabel('responsible_person'), ['class' => 'col-lg-2 col-form-label']);
                ?>
            </div>
            <div class="row form-group justify-content-center">
                <input type="submit" class="btn btn-primary mr-2" value="<?= Yii::t('app', 'Submit') ?>">
            </div>
        </div>
    </div>

<?php ActiveForm::end() ?>


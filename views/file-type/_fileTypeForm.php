<?php
    use yii\widgets\ActiveForm;
    use app\common\helpers\views\SettingsHelper;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use app\common\helpers\views\TranslationHelper;
    use app\models\FileAccess;
    use app\models\FileType;
    use app\common\helpers\views\HtmlHelper;

    $baseUrl = Yii::$app->params['themeUrl'];
    $urlWithLayout = Yii::$app->params['layoutUrl'];

    $activeFormSettings = SettingsHelper::activeFormBasic('file-type-form');
    $form = ActiveForm::begin($activeFormSettings);
    
    echo
        HtmlHelper::javaScript($baseUrl.'/global_assets/js/plugins/tables/datatables/datatables.min.js'),
        HtmlHelper::javaScript($baseUrl.'/global_assets/js/plugins/tables/datatables/extensions/responsive.min.js'),
        HtmlHelper::javaScript($urlWithLayout.'/assets/js/commonSettings/dataTableResponsive.js'),  
        HtmlHelper::javaScript($urlWithLayout.'/assets/js/pages/fileTypeForm.js');
?>

<?php if ($model->hasErrors()): ?>
    <div class="alert alert-danger">
        <p><?= $form->errorSummary($model); ?></p>
    </div>
<?php endif; ?>

            
<?= TranslationHelper::renderFieldWithTranslations(
    'FileType', 
    'title_source_message_id', 
    Yii::t('app/models/fileType', 'Name'),
    $model
) ?>

<div class="row form-group">
    <?= $form->field($model, 'key')
        ->textInput([
            'class'    => 'form-control',
            'disabled' => $model['scenario'] === FileType::SCENARIO_UPDATE
        ])
        ->label($model->getAttributeLabel('key'), ['class' => 'col-lg-2 col-form-label']);
    ?>
</div>

<div class="row form-group">
    <?= $form->field($model, 'allowed_extensions')
        ->textInput(['class' => 'form-control'])
        ->label($model->getAttributeLabel('allowed_extensions'), ['class' => 'col-lg-2 col-form-label']);
    ?>
    <div class="pl-1"><?= Yii::t('app/models/fileType', '(separated by commas: pdf,doc)') ?></div>
</div>

<div class="row form-group">
    <?= $form->field($model, 'entity')->dropDownList(
            $model->entities
        )->label($model->getAttributeLabel('entity'), ['class' => 'col-lg-2 col-form-label']);
    ?>
</div>

<div class="row form-group justify-content-center">
    <input type="submit" class="btn btn-primary mr-2" value="<?= Yii::t('app', 'Submit') ?>">
</div>


<?php if (!empty($statuses)): ?>
    <div class="form-group row">
        <h3><?= Yii::t('app/models/fileType', 'File Access Modes') ?>:</h3>
        <div class="clear"></div>
    </div>

    <table class="table table-hover table-bordered datatable-responsive">
        <thead>
            <tr>
                <td><?= Yii::t('app/models/status', 'Status') ?></td>
                <?php foreach ($userRoles as $role): ?>
                    <td>
                        <span class="form-check">
                            <?= Html::checkbox(
                                'check_all_roles[' . $role->name . ']', 
                                false, 
                                [
                                    'label' => '', 
                                    'class' => 'role_checkbox', 
                                    'id' => 'role_' . $role->name
                                ]
                            ) ?>
                            <?= $role->title ?>
                        </span>
                    </td>
                <?php endforeach; ?>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($statuses as $status): ?>
                <tr class="permission_row">
                    <td>
                        <b><?= $status->title ?></b>
                    </td>
                    <?php foreach ($userRoles as $role): ?>
                        <td>
                            <div class="form-check">
                                <?= $form->field(
                                        $model, 
                                        'fileAccess[' . $status->id . '.' . $role->name . '.' . $accessActions['view']['id'] . ']'
                                    )
                                    ->checkbox([
                                        'checked' => FileAccess::checkAccesses($model->id, $status->id, $role->name, $accessActions['view']['id']), 
                                        'class' => 'check role_' . $role->name,
                                        'label' => $accessActions['view']['title']
                                    ]);
                                ?>
                            </div>
                            <div class="form-check">
                                <?= $form->field(
                                        $model,
                                        'fileAccess[' . $status->id . '.' . $role->name . '.' . $accessActions['edit']['id'] . ']'
                                    )
                                    ->checkbox([
                                        'checked' => FileAccess::checkAccesses($model->id, $status->id, $role->name, $accessActions['edit']['id']), 
                                        'class' => 'check role_' . $role->name,
                                        'label' => $accessActions['edit']['title']
                                    ]);
                                ?>
                            </div>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<?php ActiveForm::end() ?>


<?php
    use yii\widgets\ActiveForm;
    use app\common\helpers\views\SettingsHelper;
    use \app\models\Language;
    use yii\helpers\Html;
    use app\common\helpers\views\TranslationHelper;
    use app\common\helpers\views\HtmlHelper;
    use app\models\NextStatusOrder;
    use app\common\validators\CyrillicValidator;

    $baseUrl = Yii::$app->params['themeUrl'];
    $urlWithLayout = Yii::$app->params['layoutUrl'];

    $activeFormSettings = SettingsHelper::activeFormBasic('status-form');
    $form = ActiveForm::begin($activeFormSettings);

    echo HtmlHelper::javaScript($baseUrl.'/global_assets/js/plugins/pickers/color/spectrum.js');
?>

    <div class="card">
        <div class="card-header">
            <h6 class="card-title"><?= Yii::t('app/models/status', $pageTitle) ?></h6>
        </div>
        <div class="card-body">
            <?php if ($model->hasErrors()): ?>
                <div class="alert alert-danger">
                    <p><?= $form->errorSummary($model); ?></p>
                </div>
            <?php endif; ?>

            <?= TranslationHelper::renderFieldWithTranslations(
                'Status', 
                'title_source_message_id', 
                Yii::t('app/models/product', 'Name'),
                $model
            ) ?>

            <div class="row form-group">
                <?= $form->field($model, 'key')
                    ->textInput(['class' => 'form-control'])
                    ->label($model->getAttributeLabel('key'), ['class' => 'col-lg-2 col-form-label']);
                ?>
            </div>

            <div class="row form-group">
                <?= $form->field($model, 'color')
                    ->textInput(['class' => 'form-control colorpicker-show-input', 'id' => 'color-picker', 'data-preferred-format' => 'hex'])
                    ->label($model->getAttributeLabel('color'), ['class' => 'col-lg-2 col-form-label']);
                ?>
            </div>

            <div class="row form-group">
                <?= $form->field($model, 'order')
                    ->textInput(['class' => 'form-control'])
                    ->label($model->getAttributeLabel('order'), ['class' => 'col-lg-2 col-form-label']);
                ?>
            </div>

            <div class="row form-group">
                <?= $form->field($model, 'allow_comment')
                    ->checkbox([
                        'checked' => (bool) $model->allow_comment, 
                        'class' => 'check',
                         'label' => ''
                    ])
                    ->label(
                        $model->getAttributeLabel('allow_comment'),
                        ['class' => 'col-lg-2 col-form-label']
                    );
                ?>
            </div>

            <?php if(!empty($statuses)): ?>
                <div class="formRow form-group row mt-2">
                    <h4><?= Yii::t('app/models/status', 'Available next statuses') ?>:</h4>
                </div>
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <td><?= Yii::t('app/models/status', 'Status') ?></td>
                            <?php foreach($userRoles as $role):
                                 if (CyrillicValidator::isCyrillicCharacters($role->name)) continue; ?>
                                <td><?= $role->title ?></td>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($statuses as $status): 
                            if(!empty($model->id) && $model->id == $status->id) continue; 
                        ?>
                            <tr>
                                <td><?= $status->title ?></td>
                                <?php foreach($userRoles as $role): ?>
                                    <?php if (CyrillicValidator::isCyrillicCharacters($role->name)) continue; ?>
                                    <td align="center">
                                        <?= $form->field($model, "next_statuses[$status->key" . "." . "$role->name]")
                                            ->checkbox([
                                                'checked' => NextStatusOrder::checkNextStatus($model->key, $status->key, $role->name), 
                                                'class' => 'check',
                                                'label' => ''
                                            ]);
                                        ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <div class="formRow form-group d-flex justify-content-center mt-3">
                <input type="submit" class="btn btn-primary mr-2" value="<?= Yii::t('app', 'Submit') ?>">
            </div>
        </div>
    </div>
<?php ActiveForm::end() ?>

<script type="text/javascript">
    $(function(){
        $('#color-picker').spectrum({
            color: $('#Status_color').val(),
            chooseText: "Ok",
            cancelText: "Cancel",
            showInitial: true,
            change: function(color) {
                $('#Status_color').val(color);
            }
        });
    });
</script>

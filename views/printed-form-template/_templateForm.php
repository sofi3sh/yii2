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

    echo
        Html::cssFile('https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-lite.css'),
        
        HtmlHelper::javaScript($baseUrl.'/global_assets/js/main/jquery.min.js'),
        HtmlHelper::javaScript('https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-lite.js'),
        HtmlHelper::javaScript($urlWithLayout.'/assets/js/pages/printedFormFormula.js'),
        HtmlHelper::javaScript($urlWithLayout.'/assets/js/pages/printedFormTemplate.js');
?>
<?php if ($model->hasErrors()): ?>
    <div class="alert alert-danger">
        <p><?= $form->errorSummary($model); ?></p>
    </div>
<?php endif; ?>

<?= TranslationHelper::renderFieldWithTranslations(
    'PrintedFormTemplate', 
    'title_source_message_id', 
    Yii::t('app', 'Title'),
    $model
) ?>

<div class="row form-group">
    <?= $form->field($model, 'convert_to_csv')
        ->checkbox(['label' => ''])
        ->label($model->getAttributeLabel('convert_to_csv'), ['class' => 'col-lg-2 col-form-label']);
    ?>
</div>

<div class="form-group row">
    <div class="col-lg-7">
        <?= Html::textArea('PrintedFormTemplate[template]', $model->template, ['class' => 'form-control template']) ?>
    </div>
    <div class="col-lg-5">
        <div class="card" style="margin-top: 0">          
            <?= $this->render('../printed-form-formula/_availableMarks', [
                'formulas' => $formulas
            ]); ?>
        </div>
    </div>
</div>

<div class="row form-group justify-content-center">
    <input type="submit" class="btn btn-primary mr-2" value="<?= Yii::t('app', 'Submit') ?>">
</div>

<?php ActiveForm::end() ?>

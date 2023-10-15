<?php
    use yii\widgets\ActiveForm;
    use app\common\helpers\views\SettingsHelper;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use app\common\helpers\views\TranslationHelper;
    use app\common\helpers\views\HtmlHelper;

    $baseUrl = Yii::$app->params['themeUrl'];
    $urlWithLayout = Yii::$app->params['layoutUrl'];

    $activeFormSettings = SettingsHelper::activeFormBasic('file-form');
    $formulasDocsLink = Url::to("https://docs.google.com/document/d/1ydMs1KQpi4veu-CHQjAaUpaIM-E8RDX8IFUrHS3jJL8/edit?usp=sharing");
    $form = ActiveForm::begin($activeFormSettings);

    echo HtmlHelper::javaScript($urlWithLayout.'/assets/js/pages/printedFormFormula.js');
?>

<?php if ($model->hasErrors()): ?>
    <div class="alert alert-danger">
        <p><?= $form->errorSummary($model); ?></p>
    </div>
<?php endif; ?>

<?= TranslationHelper::renderFieldWithTranslations(
    'PrintedFormFormula', 
    'title_source_message_id', 
    Yii::t('app', 'Title'),
    $model
) ?>

<div class="form-group row">
    <div class="col-lg-2 col-form-label">
        <span class="btn bg-teal mr-1"
                title="
                    <?= Yii::t(
                        'app/models/printedFormFormula', 
                        'Key must be in Latin letters or numbers, the words should be separated via lowercase underscores'
                    ) ?>
                ">
            <i class="fa fa-info-circle"></i>
        </span>
        <?= $model->getAttributeLabel('key') ?>
    </div>
    <?= $form->field($model, 'key')
        ->textInput(['class' => 'form-control'])
        ->label(false);
    ?>
</div>

<div class="form-group row">
    <div class="col-lg-1">
        {<b class="key_field"><?= $model->key ?></b>} =
    </div>
    <div class="col-lg-5">
        <div>
            <?= $model->getAttributeLabel('expression') ?>
            <a target="_blank" href="<?= $formulasDocsLink ?>">
                <span class="fas fa-question-circle" style="color: #009688"></span>
            </a>
        </div>
        <?= Html::textArea(
            'PrintedFormFormula[expression]', 
            $model->expression, 
            ['class' => 'form-control', 'style' => 'height:90%']) 
        ?>
    </div>
    <div class="col-lg-6">
        <div class="card" style="margin-top: 27px">
            <?= $this->render('_availableMarks', [
                'formulas' => $formulas
            ]); ?>
        </div>
    </div>
</div>

<div class="row form-group justify-content-center">
    <input type="submit" class="btn btn-primary mr-2" value="<?= Yii::t('app', 'Submit') ?>">
</div>

<?php ActiveForm::end() ?>

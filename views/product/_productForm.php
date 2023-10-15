<?php
    use yii\widgets\ActiveForm;
    use app\common\helpers\views\SettingsHelper;
    use app\common\helpers\views\HtmlHelper;
    use \app\models\Language;
    use \app\models\Product;
    use yii\helpers\Html;
    use app\common\helpers\views\TranslationHelper;
    use app\common\helpers\views\ModelAttributeHelper;

    $baseUrl = Yii::$app->params['themeUrl'];
    $urlWithLayout = Yii::$app->params['layoutUrl'];

    $activeFormSettings = SettingsHelper::activeFormBasic('product-form');
    $form = ActiveForm::begin($activeFormSettings);
    $action = Yii::$app->controller->action->id;
?>

    <?= HtmlHelper::javaScript($urlWithLayout.'/assets/js/pages/productOptionsValidator.js'); ?>

    <div class="card">
        <div class="card-header">
            <h6 class="card-title"><?= Yii::t('app/models/product', 'Create a new product') ?></h6>
        </div>
        <div class="card-body">
            <?php if ($model->hasErrors()): ?>
                <div class="alert alert-danger">
                    <p><?= $form->errorSummary($model); ?></p>
                </div>
            <?php endif; ?>

            <?= TranslationHelper::renderFieldWithTranslations(
                'Product', 
                'title_source_message_id', 
                Yii::t('app/models/product', 'Name'),
                $model
            ) ?>

            <div class="row form-group">
                <?= $form->field($model, 'product_key')
                    ->textInput([
                        'size' => 60,
                        'maxlength' => 255, 
                        'class' => 'form-control', 
                        'disabled' => $action === 'update',
                        'value' => ModelAttributeHelper::getOldAttributeValueOnUpdateAction($model, 'product_key')
                    ])
                    ->label($model->getAttributeLabel('product_key'), ['class' => 'col-lg-2 col-form-label']);
                ?>
            </div>

            <?= $this->render('_productOptionsForm', [
                'availableProductOptions' => $availableProductOptions,
                'model' => $model,
                'form' => $form   
            ]); ?>

            <div class="row form-group justify-content-center">
                <input type="submit" class="btn btn-primary mr-2" value="<?= Yii::t('app', 'Submit') ?>">
            </div>
        </div>
    </div>

<?php ActiveForm::end() ?>


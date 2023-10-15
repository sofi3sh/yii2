<?php
use \yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\common\helpers\views\HtmlHelper;
use Yii;

$defaultQueryParams = [
    'name' => '',
    'expression' => '',
    'key' => ''
];

$queryParams = isset(Yii::$app->request->queryParams['PrintedFormFormula']) ?
    Yii::$app->request->queryParams['PrintedFormFormula'] : $defaultQueryParams;

$urlWithLayout = Yii::$app->params['layoutUrl'];
?>

<?= HtmlHelper::javaScript($urlWithLayout.'/assets/js/pages/printedFormFormula.js'); ?>
<div class="card">
    <div class="card-header">
         <button id="filters-toggle-btn" class="btn btn-primary" style="float: right; z-index: 1">
            <span class="fa fa-filter"></span>
        </button>
        <h6 class="card-title"><?= Yii::t('app/models/printedFormFormula', 'Variables / Formulas'); ?></h6>
        <br>
        <div class="filter-options-container">
            <?php $form = ActiveForm::begin([
                    'method' => 'get',
                    'action' => Url::to('/printed-form-formula/index')
                ]); ?>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <?= $form->field($model, 'name')
                            ->textInput([
                                'class' => 'form-control',
                                'value' => $queryParams['name']
                            ])
                            ->label($model->getAttributeLabel('name'))
                        ?>
                    </div>
                    <div class="form-group col-md-4">
                        <?= $form->field($model, 'key')
                            ->textInput(['class' => 'form-control'])
                            ->label($model->getAttributeLabel('key'))
                        ?>
                    </div>
                    <div class="form-group col-md-4">
                        <?= $form->field($model, 'expression')
                            ->textInput(['class' => 'form-control'])
                            ->label($model->getAttributeLabel('expression'))
                        ?>
                    </div>
                </div>
                <a class="btn btn-danger mr-2" href="<?= Url::to('/printed-form-formula/index') ?>">
                    <?= Yii::t('app', 'Reset') ?>
                </a>
                <input type="submit" class="btn btn-primary mr-2" value="<?= Yii::t('app', 'Submit') ?>">
            <?php $form = ActiveForm::end(); ?>
        </div>
    </div>
    <div class="card-body">
        <?= $this->render('../shared/successAlert'); ?>
        <div class="row mb-3">
            <?= 
                Html::a(
                    '<i class="fa fa-plus mr-1"></i>' . Yii::t('app', 'Create'), 
                    ['printed-form-formula/create'], 
                    [
                        'class' => 'btn bg-teal mt-1 mr-2', 
                        'title' => Yii::t('app', 'Create')
                    ]
                )
            ?>
        </div>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'id',
                [
                    'label' => Yii::t('app', 'Title'),
                    'value' => function ($model) {
                        return $model->title;
                    }
                ],
                [
                    'attribute' => 'key',
                    'value' => function($model) {
                        return '{' . $model->key . '}';
                    }
                ],
                [
                    'format' => 'raw',
                    'attribute' => 'expression',
                    'value' => function($model) {
                        return '<i>' . $model->expression . '</i>';
                    },
                    'contentOptions' => [
                        'class' => 'text-secondary'
                    ]
                ],
                'created_at:datetime',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => Yii::t('app', 'Actions'),
                    'template' => '{view} {update} {delete}',
                    'buttons' => [
                        'update' => function($url, $model) {
                            return Html::a(
                                '<i class="fa fa-edit"></i>', 
                                ['printed-form-formula/update', 'id' => $model->id], 
                                [
                                    'class' => 'btn btn-primary', 
                                    'title' => Yii::t('app', 'Edit')
                                ]
                            );
                        },
                        'delete' => function($url, $model) {
                            return Html::a(
                                '<i class="fa fa-times-circle"></i>', 
                                ['printed-form-formula/delete', 'id' => $model->id], 
                                [
                                    'class' => 'btn btn-danger', 
                                    'title' => Yii::t('app', 'Delete'),
                                    'data' => [
                                        'confirm' => Yii::t('app/models/printedFormFormula', 'Do you want to delete this formula?'),
                                    ],
                                ]
                            );
                        }
                    ]
                ]
            ]
        ]) ?>
    </div>
</div>

<?php

use \yii\grid\GridView;
use yii\helpers\Html;
use app\common\helpers\views\HtmlHelper;

$baseUrl = Yii::$app->params['themeUrl'];
$urlWithLayout = Yii::$app->params['layoutUrl'];
?>

<?= HtmlHelper::javaScript($urlWithLayout . '/assets/js/pages/basicOrderModule.js') ?>

<!-- Modal widow for uploading modules (invisible by default) -->
<div class="modal fade modal-load-new-module" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style='margin-top: 10%'>
    <div class="modal-dialog modal-md modalCenter">
        <div class="modal-content p-3">
            <h3>
                <?= Yii::t('app/models/basicOrderModule', 'Upload new typical fragment') ?>
            </h3>
            <form action="/basic-order-module/upload" method="post" enctype="multipart/form-data" id='modulesDropzone'>
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
                <div>
                    <label for="basic-modules" class="alert alert-primary w-100" style="cursor: pointer">
                        <?= Yii::t('app', 'Click here to select files'); ?>
                    </label>
                    <input id="basic-modules" name="files[]" style="display: none" type="file" multiple required>
                </div>
                <input type="submit" class="btn btn-primary float-right" value="<?= Yii::t('app', 'Submit') ?>">
            </form>
            <ul id="files-list"></ul>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h6 class="card-title"><?= Yii::t('app/models/basicOrderModule', 'Typical fragments') ?></h6>
    </div>
    <div class="card-body">
        <?= $this->render('../shared/successAlert'); ?>
        <?= $this->render('../shared/errorAlert'); ?>
        <div class="row mb-3">
            <?=
                Html::a(
                    '<i class="fa fa-plus mr-1"></i>' . Yii::t('app', 'Load new'),
                    ['file/create'],
                    [
                        'class' => 'btn bg-teal mt-1 mr-2',
                        'title' => Yii::t('app', 'Create'),
                        'data-toggle' => 'modal',
                        'data-target' => '.modal-load-new-module'
                    ]
                )
            ?>
        </div>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'rowOptions' => function($model) {
                if (!$model->module_id) {
                    return ['class' => 'text-secondary'];
                }
            },
            'columns' => [
                'id',
                'title',
                [
                    'format' => 'raw',
                    'label' => Yii::t('app/models/basicOrderModule', 'Belongs to module'),
                    'value' => function ($model) {
                        if ($model->parentModule) {
                            return Html::a(
                                $model->parentModule->title,
                                ['basic-order-module/update', 'id' => $model->parentModule->id]
                            );
                        }
                    }
                ],
                'amount',
                'weight',
                'material',
                'laser',
                'bending',
                'welding',
                'created_at',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => Yii::t('app', 'Actions'),
                    'template' => '<center>{update} {delete}</center>',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            return Html::a(
                                '<i class="fa fa-edit"></i>',
                                ['basic-order-module/update', 'id' => $model->id],
                                [
                                    'class' => 'btn btn-primary',
                                    'title' => Yii::t('app', 'Edit')
                                ]
                            );
                        },
                        'delete' => function ($url, $model) {
                            return Html::a(
                                '<i class="fa fa-trash"></i>',
                                ['basic-order-module/delete', 'id' => $model->id],
                                [
                                    'class' => 'btn btn-danger',
                                    'title' => Yii::t('app', 'Delete'),
                                    'data' => [
                                        'confirm' => Yii::t('app', 'Are you sure?')
                                    ]
                                ]
                            );
                        },
                    ]
                ]
            ]
        ]) ?>
    </div>
</div>
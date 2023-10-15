<?php
use \yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\FileType;
use app\common\helpers\views\ImageHelper;
use app\common\helpers\views\PrintedFormulasHelper;

?>
<div class="card">
    <div class="card-header">
        <h6 class="card-title"><?= Yii::t('app/models/file', 'Files') ?></h6>
    </div>
    <div class="card-body">
        <?= $this->render('../shared/successAlert'); ?>
        <div class="row mb-3">
            <?= 
                Html::a(
                    '<i class="fa fa-plus mr-1"></i>' . Yii::t('app', 'Create'), 
                    ['file/create'], 
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
                [
                    'format' => 'raw',
                    'label' => Yii::t('app/models/fileType', 'Preview'),
                    'value' => function ($model) {
                        return ImageHelper::renderImagePreview($model);
                    }
                ],
                [
                    'label' => Yii::t('app/models/file', 'File ID'),
                    'value' => function ($model) {
                        return $model->id;
                    }
                ],
                [
                    'label' => Yii::t('app/models/fileType', 'Name'),
                    'value' => function ($model) {
                        return $model->fileType->title;
                    }
                ],
                [
                    'label' => Yii::t('app', 'User'),
                    'value' => function ($model) {
                        return $model->user ? $model->user->full_name : '';
                    }
                ],
                'full_origin_name',
                [
                    'label'  => Yii::t('app', 'Mark'),
                    'value' => function($model) {
                        return $model->imageMark ? $model->imageMark->key : '';
                    }
                ],
                'size',
                'created_at:datetime',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => Yii::t('app', 'Actions'),
                    'template' => '{view} {update} {delete}',
                    'buttons' => [
                        'update' => function($url, $model) {
                            return Html::a(
                                '<i class="fa fa-edit"></i>', 
                                ['file/update', 'id' => $model->id], 
                                [
                                    'class' => 'btn btn-primary', 
                                    'title' => Yii::t('app', 'Edit')
                                ]
                            );
                        },
                    ]
                ]
            ]
        ]) ?>
    </div>
</div>

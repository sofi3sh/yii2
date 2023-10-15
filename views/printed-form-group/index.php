<?php
use \yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="card">
    <div class="card-header">
        <h6 class="card-title"><?= Yii::t('app/models/printedFormGroup', 'Template Groups'); ?></h6>
    </div>
    <div class="card-body">
        <?= $this->render('../shared/successAlert'); ?>
        <div class="row mb-3">
            <?= 
                Html::a(
                    '<i class="fa fa-plus mr-1"></i>' . Yii::t('app', 'Create'), 
                    ['printed-form-group/save'], 
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
                'created_at:datetime',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => Yii::t('app', 'Actions'),
                    'template' => '{view} {update} {delete}',
                    'buttons' => [
                        'update' => function($url, $model) {
                            return Html::a(
                                '<i class="fa fa-edit"></i>', 
                                ['printed-form-group/save', 'id' => $model->id], 
                                [
                                    'class' => 'btn btn-primary', 
                                    'title' => Yii::t('app', 'Edit')
                                ]
                            );
                        },
                        'delete' => function($url, $model) {
                            return Html::a(
                                '<i class="fa fa-times-circle"></i>', 
                                ['printed-form-group/delete', 'id' => $model->id], 
                                [
                                    'class' => 'btn btn-danger', 
                                    'title' => Yii::t('app', 'Delete'),
                                    'data' => [
                                        'confirm' => Yii::t('app/models/printedFormGroup', 'Do you want to delete this group?'),
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

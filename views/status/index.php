<?php
use \yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="card">
    <div class="card-header">
        <h6 class="card-title"><?= Yii::t('app/models/status', 'Statuses') ?></h6>
    </div>
    <div class="card-body">
        <?= $this->render('../shared/successAlert'); ?>
        <?= $this->render('../shared/errorAlert'); ?>
        <div class="row mb-3">
            <?= 
                Html::a(
                    '<i class="fa fa-plus mr-1"></i>' . Yii::t('app', 'Create'), 
                    ['status/save'], 
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
                'key',
                'title',
                'order',
                [
                    'label' => Yii::t('app/models/status', 'Сolor'),
                    'format' => 'raw',
                    'value' => function ($model) {
                        return '<div class="text-center p-1" style="color: #fff; background-color:'
                            . $model->color . ';">' . $model->title . '</div>';
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
                                ['status/save', 'id' => $model->id], 
                                [
                                    'class' => 'btn btn-primary', 
                                    'title' => Yii::t('app', 'Edit')
                                ]
                            );
                        },
                        'delete' => function($url, $model) {
                            return Html::a(
                                '<i class="fa fa-trash"></i>', 
                                ['status/delete', 'id' => $model->id], 
                                [
                                    'class' => 'btn btn-danger confirm-delete-alert', 
                                    'title' => Yii::t('app', 'Delete')
                                ]
                            );
                        },
                    ]
                ]
            ]
        ]) ?>
    </div>
</div>

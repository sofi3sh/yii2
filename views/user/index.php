<?php
use \yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="card">
    <div class="card-header">
        <h6 class="card-title"><?= Yii::t('app/models/user', 'List of all users') ?></h6>
    </div>
    <div class="card-body">
        <?= $this->render('../shared/successAlert'); ?>
        <?= $this->render('../shared/errorAlert'); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'id',
                'full_name',
                'username',
                'email',
                'phone',
                'created_at:datetime',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header'=>'Actions',
                    'template' => '{view} {update} {delete}',
                    'visibleButtons' => [
                        'update' => function ($model) {
                            return true;
                         }
                        ],
                        'buttons' => [
                            'update' => function($url, $model) {
                                return Html::a(
                                    '<i class="fa fa-edit"></i>', 
                                    Url::base() . '/user/update/' . $model->id, 
                                    [
                                        'class' => 'btn btn-primary', 
                                        'title' => Yii::t('app', 'Edit')
                                    ]
                                );
                            },
                            'delete' => function($url, $model) {
                                return Html::a(
                                    '<i class="fa fa-trash"></i>', 
                                    Url::base() . '/user/delete/' . $model->id, 
                                    [
                                        'class' => 'btn btn-danger', 
                                        'title' => Yii::t('app', 'Delete'),
                                        'data-confirm' => Yii::t('app/models/user', 'Are you sure you want to delete this User?')
                                    ]
                                );
                            }
                        ]
                ]
            ]
        ]) ?>
    </div>
</div>

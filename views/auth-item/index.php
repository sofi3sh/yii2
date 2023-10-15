<?php
use \yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="card">
    <div class="card-header">
        <h6 class="card-title"><?= Yii::t('app/models/authItem', 'User Roles') ?></h6>
    </div>
    <div class="card-body">
        <?= $this->render('../shared/successAlert'); ?>
        <div class="row mb-3">
            <?= 
                Html::a(
                    '<i class="fa fa-plus mr-1"></i>' . Yii::t('app', 'Create'), 
                    ['/auth-item/create-role'], 
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
                'name',
                [
                    'label' => Yii::t('app/models/authItem', 'Name'),
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
                                ['auth-item/update-role', 'name' => $model->name], 
                                [
                                    'class' => 'btn btn-primary', 
                                    'title' => Yii::t('app', 'Edit')
                                ]
                            );
                        },
                        'delete' => function($url, $model) {
                            return Html::a(
                                '<i class="fa fa-trash"></i>', 
                                ['auth-item/delete-role', 'name' => $model->name], 
                                [
                                    'class' => 'btn btn-danger', 
                                    'title' => Yii::t('app', 'Delete'),
                                    'data-confirm' => Yii::t('app/models/authItem', 'Are you sure you want to delete this Role?')
                                ]
                            );
                        },
                    ]
                ]
            ]
        ]) ?>
    </div>
</div>

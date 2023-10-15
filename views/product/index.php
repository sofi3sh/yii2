<?php
use \yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?= $this->render('../shared/successAlert'); ?>
<div class="card">
    <div class="card-header">
        <h6 class="card-title"><?= Yii::t('app/models/product', 'List of all products') ?></h6>
    </div>
    <div class="card-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'id',
                'title',
                'created_at:datetime',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => Yii::t('app', 'Actions'),
                    'template' => '{view} {update} {delete}',
                    'visibleButtons' => [
                        'update' => function ($model) {
                            return true;
                         }
                        ],
                        'buttons' => [
                            'view' => function($url, $model) {
                                return Html::a(
                                    '<i class="fa fa-eye"></i>', 
                                    ['product/view', 'id' => $model->id], 
                                    [
                                        'class' => 'btn btn-primary', 
                                        'title' => Yii::t('app', 'View')
                                    ]
                                );
                            },
                            'update' => function($url, $model) {
                                return Html::a(
                                    '<i class="fa fa-edit"></i>', 
                                    ['product/update', 'id' => $model->id], 
                                    [
                                        'class' => 'btn btn-primary', 
                                        'title' => Yii::t('app', 'Edit')
                                    ]
                                );
                            },
                            'delete' => function($url, $model) {
                                return Html::a(
                                    '<i class="fa fa-times-circle"></i>', 
                                    ['product/delete', 'id' => $model->id], 
                                    [
                                        'class' => 'btn btn-danger', 
                                        'title' => Yii::t('app', 'Delete'),
                                        'data' => [
                                            'confirm' => Yii::t('app/models/product', 'Do you want to delete this product?'),
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

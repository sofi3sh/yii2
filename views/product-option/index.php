<?php
use \yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="card">
    <div class="card-header">
        <h6 class="card-title"><?= Yii::t('app/models/productOption', 'List of all product options') ?></h6>
    </div>
    <div class="card-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'id',
                'title',
                [
                    'label' => Yii::t('app/models/productOption', 'Product'),
                    'attribute' => 'product_title',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::a(
                            $model->product->title,
                            ['product/view', 'id' => $model->product->id]
                        );
                    }
                  ],
                'option_key',
                'created_at:datetime',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => Yii::t('app', 'Actions'),
                    'template' => '{view} {update} {delete}',
                    'buttons' => [
                        'update' => function($url, $model) {
                            return Html::a(
                                '<i class="fa fa-edit"></i>',
                                ['product-option/update', 'id' => $model->id],
                                [
                                    'class' => 'btn btn-primary', 
                                    'title' => Yii::t('app', 'Edit')
                                ]
                            );
                        },
                        'delete' => function($url, $model) {
                            return Html::a(
                                '<i class="fa fa-trash"></i>',
                                ['product-option/delete', 'id' => $model->id], 
                                [
                                    'class' => 'btn btn-danger', 
                                    'title' => Yii::t('app', 'Delete'),
                                    'data-confirm' => Yii::t('app/models/productOption', 'Are you sure you want to delete this Option?')
                                ]
                            );
                        }
                    ]
                ]
            ]
        ]) ?>
    </div>
</div>

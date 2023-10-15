<?php
use \yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="card">
    <div class="card-header">
        <h6 class="card-title"><?= Yii::t('app/models/client', 'Clients') ?></h6>
    </div>
    <div class="card-body">
        <?= $this->render('../shared/successAlert'); ?>
        <div class="row mb-3">
            <?= 
                Html::a(
                    '<i class="fa fa-plus mr-1"></i>' . Yii::t('app', 'Create'), 
                    ['client/create'], 
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
                'full_name',
                'client_code',
                'customer_code',
                'phone',
                [
                    'label' => Yii::t('app/models/client', 'Contractor Type'),
                    'value' => function ($model) {
                        return ($model->getContractorTypes())[$model->contractor_type];
                    }
                ],
                [
                    'label' => Yii::t('app', 'User'),
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::a(
                            Yii::t('app', 'Create') . ' (' . count($model->users) . ')', 
                            ['user/create', 'client_id' => $model->id], 
                            [
                                'class' => 'btn btn-secondary',
                            ]
                        );
                    }
                ],
                'contact_person',
                'created_at:datetime',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => Yii::t('app', 'Actions'),
                    'template' => '{view} {update} {delete}',
                    'buttons' => [
                        'update' => function($url, $model) {
                            return Html::a(
                                '<i class="fa fa-edit"></i>', 
                                ['client/update', 'id' => $model->id], 
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

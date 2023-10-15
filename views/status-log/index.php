<?php
use \yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\common\helpers\views\SettingsHelper;
use yii\helpers\ArrayHelper;
?>

<div class="card">
    <div class="card-header">
        <h6 class="card-title"><?= Yii::t('app/models/status', 'Status Log') ?></h6>
    </div>
    <div class="card-body">
        <div>
            <?php 
                $activeFormSearchSettings = SettingsHelper::activeFormBasic('product-option-form');
                $formSerch = ActiveForm::begin($activeFormSearchSettings);
            ?>
                <div class="row align-content-center">
                    <div class="col-6">
                        <?= $formSerch->field($model, 'user_id')->dropDownList(
                            ArrayHelper::map($users, 'id', 'full_name'),
                            ['prompt' => Yii::t('app', 'Select')]
                            )->label(Yii::t('app', 'User'), ['class' => 'col-lg-2 col-form-label']);
                        ?>
                    </div>
                    <div class="col-6">
                        <?= $formSerch->field($model, 'order_uuid')
                            ->textInput(['maxlength' => 255, 'class' => 'form-control'])
                            ->label(Yii::t('app/models/order', 'Order #'), ['class' => 'col-lg-2 col-form-label']);
                        ?>
                    </div>
                </div>
                <div class="d-flex justify-content-center mt-3 mb-3">
                    <button class="btn btn-primary mr-2"><?= Yii::t('app', 'Submit') ?></button>
                    <a href="" value="" class="btn btn-danger"><?= Yii::t('app', 'Discard') ?></a>
                </div>
            <?php ActiveForm::end() ?>
        </div>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'label' => Yii::t('app/models/order', 'Order #'),
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->order->uuid;
                    }
                ],
                [
                    'label' => Yii::t('app/models/order', 'User'),
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->user ? $model->user->full_name : '';
                    }
                ],
                [
                    'label' => Yii::t('app/models/order', 'Status'),
                    'format' => 'raw',
                    'value' => function ($model) {
                        return '<div class="text-center p-1" style="color: #fff; background-color:'
                            . $model->status->color . ';">' . $model->status->title . '</div>';
                    }
                ],
                'created_at:datetime',
            ]
        ]) ?>
    </div>
</div>

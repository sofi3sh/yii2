<?php
use yii\helpers\Html;
use app\models\File;
use app\models\ProductOption;
use app\models\OrderProductOption;
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header alpha-success text-success-800 header-elements-inline">
                <h6 class="card-title"><?= Yii::t('app/models/order', 'View The Order') ?></h6>
            </div>
            <div class="card-body">
                <div class="card">
                    <div class="pl-2">
                        <b><?= Yii::t('app/models/order', 'ID') ?>:</b> <?= $order->id; ?>
                    </div>
                </div>

                <div class="card">
                    <div class="pl-2">
                        <b><?= Yii::t('app/models/order', 'Order #') ?>:</b> <?= $order->uuid; ?>
                    </div>
                </div>

                <div class="card">
                    <div class="pl-2">
                        <b><?= Yii::t('app/models/order', 'Client') ?>:</b> <?= $order->client->full_name; ?>
                    </div>
                </div>

                <div class="card">
                    <div class="pl-2">
                        <b><?= Yii::t('app/models/order', 'User') ?>:</b> <?= $order->user->full_name; ?>
                    </div>
                </div>

                <div class="card">
                    <div class="pl-2">
                        <b><?= Yii::t('app/models/order', 'Status') ?>:</b> <?= $order->status->title; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header alpha-success text-success-800 header-elements-inline">
                <h6 class="card-title"><?= Yii::t('app/models/order', 'Product') ?></h6>
            </div>
            <div class="card-body">
                <?php foreach ($order->orderProductOptions as $orderProductOption): ?>
                    <?php if (!$orderProductOption->productOption) continue; ?>
                    <?php if ($orderProductOption->productOption->option_type === ProductOption::getOptionTypeByName('file')) continue; ?>
                    <div class="card">
                        <div class="pl-2">
                            <b><?= $orderProductOption->productOption->title ?>:</b> 
                            <?= OrderProductOption::getFormattedProductOptionValue($orderProductOption) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php if(!empty($order->files)): ?>
        <div class="col-md-12 col-lg-4">
            <div class="card">
                <div class="card-header alpha-success text-success-800 header-elements-inline">
                    <h6 class="card-title"><?= Yii::t('app/models/order', 'Uploaded Images') ?></h6>
                </div>
                <div class="card-body">
                    <?php foreach ($order->files as $key => $file): ?>
                        <div class="card">
                            <div class="card-body">
                                <b><?= $file->fileType->title ?>:</b>
                                <?= 
                                    Html::a(
                                       $file->full_origin_name . ' (' . Yii::t('app', 'Size') . ':' . File::convertBytesToMB($file->size) . ' MB)',
                                       ['file/view', 'id' => $file->id],
                                        [
                                            'class' => 'btn btn-primary', 
                                            'title' => Yii::t('app', 'Size'),
                                            'target' => '_blank'
                                        ]
                                    )
                                ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>

<div class="card">
    <div class="card-header alpha-success text-success-800 header-elements-inline">
        <h6 class="card-title"><?= Yii::t('app/models/status', 'Status Log') ?></h6>
    </div>
    <div class="card-body d-flex flex-column">
        <?php foreach($order->statusLog as $log): ?>
            <div class="card">
                <div class="pl-2">
                    <b><?= $log->status->title ?>:</b>
                    <?= $log->created_at; ?>
                    <?php if ($log->comment) : ?>
                        <div class="card mt-3">
                            <div class="pl-2 d-flex flex-column">
                                <?= Yii::t('app/models/status', 'Comment') ?>: <?= $log->comment; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>


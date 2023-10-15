<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header alpha-success text-success-800 header-elements-inline">
                <h6 class="card-title">
                    <?= Yii::t(
                        'app/models/order', 
                        'View Comments For The Order {uuid}',
                        ['uuid' => $order->uuid])
                    ?>
                </h6>
            </div>
            <div class="card-body d-flex flex-column">
                <?= empty($statusLogs) ? Yii::t('app/models/status', 'No comments Found') : '' ?>
                <?php foreach($statusLogs as $log): ?>
                    <div class="card">
                        <div class="card-body">
                            <p>
                                <b><?= Yii::t('app/models/order', 'Status') ?>:</b> 
                                <?= $log->status->title ?>
                            </p>
                            <p>
                                <b><?= Yii::t('app/models/order', 'Created At') ?>:</b> 
                                <?= $log->created_at ?>
                            </p>
                            <p>
                                <b><?= Yii::t('app', 'Created by') ?>:</b> <?= $log->user->full_name ?>
                            </p>
                            <?php if ($log->commentReason) : ?>
                                <p>
                                    <b><?= Yii::t('app/models/status', 'Reason') ?>:</b> <?= $log->commentReason->title; ?>
                                </p>
                            <?php endif; ?>
                            <p>
                                <b><?= Yii::t('app/models/status', 'Comment') ?>:</b> <?= $log->comment; ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>


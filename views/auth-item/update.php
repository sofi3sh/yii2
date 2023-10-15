<div class="card">
    <div class="card-header">
        <h6 class="card-title"><?= Yii::t('app/models/authItem', 'Update The User') ?></h6>
    </div>
    <div class="card-body">         
        <?= $this->render('../shared/successAlert'); ?>
        <?= $this->render('_roleForm', [
            'model' => $model,
            'statuses' => $statuses
            ]); ?>
    </div>
</div>

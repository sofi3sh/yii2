<div class="card">
    <div class="card-header">
        <h6 class="card-title"><?= Yii::t('app/models/fileType', 'Create a file type') ?></h6>
    </div>
    <div class="card-body">
        <?= $this->render('_fileTypeForm', [
            'model' => $model,
            'statuses' => $statuses,
            'userRoles' => $userRoles,
            'accessActions' => $accessActions
        ]); ?>
    </div>
</div>



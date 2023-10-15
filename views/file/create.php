<div class="card">
    <div class="card-header">
        <h6 class="card-title"><?= Yii::t('app/models/file', 'Create a new file') ?></h6>
    </div>
    <div class="card-body">
        <?= $this->render('_fileForm', [
            'model' => $model,
            'fileTypes' => $fileTypes
        ]); ?>
    </div>
</div>



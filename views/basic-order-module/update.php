<div class="card">
    <div class="card-header">
        <h6 class="card-title"><?= Yii::t('app/models/basicOrderModule', 'Update the typical fragment') ?></h6>
    </div>
    <div class="card-body">
        <?= $this->render('_basicOrderModuleForm', [
            'model' => $basicModule
        ]); ?>
    </div>
</div>



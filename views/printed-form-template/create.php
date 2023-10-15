<div class="card">
    <div class="card-header">
        <h6 class="card-title"><?= Yii::t('app/models/printedFormTemplate', 'Create a new template') ?></h6>
    </div>
    <div class="card-body">
        <?= $this->render('_templateForm', [
            'model' => $model,
            'formulas' => $formulas
        ]); ?>
    </div>
</div>



<div class="card">
    <div class="card-header">
        <h6 class="card-title"><?= Yii::t('app/models/printedFormFormula', 'Create a new formula') ?></h6>
    </div>
    <div class="card-body">
        <?= $this->render('_formulaForm', [
            'model' => $model,
            'formulas' => $formulas
        ]); ?>
    </div>
</div>



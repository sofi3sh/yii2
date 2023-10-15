<div class="card-header">
    <h6 class="card-title" style="margin-bottom: 10px;">
        <span 
            class="btn bg-teal mr-2" 
            title="
                <?= Yii::t(
                    'app/models/printedFormFormula', 
                    'Click on a tag to copy it'
                ) ?>
            ">
            <i class="fa fa-info-circle"></i>
        </span>
        <?= Yii::t('app/models/printedFormFormula', 'Available marks') ?>
    </h6>
    <div class="card-body" style="max-height: 365px;overflow-y: scroll;padding: 13px">
        <?php foreach ($formulas as $formula): ?>
            <div class="mb-2">
                <button 
                    data-title="<?= Yii::t('app/models/printedFormFormula', 'Copy to clipboard: Ctrl+C, Enter') ?>"
                    data-formula="<?=$formula->key?>"
                    class="printed-form-mark btn"
                >
                    <span class="badge badge-<?= $formula->is_system ? 'info' : 'success'; ?>"><?= $formula->title ?></span> -
                    <span class="badge badge-secondary">{<?= $formula->key ?>}</span>
                </button>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h6 class="card-title"><?= Yii::t('app/models/product', 'Product {title}', [
            'title' => $model->title
        ]) ?></h6>
    </div>
    <div class="card-body">
        <div class="card-body">
            <b><?= $model->getAttributeLabel('title') ?>:</b>
            <?= $model->title ?>
        </div>

        <div class="card-body">
            <b><?= $model->getAttributeLabel('created_at') ?>:</b>
            <?= $model->created_at ?>
        </div>
    </div>
</div>

<?= $this->render('_productOptions', [
    'model' => $model,
    'availableProductOptions' => $availableProductOptions
]); ?>

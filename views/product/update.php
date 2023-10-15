<?= $this->render('../shared/successAlert'); ?>
<?= $this->render('_productForm', [
    'model' => $model,
    'availableProductOptions' => $availableProductOptions
]); ?>

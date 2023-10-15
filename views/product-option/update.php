<?= $this->render('../shared/successAlert'); ?>
<?= $this->render(
    '_productOptionForm', 
    [
        'title' => Yii::t('app/models/productOption', 'Update the product option'),
        'model' => $model, 
        'productOptions' => $productOptions, 
        'products' => $products,
        'availablePreviousOptions' => $availablePreviousOptions
    ]); 
?>

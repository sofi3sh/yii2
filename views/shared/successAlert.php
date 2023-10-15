<?php if(Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-styled-left alert-arrow-left alert-dismissible">
        <p><?= Yii::$app->session->getFlash('success') ?></p>
    </div>
<?php endif; ?>

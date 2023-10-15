<?php if(Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger alert-dismissible">
        <p><?= Yii::$app->session->getFlash('error') ?></p>
    </div>
<?php endif; ?>

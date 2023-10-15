<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
        <title><?= Yii::t('app', 'Metalpark'); ?></title>

        <?php
        use yii\helpers\Html;

        $baseUrl = Yii::$app->params['themeUrl'];
        $urlWithLayout = Yii::$app->params['layoutUrl'];
        
        echo
            Html::cssFile('https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900'),
            Html::cssFile($urlWithLayout.'/assets/css/bootstrap.min.css');
        ?>
    </head>

    <body class="navbar-top">
        <?php $this->beginBody() ?>
            <div class="content">
                <?= $content; ?>
            </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>


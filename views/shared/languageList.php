<?php 
use yii\helpers\Url;
use app\models\Language;
use yii\helpers\Html;

$languages = Language::LANGUAGES_LIST;
?>

<ul class="navbar-nav ml-md-auto">
    <li class="nav-item dropdown dropdown-user">
        <a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
            <span><?= Yii::t('app', 'Language'); ?></span>
        </a>
                        
        <div class="dropdown-menu dropdown-menu-right">
            <?php foreach($languages as $languageId => $languageParams ) : ?>
                <?= Html::a(
                    $languageParams['title'], 
                    ["/language/switch/$languageId"], 
                    [
                        'class' => 'dropdown-item', 
                        'title' => $languageParams['title'],
                        'data-confirm' => Yii::t('app', 'All unsaved changes will be lost. Would you like to proceed with this action?')
                    ]
                ); ?>
            <?php endforeach; ?>
        </div>
    </li>
</ul>

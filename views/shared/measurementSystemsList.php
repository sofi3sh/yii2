<?php 
use yii\helpers\Url;
use app\models\MeasurementSystem;
use yii\helpers\Html;

$measurementSystems = MeasurementSystem::MEASUREMENT_SYSTEMS_LIST;
$userMeasurementSystemId = \Yii::$app->user->identity->settings->measurement_system_id;
?>

<ul class="navbar-nav">
    <li class="nav-item dropdown dropdown-user">
        <a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
            <span><?= Yii::t('app', 'Measurement System'); ?></span>
        </a>
                        
        <div class="dropdown-menu dropdown-menu-right">
            <?php foreach($measurementSystems as $systemId => $systemParams ) : ?>
                <?= Html::a(
                    Yii::t('app', $systemParams['title']), 
                    ['/measurement-system/switch', 'id' => $systemId], 
                    [
                        'class' => 'dropdown-item ' . ($systemId == $userMeasurementSystemId ? 'bg-primary' : ''), 
                        'title' => $systemParams['title'],
                    ]
                ); ?>
            <?php endforeach; ?>
        </div>
    </li>
</ul>

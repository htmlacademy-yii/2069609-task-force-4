<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\models\User;
use yii\bootstrap5\Html;

AppAsset::register($this);
//$this->registerJsFile("@web/js/main.js");

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => '@web/favicon.ico']);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <?php $this->registerCsrfMetaTags() ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <script src="https://api-maps.yandex.ru/2.1/?apikey=<?= Yii::$app->geocoder->apiKey ?>&lang=ru_RU"
            type="text/javascript">
    </script>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody()?>
<header class="page-header">
    <nav class="main-nav">
        <a href='#' class="header-logo">
            <img class="logo-image" src="<?=Yii::$app->request->baseUrl; ?>/img/logotype.png" width=227 height=60 alt="taskforce"/>
        </a>
        <?php if (!Yii::$app->user->isGuest): ?>
            <div class="nav-wrapper">
                <ul class="nav-list">
                    <li class="list-item list-item--active">
                        <a class="link link--nav" >Новое</a>
                    </li>
                    <li class="list-item">
                        <a href="#" class="link link--nav" >Мои задания</a>
                    </li>
                    <li class="list-item">
                        <a href="<?= Yii::$app->urlManager->createUrl('tasks/create') ?>" class="link link--nav" >Создать задание</a>
                    </li>
                    <li class="list-item">
                        <a href="#" class="link link--nav" >Настройки</a>
                    </li>
                </ul>
            </div>
        <?php endif; ?>
    </nav>
    <?php if (!Yii::$app->user->isGuest): ?>
        <div class="user-block">
            <a href="#">
                <img class="user-photo" src="<?=Yii::$app->request->baseUrl; ?>/img/man-glasses.png" width="55" height="55" alt="Аватар">
            </a>
            <div class="user-menu">
                <p class="user-name"><?= User::findOne(Yii::$app->user->id)->name; ?></p>
                <div class="popup-head">
                    <ul class="popup-menu">
                        <li class="menu-item">
                            <a href="#" class="link">Настройки</a>
                        </li>
                        <li class="menu-item">
                            <a href="#" class="link">Связаться с нами</a>
                        </li>
                        <li class="menu-item">
                            <a href="<?= Yii::$app->urlManager->createUrl('user/logout') ?>" class=link">Выход из системы</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>
</header>

<div class="main-container">
    <?= $content ?>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
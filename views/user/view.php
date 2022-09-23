<?php
/** @var yii\web\View $this */
/** @var app\models\User $user */
/** @var app\models\ExecutorCategory $categories */
/** @var app\models\Response $responses */

use app\models\Task;

?>

<main class="main-content container">
    <div class="left-column">
        <h3 class="head-main"><?=$user->name ?></h3>
        <div class="user-card">
            <div class="photo-rate">
                <img class="card-photo" src="<?php echo Yii::$app->request->baseUrl; ?>/img/man-glasses.png" width="191" height="190" alt="Фото пользователя">
                <div class="card-rate">
                    <div class="stars-rating small">
                    <?php $i = 0; while ($i < round($user->rating)) { ?>
                        <span class="fill-star">&nbsp;</span>
                        <?php $i++; } ?>
                    <?php while ($i < 5) { ?>
                        <span>&nbsp;</span>
                        <?php $i++; } ?>
                    </div>
                    <span class="current-rate"><?=$user->rating; ?></span>
                </div>
            </div>
            <p class="user-description">
                <?=$user->description ?>
            </p>
        </div>
        <div class="specialization-bio">
            <div class="specialization">
                <p class="head-info">Специализации</p>
                <ul class="special-list">
                    <?php foreach ($categories as $category): ?>
                    <li class="special-item">
                        <a href="#" class="link link--regular"><?=$category->category->name ?></a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="bio">
                <p class="head-info">Био</p>
                <p class="bio-info"><span class="country-info">Россия</span>, <span class="town-info"><?=$user->city->name ?></span>, <span class="age-info">30</span> лет</p>
            </div>
        </div>
        <?php if ($responses): ?>
        <h4 class="head-regular">Отзывы заказчиков</h4>
        <?php foreach ($responses as $response): ?>
        <div class="response-card">
            <img class="customer-photo" src="<?php echo Yii::$app->request->baseUrl; ?>/img/man-coat.png" width="120" height="127" alt="Фото заказчиков">
            <div class="feedback-wrapper">
                <p class="feedback"><?=$response->feedback ?></p>
                <p class="task">Задание «<a href="#" class="link link--small"><?=$response->task->name ?></a>» выполнено</p>
            </div>
            <div class="feedback-wrapper">
                <div class="stars-rating small"><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span>&nbsp;</span></div>
                <p class="info-text"><span class="current-time">25 минут </span>назад</p>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="right-column">
        <div class="right-card black">
            <h4 class="head-card">Статистика исполнителя</h4>
            <dl class="black-list">
                <dt>Всего заказов</dt>
                <dd><?= Task::getCountDoneTask($user->id) ?> выполнено, <?= Task::getCountFailedTask($user->id) ?> провалено</dd>
                <dt>Место в рейтинге</dt>
                <dd>25 место</dd>
                <dt>Дата регистрации</dt>
                <dd><?=date('d.m.y', strtotime($user->dt_add)) ?></dd>
                <dt>Статус</dt>
                <dd><?=$user->getStatusLabel() ?></dd>
            </dl>
        </div>
        <div class="right-card white">
            <h4 class="head-card">Контакты</h4>
            <ul class="enumeration-list">
                <li class="enumeration-item">
                    <a href="#" class="link link--block link--phone"><?=$user->phone ?></a>
                </li>
                <li class="enumeration-item">
                    <a href="#" class="link link--block link--email"><?=$user->email ?></a>
                </li>
                <?php if (!$user->telegram): ?>
                <li class="enumeration-item">
                    <a href="#" class="link link--block link--tg"><?=$user->telegram ?></a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</main>

<?php

/** @var app\models\Task $task*/
/** @var yii\web\View $this */
/** @var RespondForm $model */
/** @var RefuseForm $modelRefuse */
/** @var CompleteForm $modelComplete */


use app\models\Response;
use app\models\Task;
use app\widgets\TaskActionWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\widgets\RatingWidget;
use Delta\TaskForce\TaskAction;
use app\models\forms\RespondForm;
use app\models\forms\RefuseForm;
use app\models\forms\CompleteForm;
use yii\widgets\ActiveForm;
use kartik\rating\StarRating;

?>
<main class="main-content container">
    <div class="left-column">
        <div class="head-wrapper">
            <h3 class="head-main"><?=$task->description ?></h3>
            <p class="price price--big"><?=$task->budget ?> ₽</p>
        </div>
        <p class="task-description">
            <?=$task->details ?>
        </p>

        <?php
        $taskAction = new TaskAction($task, Yii::$app->user->id);
        $actionObject = $taskAction->getAvailableActions(); ?>
        <?= $actionObject !== null ? TaskActionWidget::widget(['actionObject' => $actionObject]) : ''; ?>

        <div class="task-map">
            <img class="map" src="<?php echo Yii::$app->request->baseUrl; ?>/img/map.png"  width="725" height="346" alt="Новый арбат, 23, к. 1">
            <p class="map-address town">Москва</p>
            <p class="map-address">Новый арбат, 23, к. 1</p>
        </div>


        <?php if ((Yii::$app->user->id === $task->user_id) || $task->getResponseForUser(Yii::$app->user->id, $task->id)): ?>
            <h4 class="head-regular">Отклики на задание</h4>
            <?php foreach ($task->responses as $response): ?>
                <?php if (Yii::$app->user->id === $response->user_id || Yii::$app->user->id === $task->user_id): ?>
                    <div class="response-card">
                        <img class="customer-photo" src="<?= Yii::$app->request->baseUrl; ?>/img/man-glasses.png" width="146" height="156" alt="Фото заказчиков">
                        <div class="feedback-wrapper">
                            <a href="<?= Url::to(['user/view', 'id' => $response->user->id]) ?>" class="link link--block link--big"><?=$response->user->name ?></a>

                            <div class="response-wrapper">
                                    <div class="stars-rating small">
                                        <?= RatingWidget::widget(['rating' => $response->user->rating]) ?>
                                    </div>
                                    <p class="reviews"><?=$response->user->getCountTaskByStatus(Task::STATUS_NEW) + $response->user->getCountTaskByStatus(Task::STATUS_FAILED)?> <span>отзыва</span></p>
                            </div>

                            <p class="response-message">
                                <?=$response->comment ?>
                            </p>
                        </div>
                        <div class="feedback-wrapper">
                            <p class="info-text"><span class="current-time"><?=Yii::$app->formatter->asRelativeTime($response->date_add) ?></span></p>
                            <p class="price price--small"><?=$response->price ?> ₽</p>
                        </div>
                        <?php if (Response::isActionResponseVisiable($task->user_id, $task->status, $response->status)): ?>
                            <div class="button-popup">
                                <a href="<?= Yii::$app->urlManager->createUrl(['tasks/agree', 'id' => $response->id]) ?>" class="button button--blue button--small">Подтвердить</a>
                                <a href="<?= Yii::$app->urlManager->createUrl(['tasks/disagree', 'id' => $response->id]) ?>" class="button button--orange button--small">Отказать</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="right-column">
        <div class="right-card black info-card">
            <h4 class="head-card">Информация о задании</h4>
            <dl class="black-list">
                <dt>Категория</dt>
                <dd><?=$task->category->name ?></dd>
                <dt>Дата публикации</dt>
                <dd><?=Yii::$app->formatter->asRelativeTime($task->date_of_publication) ?></dd>
                <dt>Срок выполнения</dt>
                <dd><?=Yii::$app->formatter->asDatetime($task->date_of_execution, "php:d-m-Y"); ?></dd>
                <dt>Статус</dt>
                <dd><?= Task::TASK_STATUS_LABELS[Task::STATUS_NEW] ?></dd>
            </dl>
        </div>
        <div class="right-card white file-card">
            <h4 class="head-card">Файлы задания</h4>
            <ul class="enumeration-list">
                <li class="enumeration-item">
                    <a href="#" class="link link--block link--clip">my_picture.jpg</a>
                    <p class="file-size">356 Кб</p>
                </li>
                <li class="enumeration-item">
                    <a href="#" class="link link--block link--clip">information.docx</a>
                    <p class="file-size">12 Кб</p>
                </li>
            </ul>
        </div>
    </div>

</main>


<section class="pop-up pop-up--refusal pop-up--close">
    <div class="pop-up--wrapper">
        <?php $form = ActiveForm::begin([
            'id' => 'refuse-form',
            'action' => ['/tasks/refuse', 'id_task' => $task->id],
        ]); ?>
            <h4>Отказ от задания</h4>
            <p class="pop-up-text">
                <b>Внимание!</b><br>
            Вы собираетесь отказаться от выполнения этого задания.<br>
            Это действие плохо скажется на вашем рейтинге и увеличит счетчик проваленных заданий.
            </p>
            <div class="button-container">
                <button class="button--close" type="button">Закрыть окно</button>
                <?= $form->field($modelRefuse, 'proof')->hiddenInput(); ?>
            </div>
            <input type="submit" class="button button--pop-up button--orange" value="Отказаться">
        <?php ActiveForm::end(); ?>
    </div>
</section>

<section class="pop-up pop-up--completion pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Завершение задания</h4>
        <p class="pop-up-text">
            Вы собираетесь отметить это задание как выполненное.
            Пожалуйста, оставьте отзыв об исполнителе и отметьте отдельно, если возникли проблемы.
        </p>
        <div class="completion-form pop-up--form regular-form">
            <?php $form = ActiveForm::begin([
                'id' => 'refuse-form',
                'enableAjaxValidation' => true,
                'action' => ['/tasks/complete', 'id_task' => $task->id],
            ]); ?>
                <div class="form-group">
                    <?= $form->field($modelComplete, 'feedback', ['options' => ['class' => 'control-label']])->textarea(); ?>
                </div>
                <?=$form->field($modelComplete, 'score')->widget(StarRating::class, ['pluginOptions' => [
                    'showClear' => false,
                    'showCaption' => false,
                    'size'=>'md',
                    'min' => 0,
                    'max' => 5,
                    'step' => 1,
                    'language' => 'ru',
                ]]); ?>
                <input type="submit" class="button button--pop-up button--blue" value="Завершить">
            <?php ActiveForm::end(); ?>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>

<section class="pop-up pop-up--act_response pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Добавление отклика к заданию</h4>
        <p class="pop-up-text">
            Вы собираетесь оставить свой отклик к этому заданию.
            Пожалуйста, укажите стоимость работы и добавьте комментарий, если необходимо.
        </p>
        <div class="addition-form pop-up--form regular-form">
            <?php $form = ActiveForm::begin([
                'id' => 'respond-form',
                'action' => ['/tasks/respond', 'id_task' => $task->id],
                'enableAjaxValidation' => true,
                ]); ?>

            <div class="form-group">
                <?= $form->field($model, 'price', ['options' => ['class' => 'control-label']])->textarea(); ?>
                <?= $form->field($model, 'comment', ['options' => ['class' => 'form-group control-label']])->textarea(); ?>
            </div>
            <input type="submit" class="button button--pop-up button--blue" value="Завершить">
            <?php ActiveForm::end(); ?>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
<div class="overlay"></div>


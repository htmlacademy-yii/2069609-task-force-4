<?php

use app\models\forms\TaskSearchForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Task $tasks */
/** @var app\models\Category $categories */
/** @var TaskSearchForm $model */
?>

<main class="main-content container">
    <div class="left-column">
        <h3 class="head-main head-task">Новые задания</h3>
        <?php foreach ($tasks as $task): ?>
        <div class="task-card">
            <div class="header-task">
                <a  href="#" class="link link--block link--big"><?=$task->description ?></a>
                <p class="price price--task"><?=$task->budget ?> ₽</p>
            </div>
            <p class="info-text"><span class="current-time"><?=Yii::$app->formatter->asRelativeTime(strtotime($task->date_of_publication)) ?></span></p>
            <p class="task-text"><?=$task->details ?>
            </p>
            <div class="footer-task">
                <p class="info-text town-text"><?=$task->city->name ?></p>
                <p class="info-text category-text"><?=$task->category->name ?></p>
                <a href="<?= Url::to(['tasks/view', 'id' => $task->id]) ?>" class="button button--black">Смотреть Задание</a>
            </div>
        </div>
        <?php endforeach; ?>

        <div class="pagination-wrapper">
            <ul class="pagination-list">
                <li class="pagination-item mark">
                    <a href="#" class="link link--page"></a>
                </li>
                <li class="pagination-item">
                    <a href="#" class="link link--page">1</a>
                </li>
                <li class="pagination-item pagination-item--active">
                    <a href="#" class="link link--page">2</a>
                </li>
                <li class="pagination-item">
                    <a href="#" class="link link--page">3</a>
                </li>
                <li class="pagination-item mark">
                    <a href="#" class="link link--page"></a>
                </li>
            </ul>
        </div>
    </div>
    <div class="right-column">
        <div class="right-card black">
            <div class="search-form">
                <?php $form = ActiveForm::begin([
                    'id' => 'task-form'
                ]); ?>
                <?php
                echo $form->field($model, 'categories', ['options' => ['class' => 'head-card']])->checkboxList(ArrayHelper::map($categories, 'id', 'name'), ['class' => 'form-group checkbox-wrapper control-label']); ?>
                <p class="head-card">Дополнительно</p><br>
                <?php
                echo $form->field($model, 'withoutResponses')->checkbox(['class' => 'form-group control-label']);
                echo $form->field($model, 'isDistant')->checkbox(['class' => 'form-group control-label']);
                echo $form->field($model, 'period', ['options' => ['class' => 'head-card']])->dropDownList(TaskSearchForm::SEARCH_INTERVAL, ['class' => 'form-group', 'options'=>[TaskSearchForm::KEY_ALL_TASKS => ['Selected'=>true]]]) ?>
                <input type="submit" class="button button--blue" value="Искать">
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</main>
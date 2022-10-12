<?php

use app\models\Category;
use app\models\forms\TaskCreateForm;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var TaskCreateForm $model */
?>
<main class="main-content main-content--center container">
    <div class="add-task-form regular-form">

        <?php $form = ActiveForm::begin([
            'id' => 'task-create-form',
        ]); ?>
            <h3 class="head-main head-main">Публикация нового задания</h3>
            <?= $form->field($model, 'description', ['options' => ['class' => 'form-group control-label']]); ?>
            <?= $form->field($model, 'details', ['options' => ['class' => 'form-group control-label']])->textarea(); ?>
            <?= $form->field($model, 'category', ['options' => ['class' => 'head-card']])->dropDownList(ArrayHelper::map(
                Category::find()->all(), 'id', 'name'), ['class' => 'form-group control-label']); ?>
            <?= $form->field($model, 'location', ['options' => ['class' => 'form-group control-label']])->input('location', ['class' => 'location-icon']); ?>

            <div class="half-wrapper">
                <?= $form->field($model, 'budget', ['options' => ['class' => 'form-group control-label']])->input('budget', ['class' => 'budget-icon']); ?>
                <?= $form->field($model, 'dateOfExecution', ['options' => ['class' => 'form-group control-label']])->input('date'); ?>
            </div>

            <?= $form->field($model, 'files[]')->fileInput(['multiple' => true, 'class' => 'new-file form-label', 'placeholder' => 'Добавить новый файл']); ?>
            <input type="submit" class="button button--blue" value="Опубликовать">
        <?php ActiveForm::end(); ?>
    </div>
</main>
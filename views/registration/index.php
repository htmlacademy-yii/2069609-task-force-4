<?php

use app\models\forms\RegistrationForm;
use app\models\City;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\base\ErrorException;

/** @var yii\web\View $this */
/** @var RegistrationForm $model */
?>

<main class="container container--registration">
    <div class="center-block">
        <div class="registration-form regular-form">
            <?php $form = ActiveForm::begin([
                'id' => 'registration-form'
            ]); ?>
                <h3 class="head-main head-task">Регистрация нового пользователя</h3>
                <?= $form->field($model, 'name', ['options' => ['class' => 'form-group']]) ?>
                <div class="half-wrapper">
                    <?= $form->field($model, 'email', ['options' => ['class' => ' form-group']]) ?>
                    <?= $form->field($model, 'city', ['options' => ['class' => 'form-group control-label']])->dropDownList(ArrayHelper::map(City::find()->all(), 'id', 'name'), ['class' => 'form-group checkbox-wrapper control-label']) ?>
                </div>
                <?= $form->field($model, 'password', ['options' => ['class' => 'half-wrapper form-group control-label']])->passwordInput() ?>
                <?= $form->field($model, 'passwordRepeat', ['options' => ['class' => 'half-wrapper form-group control-label']])->passwordInput() ?>
                <?= $form->field($model, 'isExecutor', ['options' => ['class' => 'form-group']])->checkbox(['class' => 'control-label checkbox-label']) ?>

                <input type="submit" class="button button--blue" value="Создать аккаунт">
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</main>

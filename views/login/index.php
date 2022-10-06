<?php

use yii\widgets\ActiveForm;
use app\models\forms\LoginForm;

/** @var yii\web\View $this */
/** @var LoginForm $model */
/* @var ActiveForm $form */

?>

<section class="modal enter-form form-modal" id="enter-form">
    <h2>Вход/Проверка на сайт</h2>

    <?php $form = ActiveForm::begin([
            'id' => 'login-form'
    ]); ?>

        <?= $form->field($model, 'email')->input('email', ['class' => 'input input-middle']); ?>
        <?= $form->field($model, 'password')->passwordInput(['class' => 'input input-middle']); ?>

        <button class="button" type="submit">Войти</button>
    <?php ActiveForm::end(); ?>

    <button class="form-modal-close" type="button">Закрыть</button>
</section>


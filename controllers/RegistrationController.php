<?php

namespace app\controllers;
use app\models\forms\RegistrationForm;
use app\models\User;
use Yii;
use yii\web\Controller;

class RegistrationController extends Controller
{
    public function actionIndex(){
        $userForm = new RegistrationForm();
        if (Yii::$app->request->getIsPost()) {
            $userForm->load(Yii::$app->request->post());
            if ($userForm->validate()) {
                $userForm->createUser();
                $this->goHome();
            }
        }
        return $this->render('index', ['model' => $userForm]);
    }
}
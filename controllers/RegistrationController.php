<?php

namespace app\controllers;
use app\models\forms\RegistrationForm;
use app\models\User;
use Yii;
use yii\web\Controller;

class RegistrationController extends Controller
{
    public function actionIndex(){
        $user = new RegistrationForm();
        if (Yii::$app->request->getIsPost()) {
            $user->load(Yii::$app->request->post());
            if ($user->validate()) {
                $user->createUser();
                $this->goHome();
            }
        }
        return $this->render('index', ['model' => $user]);
    }
}
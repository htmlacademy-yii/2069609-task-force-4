<?php

namespace app\controllers;
use app\models\forms\RegistrationForm;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class RegistrationController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['?']
                    ]
                ]
            ]
        ];
    }

    public function actionIndex(){
        $userForm = new RegistrationForm();
        if (Yii::$app->request->getIsPost()) {
            $userForm->load(Yii::$app->request->post());
            if ($userForm->validate()) {
                $userForm->createUser();
                return $this->goHome();
            }
        }
        return $this->render('index', ['model' => $userForm]);
    }
}
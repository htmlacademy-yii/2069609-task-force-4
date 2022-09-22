<?php
namespace app\controllers;
use app\models\User;
use yii\web\Controller;

class UserController extends Controller
{
    public function actionView($id)
    {
        $user = User::findOne($id);
        return $this->render('view');
    }
}
<?php
namespace app\controllers;
use app\models\ExecutorCategory;
use app\models\User;
use yii\web\Controller;

class UserController extends Controller
{
    public function actionView($id)
    {
        $user = User::findOne($id);
        $categories = ExecutorCategory::findAll(['user_id' => $id]);
        return $this->render('view', [
            'user' => $user,
            'categories' => $categories
        ]);
    }
}
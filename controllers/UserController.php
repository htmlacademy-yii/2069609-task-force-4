<?php
namespace app\controllers;
use app\models\ExecutorCategory;
use app\models\Task;
use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class UserController extends Controller
{
    /**
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        if (!$id) {
            throw new NotFoundHttpException('The user does not exist');
        }
        $user = User::findOne($id);
        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        return $this->render('view', [
            'user' => $user
        ]);
    }
}
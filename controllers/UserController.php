<?php
namespace app\controllers;

use app\models\User;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class UserController extends SecuredController
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

    public function actionLogout() {
        Yii::$app->user->logout();
        return Yii::$app->response->redirect(['login']);
    }
}
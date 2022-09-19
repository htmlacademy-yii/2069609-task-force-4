<?php
namespace app\controllers;

use app\models\Category;
use app\models\forms\TaskSearchForm;
use Yii;
use yii\web\Controller;

class TasksController extends Controller
{
    public function actionIndex(): string
    {
        $categories = Category::getCategoryList();
        $taskSearch = new TaskSearchForm();
        if (Yii::$app->request->getIsPost()) {
            $taskSearch->load(Yii::$app->request->get());
        }

        $tasks = $taskSearch->search()->all();
        return $this->render('tasks', [
            'tasks' => $tasks,
            'model' => $taskSearch,
            'categories' => $categories
        ]);
    }

}

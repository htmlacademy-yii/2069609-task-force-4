<?php
namespace app\controllers;

use app\models\Category;
use app\models\forms\TaskSearchForm;
use app\models\Task;
use Yii;
use yii\web\Controller;

class TasksController extends Controller
{
    public function actionIndex(): string
    {
        $query = Task::find();
        $query->where(['status' => Task::STATUS_NEW]);
        $query->orderBy('date_of_publication DESC');
        $tasks = $query->all();

        $categories = Category::getCategoryList();
        $taskSearch = new TaskSearchForm();
        if (Yii::$app->request->getIsGet()) {
            $taskSearch->load(Yii::$app->request->get());
            if ($taskSearch->validate()) {
                $tasks = $taskSearch->search()->all();
            }
        }
        return $this->render('tasks', [
            'tasks' => $tasks,
            'model' => $taskSearch,
            'categories' => $categories
        ]);
    }
}

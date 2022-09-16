<?php
namespace app\controllers;

use app\models\Task;
use yii\web\Controller;

class TasksController extends Controller
{
    public function actionIndex(): string
    {
        $query = Task::find();

        $query->where(['status' => Task::STATUS_NEW]);
        $query->orderBy('date_of_publication DESC');
        $tasks = $query->all();
        return $this->render('tasks', ['tasks' => $tasks]);
    }
}
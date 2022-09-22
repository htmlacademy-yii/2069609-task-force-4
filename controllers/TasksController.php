<?php
namespace app\controllers;

use app\models\Category;
use app\models\forms\TaskSearchForm;
use app\models\Response;
use app\models\Task;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class TasksController extends Controller
{
    public function actionIndex(): string
    {
        $query = Task::find();
        $query->where(['status' => Task::STATUS_NEW]);
        $query->orderBy('date_of_publication DESC');
        $tasks = $query->all();

        $categories = Category::find()->all();
        $taskForm = new TaskSearchForm();
        if (Yii::$app->request->getIsPost()) {
            $taskForm->load(Yii::$app->request->post());
            if ($taskForm->validate()) {
                $tasks = $taskForm->search()->all();
            }
        }
        return $this->render('index', [
            'tasks' => $tasks,
            'model' => $taskForm,
            'categories' => $categories
        ]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionView($id){
        $task = Task::findOne($id);
        if (!$task) {
            throw new NotFoundHttpException('Task not found');
        }
        $responses = Response::find()->where(['task_id' => $id])->all();
        return $this->render('view', [
            'task' => $task,
            'responses' => $responses
        ]);
    }
}

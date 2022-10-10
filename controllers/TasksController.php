<?php
namespace app\controllers;

use app\models\Category;
use app\models\forms\TaskCreateForm;
use app\models\forms\TaskSearchForm;
use app\models\Task;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/** @var TaskCreateForm $model */

class TasksController extends SecuredController
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
        if (!$id) {
            throw new NotFoundHttpException('The task does not exist');
        }
        $task = Task::findOne($id);
        if (!$task) {
            throw new NotFoundHttpException('Task not found');
        }

        return $this->render('view', [
            'task' => $task
        ]);
    }

    public function actionCreate(){
        $taskCreateForm = new TaskCreateForm();
        if (Yii::$app->request->getIsPost()) {
            $taskCreateForm->load(Yii::$app->request->post());
            $taskCreateForm->files = UploadedFile::getInstances($taskCreateForm, 'files');
            if ($taskCreateForm->validate()) {
                $task = $taskCreateForm->createTask();
                $taskCreateForm->upload($task->id);
                return Yii::$app->response->redirect(['tasks/view', 'id' => $task->id]);
            }
        }
        return $this->render('create', ['model' => $taskCreateForm]);
    }

}

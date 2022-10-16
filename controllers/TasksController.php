<?php
namespace app\controllers;

use app\models\Category;
use app\models\forms\TaskCreateForm;
use app\models\forms\TaskSearchForm;
use app\models\forms\RespondForm;
use app\models\Response;
use app\models\Task;
use app\models\User;
use Exception;
use Yii;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

/** @var TaskCreateForm $model */

class TasksController extends SecuredController
{
    public function behaviors()
    {
        $rules = parent::behaviors();
        $rule = [
            'allow' => false,
            'actions' => ['create'],
            'roles' => ['@'],
            'matchCallback' => function ($rule, $action) {
                return (Yii::$app->user->identity->role === User::ROLE_EXECUTOR);
            },
            'denyCallback' => function ($rule, $action) {
                throw new ForbiddenHttpException('Извините, только заказчики могут создавать задачи');
            },
        ];
        array_unshift($rules['access']['rules'], $rule);

        return $rules;
    }

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
        $respondForm = new RespondForm();

        if (!$id) {
            throw new NotFoundHttpException('The task does not exist');
        }
        $task = Task::findOne($id);
        if (!$task) {
            throw new NotFoundHttpException('TaskAction not found');
        }

        return $this->render('view', [
            'task' => $task, 'model' => $respondForm,
        ]);
    }

    /**
     * @throws Exception
     */
    public function actionCreate()
    {
        $taskCreateForm = new TaskCreateForm();
        if (Yii::$app->request->getIsPost()) {
            $taskCreateForm->load(Yii::$app->request->post());
            $taskCreateForm->files = UploadedFile::getInstances($taskCreateForm, 'files');

            if ($taskCreateForm->validate()) {
                try {
                    $taskCreateForm->doTransaction($taskCreateForm);
                } catch (Exception $e) {
                    throw new ServerErrorHttpException('Loading error');
                }
            }
        }
        return $this->render('create', ['model' => $taskCreateForm]);
    }

    public function actionAgree($id) {
        $response = Response::findOne($id);
        $response->status = 1;
        $response->user->availability = 0;
        $response->save();
        $task = Task::findOne($response->task);
        $task->status = Task::STATUS_AT_WORK;
        $task->save();
        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }

    public function actionDisagree($id) {
        $response = Response::findOne($id);
        $response->status = 0;
        $response->save();
        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function actionRespond($id_task){
        //$idTask = Yii::$app->get('task_id');
        $task = Task::findOne($id_task);
        $respondForm = new RespondForm();
        if (Yii::$app->request->getIsPost()) {
            $respondForm->load(Yii::$app->request->post());
            if ($respondForm->validate()) {
                $respondForm->createRespond($id_task);
                return $this->goHome();
            }
        }
        return $this->render('view', [
            'task' => $task,
            'model' => $respondForm]);
    }

    public function actionDone() {

    }

    public function actionRefuse() {

    }

    //Отмена задания - доступна только заказчику

    /**
     * @throws ForbiddenHttpException
     */
    public function actionCancel($id) {
        $task = Task::findOne($id);
        if (Yii::$app->user->id === $task->user_id && $task->status === Task::STATUS_NEW) {
            $task->status = Task::STATUS_CANCELLED;
            $task->save();
            return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        }
        else {
            return throw new ForbiddenHttpException('Извините, эту задачу нельзя отменить');
        }
    }

}

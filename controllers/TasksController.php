<?php
namespace app\controllers;

use app\models\Category;
use app\models\forms\TaskCreateForm;
use app\models\forms\TaskSearchForm;
use app\models\forms\RespondForm;
use app\models\forms\RefuseForm;
use app\models\forms\CompleteForm;
use app\models\Task;
use app\models\User;
use Delta\TaskForce\CancelAction;
use Delta\TaskForce\GetDoneAction;
use Delta\TaskForce\RefuseAction;
use Delta\TaskForce\RespondAction;
use Delta\TaskForce\TaskAction;
use Exception;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

/** @var TaskCreateForm $model */
/** @var RefuseForm $modelRefuse */
/** @var CompleteForm $modelComplete */

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
        $completeForm = new CompleteForm();
        $respondForm = new RespondForm();
        $refuseForm = new RefuseForm();

        $task = Task::findOne($id);

        if (!$task) {
            throw new NotFoundHttpException('Task not found');
        }

        return $this->render('view', [
            'task' => $task, 'model' => $respondForm, 'modelRefuse' => $refuseForm, 'modelComplete' => $completeForm,
        ]);
    }

    /**
     * @throws Exception
     */
    public function actionCreate()
    {
        if (!TaskAction::isCreateTaskAvailable(Yii::$app->user->id)){
            throw new ForbiddenHttpException('Извините, только заказчики могут создавать задания');
        }

        $taskCreateForm = new TaskCreateForm();

        if (Yii::$app->request->getIsPost()) {
            $taskCreateForm->load(Yii::$app->request->post());
            $taskCreateForm->files = UploadedFile::getInstances($taskCreateForm, 'files');

            if ($taskCreateForm->validate()) {
                try {
                    $taskCreateForm->saveTask($taskCreateForm);
                } catch (Exception $e) {
                    throw new ServerErrorHttpException('Loading error');
                }
            }
        }
        return $this->render('create', ['model' => $taskCreateForm]);
    }

    /**
     * @throws Exception
     */
    public function actionAgree($id) {
        if (TaskAction::isAgreeResponseAvailable($id) && TaskAction::clickAgree($id)) {
                return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        } else {
            return throw new ForbiddenHttpException('Извините, данное дейтсвие вам недоступно');
        }
    }

    /**
     * @throws ForbiddenHttpException
     * @throws InternalErrorException
     */
    public function actionDisagree($id)
    {
        if (TaskAction::isDisagreeResponseAvailable($id) && TaskAction::clickDisagree($id)) {
            return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        } else {
            throw new ForbiddenHttpException('Извините, данное дейтсвие вам недоступно');
        }
    }

    /**
     * @throws ForbiddenHttpException
     */
    public function actionRespond($id_task)
    {
        $taskModel = new TaskAction(Task::findOne($id_task), Yii::$app->user->id);
        $taskModelAction = $taskModel->getAvailableActions();
        if (!$taskModelAction instanceof RespondAction) {
            throw new ForbiddenHttpException('Данное действие вам недоступно');
        }
            $task = Task::findOne($id_task);
            $respondForm = new RespondForm();
            if (Yii::$app->request->getIsPost()) {
                $respondForm->load(Yii::$app->request->post());
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
                    return ActiveForm::validate($respondForm);
                }
                if ($respondForm->validate()) {
                    $respondForm->createRespond($id_task);
                    return $this->goHome();
                }
            }

            return $this->render('view', [
                'task' => $task,
                'model' => $respondForm,
            ]);
    }

    /**
     * @throws ForbiddenHttpException
     * @throws Exception
     */
    public function actionComplete($id_task) {
        $taskModel = new TaskAction(Task::findOne($id_task), Yii::$app->user->id);
        $taskModelAction = $taskModel->getAvailableActions();
        if (!$taskModelAction instanceof GetDoneAction) {
            throw new ForbiddenHttpException('Данное действие вам недоступно');
        }
        $completeForm = new CompleteForm();
        if (Yii::$app->request->getIsPost()) {
            $completeForm->load(Yii::$app->request->post());
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($completeForm);
            }
            if ($completeForm->validate()) {
                $completeForm->completeTask($id_task);
                return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
            } else {
                throw new ForbiddenHttpException('Извините, поля в форме должны быть заполнены');
            }
        }
        throw new ForbiddenHttpException('Извините, что-то случилось');
    }

    /**
     * @throws Exception
     */
    public function actionRefuse($id_task) {
        $taskModel = new TaskAction(Task::findOne($id_task), Yii::$app->user->id);
        $taskModelAction = $taskModel->getAvailableActions();

        if (!$taskModelAction instanceof RefuseAction) {
            throw new ForbiddenHttpException('Данное действие вам недоступно');
        }
        $refuseForm = new RefuseForm();
        if (Yii::$app->request->getIsPost() && $refuseForm->load(Yii::$app->request->post()) && $refuseForm->validate()) {
            $refuseForm->refuseTask($id_task);
        }
        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }

    /**
     * @throws ForbiddenHttpException
     * @throws InternalErrorException
     */
    //Отмена задания - доступна только заказчику
    public function actionCancel($id)
    {
        $taskModel = new TaskAction(Task::findOne($id), Yii::$app->user->id);
        $taskModelAction = $taskModel->getAvailableActions();

        if (!$taskModelAction instanceof CancelAction) {
            throw new ForbiddenHttpException('Данное действие вам недоступно');
        }
        TaskAction::cancel($id);
        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }

}

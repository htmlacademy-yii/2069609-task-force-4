<?php
namespace app\controllers;

use app\models\Category;
use app\models\forms\TaskCreateForm;
use app\models\forms\TaskSearchForm;
use app\models\forms\RespondForm;
use app\models\forms\RefuseForm;
use app\models\forms\CompleteForm;
use app\models\Response;
use app\models\Task;
use app\models\User;
use Exception;
use Yii;
use yii\helpers\ArrayHelper;
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

        if (!$id) {
            throw new NotFoundHttpException('The task does not exist');
        }
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

    /**
     * @throws ForbiddenHttpException
     * @throws Exception
     */
    //это id response
    public function actionAgree($id) {
        $idAllResponses = ArrayHelper::getColumn(Response::find()->all(), 'id');
        if (!ArrayHelper::isIn($id, $idAllResponses)) {
            return throw new ForbiddenHttpException('Извините, отклик не найден');
        }

        $response = Response::findOne($id);
        if($response->user->availability === 0) {
            return throw new ForbiddenHttpException('Извините, исполнитель уже занят');
        }

        $task = Task::findOne($response->task_id);
        if ($task->user_id === Yii::$app->user->id)
        {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $response->status = 1;
                $user = User::findOne($response->user->id);
                $user->availability = 0;
                $user->save();
                $response->save();
                $task->status = Task::STATUS_AT_WORK;
                $task->save();
                $transaction->commit();
                return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
            } catch (Exception $e) {
                $transaction->rollback();
                throw new Exception('Loading error');
            }
        } else {
            return throw new ForbiddenHttpException('Извините, подтверждать отклик может только автор задания');
        }
    }


    public function actionDisagree($id) {
        $response = Response::findOne($id);
        $response->status = 0;
        $response->save();
        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }


    public function actionRespond($id_task){
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
     */
    public function actionComplete($id_task) {
        $completeForm = new CompleteForm();
        if (Yii::$app->request->getIsPost()) {
            $completeForm->load(Yii::$app->request->post());
            if ($completeForm->validate()) {
                $completeForm->completeTask($id_task);
                return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
            } else {
                return throw new ForbiddenHttpException('Извините, поля в форме должны быть заполнены');
            }
        }
        return throw new ForbiddenHttpException('Извините, что-то случилось');
    }

    public function actionRefuse($id_task) {
        $refuseForm = new RefuseForm();
        if (Yii::$app->request->getIsPost()) {
            $refuseForm->load(Yii::$app->request->post());
            if ($refuseForm->validate()) {
                $refuseForm->refuseTask($id_task);
                return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
            }
        }
    }

    /**
     * @throws ForbiddenHttpException
     */
    //Отмена задания - доступна только заказчику
    public function actionCancel($id) {
        $task = Task::findOne($id);
        if (Yii::$app->user->id === $task->user_id && $task->status === Task::STATUS_NEW) {
            $task->status = Task::STATUS_CANCELLED;
            if ($task->save()) {
                return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
            } else {
                return throw new ForbiddenHttpException('Извините, задание не сохранилось');
            }
        }
        else {
            return throw new ForbiddenHttpException('Извините, вы не можете отменить задание');
        }
    }

}

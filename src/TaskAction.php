<?php
namespace Delta\TaskForce;

use app\models\Response;
use app\models\Task;
use app\models\User;
use Delta\TaskForce\exceptions\IncomingDataException;
use Exception;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Yii;
use yii\web\ForbiddenHttpException;

class TaskAction {
    //статусы заданий
    const STATUS_NEW = 'new';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_AT_WORK = 'work';
    const STATUS_DONE = 'done';
    const STATUS_FAILED = 'failed';

    //действия над заданиями
    //со стороны заказчика новое/в работе
    const ACTION_CANCEL = 'cancel';
    const ACTION_GET_DONE = 'get done';

    //со стороны исполнителя новое/в работе
    const ACTION_RESPOND = 'respond';
    const ACTION_REFUSE = 'refuse';

    public Task $task;
    public int $idCurrentUser;

    //конструктор для получения значений: модель Задание и ID текущего пользователя
    public function __construct(Task $task, int $idCurrentUser)
    {
        $this->task = $task;
        $this->idCurrentUser = $idCurrentUser;
    }

    //метод для возврата «карты» статусов
    public function getStatusMap(): array
    {
        return [
            self::STATUS_NEW => 'Новое',
            self::STATUS_CANCELLED => 'Отменено',
            self::STATUS_AT_WORK => 'В работе',
            self::STATUS_DONE => 'Выполнено',
            self::STATUS_FAILED => 'Провалено'
        ];
    }

    //метод для возврата «карты» действий
    public function getActionMap(): array
    {
        return [
          self::ACTION_CANCEL => 'Отменить',
          self::ACTION_GET_DONE => 'Выполнено',
          self::ACTION_RESPOND => 'Откликнуться',
          self::ACTION_REFUSE => 'Отказаться'
        ];
    }

    //метод для получения доступных объектов действий для указанного статуса
    public function getAvailableActions(): ?Action
    {
        $isCancelActionAvailable = CancelAction::isAvailable($this->task, $this->idCurrentUser);
        $isRespondActionAvailable = RespondAction::isAvailable($this->task, $this->idCurrentUser);
        $isGetDoneActionAvailable = GetDoneAction::isAvailable($this->task, $this->idCurrentUser);
        $isRefuseActionAvailable = RefuseAction::isAvailable($this->task, $this->idCurrentUser);

        if ($this->task->status !== self::STATUS_NEW) {
            if ($this->task->status !== self::STATUS_AT_WORK) {
                // пришлось убрать ошибку и вернуть null, тк после отмены задания вылезала ошибка потом решить этот вопрос
                //throw new IncomingDataException("Для текущего статуса нет доступных действий");
                return null;
            }
        }

        if ($this->task->status=== self::STATUS_NEW && $isCancelActionAvailable) {
            return new CancelAction();
        }
        if ($this->task->status === self::STATUS_AT_WORK && $isGetDoneActionAvailable) {
            return new GetDoneAction();
        }
        if ($this->task->status === self::STATUS_NEW && $isRespondActionAvailable) {
            return new RespondAction();
        }
        if ($this->task->status === self::STATUS_AT_WORK && $isRefuseActionAvailable) {
            return new RefuseAction();
        }
        return null;
    }

    public static function isCreateTaskAvailable($userCurrentId) {
        return User::findOne($userCurrentId)->role === User::ROLE_CUSTOMER;
    }

    public static function isAgreeResponseAvailable($idResponse) {

        $response = Response::findOne($idResponse);
        $isResponseExists = true;
        $isExecutorAvailable = true;
        $isTaskAlreadyHaveNotActiveResponse = true;
        $isCurrentUserIsTaskAuthor = true;

        if (!$response){
            $isResponseExists = false;
        }
        if ($response->user->availability !== User::IS_AVAILABILITY) {
            $isExecutorAvailable = false;
        }
        if ($response->task->status !== Task::STATUS_NEW){
            $isTaskAlreadyHaveNotActiveResponse = false;
        }
        if($response->task->user_id !== Yii::$app->user->id){
            $isCurrentUserIsTaskAuthor = false;
        }
        return $isResponseExists && $isExecutorAvailable && $isTaskAlreadyHaveNotActiveResponse && $isCurrentUserIsTaskAuthor;
    }

    public static function isDisagreeResponseAvailable($idResponse) {
        $response = Response::findOne($idResponse);
        $isResponseExists = true;
        $isCurrentUserIsTaskAuthor = true;
        $isResponseIsNotActive = true;
        $isTaskStatusIsNew = true;

        if (!$response){
            $isResponseExists = false;
        }
        if($response->task->user_id !== Yii::$app->user->id){
            $isCurrentUserIsTaskAuthor = false;
        }
        if ($response->status == Response::STATUS_ACTIVE_RESPONSE){
            $isResponseIsNotActive = false;
        }
        if ($response->task->status !== Task::STATUS_NEW){
            $isTaskStatusIsNew = false;
        }
        return $isResponseExists && $isCurrentUserIsTaskAuthor && $isResponseIsNotActive && $isTaskStatusIsNew;
    }

    /**
     * @throws InternalErrorException
     */
    public static function cancel($id_task){
        $task = Task::findOne($id_task);
        $task->status = Task::STATUS_CANCELLED;
        if ($task->save()){
            return true;
        } else {
            throw new InternalErrorException('Извините, задание не сохранилось');
        }
    }

    /**
     * @throws Exception
     */
    public static function clickAgree($id){
        $response = Response::findOne($id);
        $task = Task::findOne($response->task_id);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $response->status = 1;
            $user = User::findOne($response->user->id);
            $user->availability = 0;
            if (!$user->save()) {
                throw new ForbiddenHttpException('Извините, произошла ошибка сохранения');
            };
            if (!$response->save()){
                throw new ForbiddenHttpException('Извините, произошла ошибка сохранения');
            };
            $task->status = Task::STATUS_AT_WORK;
            if (!$task->save()){
                throw new ForbiddenHttpException('Извините, произошла ошибка сохранения');
            };
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollback();
            throw new Exception('Loading error');
        }
    }

    /**
     * @throws InternalErrorException
     */
    public static function clickDisagree($idResponse){
        $response = Response::findOne($idResponse);
        $response->status = 0;
        if (!$response->save()) {
            throw new InternalErrorException('Извините, произошла ошибка сохранения');
        } else {
            return true;
        }
    }
}

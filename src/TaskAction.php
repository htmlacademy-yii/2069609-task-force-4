<?php
namespace Delta\TaskForce;

use app\models\Response;
use app\models\Task;
use app\models\User;
use Delta\TaskForce\exceptions\IncomingDataException;
use Yii;

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
        //id автора задачи
        $idCustomer = $this->task->user_id;

        //id исполнителя
        $executor = Response::findOne([
            'task_id' => $this->task->id,
            'status' => 1,
        ]);

        $userCurrent = User::findOne(Yii::$app->user->id);

        $isCancelActionAvailable = CancelAction::isAvailable($this->idCurrentUser, $idCustomer);
        $isRespondActionAvailable = RespondAction::isAvailable($this->idCurrentUser, $this->task->id);
        $isGetDoneActionAvailable = GetDoneAction::isAvailable($this->idCurrentUser, $idCustomer);
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
}

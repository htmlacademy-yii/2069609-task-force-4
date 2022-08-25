<?php
namespace Delta\TaskForce;

use Delta\TaskForce\ex\IncomingDataException;

class Task {
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

    public int $idCustomer;
    public int $idExecutor;

    //конструктор для получения значений ID исполнителя и ID заказчика

    public function __construct(int $idCustomer, int $idExecutor)
    {
        $this->idCustomer = $idCustomer;
        $this->idExecutor = $idExecutor;
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

    //метод для получения доступных действий для указанного статуса

    /**
     * @throws IncomingDataException
     */
    public function getAvailableActions(string $status, int $userCurrentId): ?Action
    {
        $isCanselActionAvailable = CancelAction::isAvailable($userCurrentId, $this->idCustomer, $this->idExecutor);
        $isRespondActionAvailable = RespondAction::isAvailable($userCurrentId, $this->idCustomer, $this->idExecutor);
        $isGetDoneActionAvailable = GetDoneAction::isAvailable($userCurrentId, $this->idCustomer, $this->idExecutor);
        $isRefuseActionAvailable = RefuseAction::isAvailable($userCurrentId, $this->idCustomer, $this->idExecutor);

        if ($status !== self::STATUS_NEW) {
            if ($status !== self::STATUS_AT_WORK) {
                throw new IncomingDataException("Для текущего статуса нет доступных действий");
            }
        }

        if ($status === self::STATUS_NEW && $isCanselActionAvailable) {
            return new CancelAction();
        }
        if ($status === self::STATUS_AT_WORK && $isGetDoneActionAvailable) {
            return new GetDoneAction();
        }
        if ($status === self::STATUS_NEW && $isRespondActionAvailable) {
            return new RespondAction();
        }
        if ($status === self::STATUS_AT_WORK && $isRefuseActionAvailable) {
            return new RefuseAction();
        }
        return null;
    }

    //метод для получения статуса, в которой он перейдёт после выполнения указанного действия

    /**
     * @throws IncomingDataException
     */
    public function getNextStatus(string $action, string $currentStatus, int $userCurrentId): ?string
    {
        if (($action !== self::ACTION_CANCEL) && ($action !== self::ACTION_RESPOND) && ($action !== self::ACTION_GET_DONE) && ($action !== self::ACTION_REFUSE)
        || ($currentStatus !== self::STATUS_NEW) && ($currentStatus === self::STATUS_AT_WORK)) {
            throw new IncomingDataException("Вводимого действия/текущего статуса не существует");
        }
        if ($action === self::ACTION_CANCEL && $currentStatus === self::STATUS_NEW && $userCurrentId === $this->idCustomer) {
            return self::STATUS_CANCELLED;
        }
        if ($action === self::ACTION_RESPOND && $currentStatus === self::STATUS_NEW && $userCurrentId === $this->idExecutor) {
            return self::STATUS_AT_WORK;
        }
        if ($action === self::ACTION_GET_DONE && $currentStatus === self::STATUS_AT_WORK && $userCurrentId === $this->idCustomer) {
            return self::STATUS_DONE;
        }
        if ($action === self::ACTION_REFUSE && $currentStatus === self::STATUS_AT_WORK && $userCurrentId === $this->idExecutor) {
            return self::STATUS_FAILED;
        }
        return null;
    }
}

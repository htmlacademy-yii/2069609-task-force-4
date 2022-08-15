<?php
namespace Delta\TaskForce\business_logic;

use JetBrains\PhpStorm\ArrayShape;

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
    #[ArrayShape([
        self::STATUS_NEW => "string",
        self::STATUS_CANCELLED => "string",
        self::STATUS_AT_WORK => "string",
        self::STATUS_DONE => "string",
        self::STATUS_FAILED => "string"
    ])] public function getStatusMap(): array
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
    #[ArrayShape([
        self::ACTION_CANCEL => "string",
        self::ACTION_GET_DONE => "string",
        self::ACTION_RESPOND => "string",
        self::ACTION_REFUSE => "string"
    ])] public function getActionMap(): array
    {
        return [
          self::ACTION_CANCEL => 'Отменить',
          self::ACTION_GET_DONE => 'Выполнено',
          self::ACTION_RESPOND => 'Откликнуться',
          self::ACTION_REFUSE => 'Отказаться'
        ];
    }

    //метод для получения доступных действий для указанного статуса
    public function getAvailableActions(string $status, int $userCurrentId): ?string
    {
        if ($status === self::STATUS_NEW && $userCurrentId === $this->idCustomer) {
            return self::ACTION_CANCEL;
        }
        if ($status === self::STATUS_NEW && $userCurrentId === $this->idExecutor) {
            return self::ACTION_RESPOND;
        }
        if ($status === self::STATUS_AT_WORK && $userCurrentId === $this->idCustomer) {
            return self::ACTION_GET_DONE;
        }
        if ($status === self::STATUS_AT_WORK && $userCurrentId === $this->idExecutor) {
            return self::ACTION_REFUSE;
        }
        return null;
    }

    //метод для получения статуса, в которой он перейдёт после выполнения указанного действия
    public function getNextStatus(string $action, string $currentStatus, int $userCurrentId): ?string
    {
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

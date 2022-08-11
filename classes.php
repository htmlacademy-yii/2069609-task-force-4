<?php
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
    const ACTION_GET_DONE = 'get_done';
    //со стороны исполнителя новое/в работе
    const ACTION_RESPOND = 'respond';
    const ACTION_REFUSE = 'refuse';

    public $idCustomer = null;
    public $idExecutor = null;

    //конструктор для получения значений ID исполнителя и ID заказчика
    public function __construct($idCustomer, $idExecutor)
    {
        $this->idCustomer = $idCustomer;
        $this->idExecutor = $idExecutor;
    }

    //метод для возврата «карты» статусов
    public function getStatusMap() {
        return [
            self::STATUS_NEW => 'Новое',
            self::STATUS_CANCELLED => 'Отменено',
            self::STATUS_AT_WORK => 'В работе',
            self::STATUS_DONE => 'Выполнено',
            self::STATUS_FAILED => 'Провалено'
        ];
    }

    //метод для возврата «карты» действий
    public function getActionMap() {
        return [
          self::ACTION_CANCEL => 'Отменить',
          self::ACTION_GET_DONE => 'Выполнено',
          self::ACTION_RESPOND => 'Откликнуться',
          self::ACTION_REFUSE => 'Отказаться'
        ];
    }

    //метод для получения доступных действий для указанного статуса
    public function getAvailableActions($status) {
        if ($status === self::STATUS_NEW) {
            return [self::ACTION_CANCEL, self::ACTION_RESPOND];
        }
        if ($status === self::STATUS_AT_WORK) {
            return [self::ACTION_GET_DONE, self::ACTION_REFUSE];
        }
        return null;
    }

    //метод для получения статуса, в которой он перейдёт после выполнения указанного действия
    public function getCurrentStatus($action) {
        if ($action === self::ACTION_CANCEL) {
            return self::STATUS_CANCELLED;
        }
        if ($action === self::ACTION_GET_DONE) {
            return self::STATUS_DONE;
        }
        if ($action === self::ACTION_RESPOND) {
            return self::STATUS_AT_WORK;
        }
        if ($action === self::ACTION_REFUSE) {
            return self::STATUS_FAILED;
        }
        return null;
    }
}

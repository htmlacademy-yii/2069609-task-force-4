<?php

namespace Delta\TaskForce;

use Yii;

class CancelAction extends Action
{
    const ACTION = 'Отменить';
    const NAME = 'cancel';

    public function getAction(): string
    {
        return self::ACTION;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    //буду сравнивать с $idCustomer
    public static function isAvailable(int $userCurrentId, int $idCustomer): bool
    {
        if ($userCurrentId === $idCustomer) {
            return true;
        } else {
            return false;
        }
    }

    public function getClass(): string
    {
        return 'button button--yellow action-btn';
    }

    public function getDataAction()
    {
        return '';
    }

    public function getUrlName()
    {
        $taskId = Yii::$app->request->get('id');
        return ['/tasks/cancel', 'id' => $taskId];
    }
}

<?php

namespace Delta\TaskForce;

class GetDoneAction extends Action
{
    const ACTION = 'Выполнено';
    const NAME = 'get done';

    public function getAction(): string
    {
        return self::ACTION;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public static function isAvailable(int $userCurrentId, int $idCustomer, int $idExecutor): bool
    {
        if ($userCurrentId === $idCustomer) {
            return true;
        } else {
            return false;
        }
    }
}

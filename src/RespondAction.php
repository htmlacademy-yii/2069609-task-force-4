<?php

namespace Delta\TaskForce;

class RespondAction extends Action
{
    const ACTION = 'Откликнуться';
    const NAME = 'respond';

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
        if ($userCurrentId === $idExecutor) {
            return true;
        } else {
            return false;
        }
    }
}

<?php

namespace Delta\TaskForce;

class CancelAction extends Action
{
    private string $action = 'Отменить';
    private string $name = 'cansel';

    public function getAction(string $action): string
    {
        return $action;
    }

    public function getName(string $name): string
    {
        return $name;
    }

    public function getVerification(int $userCurrentId, int $idCustomer, int $idExecutor): bool
    {
        if ($userCurrentId === $idCustomer) {
            return true;
        } else {
            return false;
        }
    }
}

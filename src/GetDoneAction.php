<?php

namespace Delta\TaskForce;

class GetDoneAction extends Action
{
    private string $action = 'Выполнено';
    private string $name = 'get done';

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

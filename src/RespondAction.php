<?php

namespace Delta\TaskForce;

class RespondAction extends Action
{
    private string $action = 'Откликнуться';
    private string $name = 'respond';

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
        if ($userCurrentId === $idExecutor) {
            return true;
        } else {
            return false;
        }
    }
}

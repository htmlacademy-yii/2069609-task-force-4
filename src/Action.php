<?php

namespace Delta\TaskForce;

abstract class Action
{
    abstract public function getAction(string $action);
    abstract public function getName(string $name);
    abstract public function getVerification(int $userCurrentId, int $idCustomer, int $idExecutor);
}

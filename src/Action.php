<?php

namespace Delta\TaskForce;

abstract class Action
{
    abstract public function getAction(): string;
    abstract public function getName(): string;
    abstract public static function isAvailable(int $userCurrentId, int $idCustomer, int $idExecutor): bool;
}

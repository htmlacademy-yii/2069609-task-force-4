<?php

namespace Delta\TaskForce;

use app\models\Task;

abstract class Action
{
    abstract public function getAction(): string;
    abstract public function getName(): string;
    abstract public static function isAvailable(Task $task, int $userCurrentId): bool;
    abstract public function getClass(): string;
    abstract public function getDataAction(): string;
    abstract public function getUrlName(): string;
}

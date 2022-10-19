<?php

namespace Delta\TaskForce;

abstract class Action
{
    abstract public function getAction(): string;
    abstract public function getName(): string;
    abstract public function getClass(): string;
    abstract public function getDataAction();
    abstract public function getUrlName();
    //abstract public static function isAvailable(int $userCurrentId, int $id): bool;
}

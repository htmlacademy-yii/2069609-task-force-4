<?php

namespace Delta\TaskForce;

class RefuseAction extends Action
{
    const ACTION = 'Отказаться';
    const NAME = 'refuse';

    public function getAction(): string
    {
        return self::ACTION;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    //буду сравнивать с idExecutor
    public static function isAvailable(int $userCurrentId, int $idExecutor): bool
    {
        if ($userCurrentId === $idExecutor) {
            return true;
        } else {
            return false;
        }
    }

    public function getClass(): string
    {
        return 'button button--orange action-btn';
    }

    public function getDataAction(){
        return 'refusal';
    }

    public function getUrlName()
    {
        return ['/tasks/refuse'];
    }
}
